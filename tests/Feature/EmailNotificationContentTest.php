<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\ReportProgressRequest;
use App\Models\User;
use App\Notifications\Admin\NewReporterMessage;
use App\Notifications\Admin\NewReportSubmitted;
use App\Notifications\Admin\ReportProgressApprovalRequested;
use App\Notifications\MailNotification;
use App\Notifications\NewStaffMessage;
use App\Notifications\ReportStatusUpdated;
use App\Notifications\ReportSubmitted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Mime\Email;
use Tests\TestCase;

class EmailNotificationContentTest extends TestCase
{
    /** @return array<string, array{string, string, string, string}> */
    public static function notificationEmails(): array
    {
        return [
            'confirmation' => [ReportSubmitted::class, 'Laporan berhasil dikirim', 'admin', '/status'],
            'published progress' => [ReportStatusUpdated::class, 'Pembaruan laporan', 'admin', '/status'],
            'staff reply' => [NewStaffMessage::class, 'Balasan baru dari petugas', 'admin', '/status'],
            'new report for operator' => [NewReportSubmitted::class, 'Laporan baru masuk', 'admin', '/admin/laporan/42'],
            'reporter chat for operator' => [NewReporterMessage::class, 'Pesan baru dari pelapor', 'admin', '/admin/laporan/42#komunikasi-anonim'],
            'approval for administrator' => [ReportProgressApprovalRequested::class, 'Pengajuan progres menunggu persetujuan', 'super_admin', '/admin/persetujuan-progres/73'],
        ];
    }

    #[DataProvider('notificationEmails')]
    public function test_email_has_branded_sender_html_and_text_and_keeps_private_information_in_the_app(string $notificationClass, string $subject, string $role, string $actionPath): void
    {
        config()->set([
            'mail.default' => 'array',
            'mail.from.address' => 'laporkupva@gmail.com',
            'mail.from.name' => 'TAMBORA (No Reply)',
            'app.url' => 'https://tambora.example',
        ]);
        URL::forceRootUrl('https://tambora.example');
        URL::forceScheme('https');
        $report = Report::factory()->make([
            'id' => 42, 'public_code' => 'LKP-AB12-CD34', 'status' => ReportStatus::Coordination,
            'regency' => 'Kota Mataram', 'reporter_name' => 'Identitas pelapor rahasia',
            'reporter_email' => 'private-reporter@example.test', 'reporter_phone' => '+6281234567890',
            'internal_notes' => 'Catatan internal sangat rahasia', 'tracking_pin_hash' => 'hash-rahasia',
            'public_update' => 'Catatan publik dibaca di aplikasi',
        ]);
        $notification = $this->makeNotification($notificationClass, $report);
        $recipient = User::factory()->make(['email' => 'recipient@example.test', 'role' => $role]);

        Notification::sendNow($recipient, $notification, ['mail']);

        /** @var Email $email */
        $email = Mail::mailer('array')->getSymfonyTransport()->messages()->sole()->getOriginalMessage();
        $this->assertSame('TAMBORA · '.$subject.' · LKP-AB12-CD34', $email->getSubject());
        $this->assertSame('laporkupva@gmail.com', $email->getFrom()[0]->getAddress());
        $this->assertSame('TAMBORA (No Reply)', $email->getFrom()[0]->getName());
        $this->assertSame(['recipient@example.test'], array_map(fn ($address): string => $address->getAddress(), $email->getTo()));
        $this->assertSame([], $email->getCc());
        $this->assertSame([], $email->getBcc());
        $this->assertSame([], $email->getAttachments());
        foreach ([$email->getHtmlBody(), $email->getTextBody()] as $body) {
            $this->assertNotEmpty($body);
            $this->assertStringContainsString('LKP-AB12-CD34', $body);
            $this->assertStringContainsString('no-reply', $body);
            $this->assertStringContainsString('Jika tombol', $body);
            $this->assertStringNotContainsString("If you're having trouble", $body);
            $this->assertStringContainsString('https://tambora.example'.$actionPath, $body);
            foreach (['Identitas pelapor rahasia', 'private-reporter@example.test', '+6281234567890', 'Catatan internal sangat rahasia', 'hash-rahasia', 'Chat pribadi rahasia', 'Alasan internal pengajuan'] as $privateValue) {
                $this->assertStringNotContainsString($privateValue, $body);
            }
        }
    }

    public function test_progress_email_keeps_the_published_status_at_dispatch_time(): void
    {
        $notification = new ReportStatusUpdated('LKP-AB12-CD34', ReportStatus::Received);

        $mail = $notification->toMail(new User);

        $this->assertStringContainsString('Laporan diterima', $mail->render());
        $this->assertStringNotContainsString('Koordinasi dengan APH', $mail->render());
    }

    public function test_notification_escapes_location_text_in_email(): void
    {
        $report = Report::factory()->make([
            'id' => 42, 'public_code' => 'LKP-AB12-CD34',
            'regency' => '<script>alert("xss")</script>',
        ]);

        $html = (new NewReportSubmitted($report))->toMail(new User)->render();

        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringNotContainsString('<script>', $html);
    }

    /** @param class-string<MailNotification> $notificationClass */
    private function makeNotification(string $notificationClass, Report $report): MailNotification
    {
        $request = (new ReportProgressRequest)->forceFill([
            'id' => 73, 'report_id' => $report->id, 'to_status' => ReportStatus::Coordination,
            'status' => 'pending',
            'internal_note' => 'Alasan internal pengajuan',
        ])->setRelation('report', $report);

        return match ($notificationClass) {
            ReportSubmitted::class => new ReportSubmitted($report->public_code),
            ReportStatusUpdated::class => new ReportStatusUpdated($report->public_code, $report->status),
            NewStaffMessage::class => new NewStaffMessage($report->public_code),
            NewReportSubmitted::class => new NewReportSubmitted($report),
            NewReporterMessage::class => new NewReporterMessage($report, 'Chat pribadi rahasia'),
            ReportProgressApprovalRequested::class => new ReportProgressApprovalRequested($request),
        };
    }
}
