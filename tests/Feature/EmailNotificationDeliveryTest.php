<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\AnonymousMessage;
use App\Models\Report;
use App\Models\ReportProgressRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\Email;
use Tests\TestCase;

class EmailNotificationDeliveryTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set([
            'mail.default' => 'array',
            'mail.from.address' => 'no-reply@tambora.example',
            'mail.from.name' => 'TAMBORA',
            'queue.default' => 'sync',
            'services.turnstile.site_key' => null,
            'services.turnstile.secret_key' => null,
        ]);
    }

    public function test_submission_emails_the_reporter_and_active_operators_and_preserves_portal_notifications(): void
    {
        Storage::fake('local');
        $operator = User::factory()->create(['email' => 'operator@example.test']);
        User::factory()->create(['email' => 'operator2@example.test']);
        $administrator = User::factory()->superAdmin()->create();
        User::factory()->police()->create();
        User::factory()->create(['is_active' => false]);

        $this->post(route('reports.store'), $this->submissionPayload())
            ->assertRedirect(route('reports.success'));

        $report = Report::query()->sole();
        $this->assertSame('reporter@example.test', $report->reporter_email);
        $this->assertSame(ReportStatus::Submitted, $report->status);
        $this->assertSame(['operator2@example.test', 'operator@example.test', 'reporter@example.test'], $this->recipients());
        $this->assertSame(1, $operator->notifications()->count());
        $this->assertSame(1, $administrator->notifications()->count());
        $this->assertDatabaseCount('notifications', 3);
        $email = $this->emails()->first(fn (Email $email): bool => $email->getTo()[0]->getAddress() === 'reporter@example.test');
        $this->assertStringContainsString($report->public_code, $email->getSubject());
        $this->assertSame('no-reply@tambora.example', $email->getFrom()[0]->getAddress());
    }

    public function test_invalid_submission_does_not_save_a_report_or_send_email(): void
    {
        Storage::fake('local');
        $payload = $this->submissionPayload();
        $payload['good_faith'] = false;

        $this->from(route('reports.create'))->post(route('reports.store'), $payload)
            ->assertSessionHasErrors('good_faith');

        $this->assertDatabaseCount('reports', 0);
        $this->assertSame([], $this->recipients());
    }

    public function test_reporter_chat_emails_only_active_operators(): void
    {
        $operator = User::factory()->create(['email' => 'operator@example.test']);
        $administrator = User::factory()->superAdmin()->create();
        User::factory()->police()->create();
        User::factory()->create(['is_active' => false]);
        $report = Report::factory()->create(['reporter_email' => 'reporter@example.test']);

        $this->withSession($this->trackingSession($report))
            ->postJson(route('reports.messages.store', ['report' => $report->public_code]), ['body' => 'Informasi chat rahasia dari pelapor.'])
            ->assertCreated();

        $this->assertDatabaseHas(AnonymousMessage::class, ['report_id' => $report->id, 'sender_type' => 'reporter', 'body' => 'Informasi chat rahasia dari pelapor.']);
        $this->assertSame(['operator@example.test'], $this->recipients());
        $this->assertSame(1, $operator->notifications()->count());
        $this->assertSame(1, $administrator->notifications()->count());
        $this->assertStringNotContainsString('Informasi chat rahasia', $this->emails()->sole()->getTextBody());
    }

    public function test_unverified_chat_cannot_send_an_email(): void
    {
        User::factory()->create();
        $report = Report::factory()->create(['reporter_email' => 'reporter@example.test']);

        $this->postJson(route('reports.messages.store', ['report' => $report->public_code]), ['body' => 'Pesan tanpa akses pelacakan.'])
            ->assertNotFound();

        $this->assertDatabaseCount('anonymous_messages', 0);
        $this->assertSame([], $this->recipients());
    }

    public function test_staff_reply_emails_only_the_reporter_of_that_report(): void
    {
        $operator = User::factory()->create();
        $report = Report::factory()->create(['reporter_email' => 'reporter@example.test']);
        Report::factory()->create(['reporter_email' => 'other@example.test']);

        Livewire::actingAs($operator)->test('admin.report-conversation', ['record' => $report])
            ->set('body', 'Balasan rahasia yang hanya tersedia di aplikasi.')
            ->call('send')->assertHasNoErrors();

        $this->assertDatabaseHas(AnonymousMessage::class, ['report_id' => $report->id, 'user_id' => $operator->id, 'sender_type' => 'admin']);
        $this->assertSame(['reporter@example.test'], $this->recipients());
        $this->assertStringNotContainsString('Balasan rahasia', $this->emails()->sole()->getTextBody());
    }

    public function test_aph_cannot_send_a_reply_or_trigger_reporter_email(): void
    {
        $report = Report::factory()->create(['reporter_email' => 'reporter@example.test']);

        Livewire::actingAs(User::factory()->police()->create())
            ->test('admin.report-conversation', ['record' => $report])
            ->set('body', 'Balasan tanpa kewenangan.')->call('send')->assertForbidden();

        $this->assertDatabaseCount('anonymous_messages', 0);
        $this->assertSame([], $this->recipients());
    }

    #[TestWith(['submitted', 'received'])]
    #[TestWith(['received', 'coordination'])]
    #[TestWith(['coordination', 'field_action'])]
    #[TestWith(['field_action', 'completed'])]
    public function test_published_status_changes_email_the_reporter(string $fromStatus, string $toStatus): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $report = Report::factory()->create(['status' => $fromStatus, 'reporter_email' => 'reporter@example.test']);

        $report->advanceStatus($administrator, 'Pembaruan publik yang tersedia di aplikasi.', 'Catatan internal rahasia.');

        $this->assertSame($toStatus, $report->fresh()->status->value);
        $this->assertSame(['reporter@example.test'], $this->recipients());
        $this->assertStringContainsString(ReportStatus::from($toStatus)->label(), $this->emails()->sole()->getTextBody());
        $this->assertStringNotContainsString('Catatan internal rahasia', $this->emails()->sole()->getTextBody());
    }

    public function test_pending_progress_emails_only_active_administrators(): void
    {
        $operator = User::factory()->create();
        $administrator = User::factory()->superAdmin()->create(['email' => 'administrator@example.test']);
        User::factory()->superAdmin()->create(['is_active' => false]);
        User::factory()->police()->create();
        $report = Report::factory()->received()->create(['reporter_email' => 'reporter@example.test']);

        $request = ReportProgressRequest::submit($report, $operator, ['public_note' => 'Usulan yang belum dipublikasikan.', 'internal_note' => 'Rahasia pengajuan.']);

        $this->assertSame('pending', $request->status);
        $this->assertSame(ReportStatus::Received, $report->fresh()->status);
        $this->assertSame(['administrator@example.test'], $this->recipients());
        $this->assertSame(1, $administrator->notifications()->count());
        $this->assertStringNotContainsString('Rahasia pengajuan', $this->emails()->sole()->getTextBody());
    }

    public function test_approval_emails_the_reporter_once_and_repeated_approval_does_not_send_again(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $report = Report::factory()->received()->create(['reporter_email' => 'reporter@example.test']);
        $request = ReportProgressRequest::factory()->create(['report_id' => $report->id, 'internal_note' => 'Rahasia persetujuan.']);

        $request->approve($administrator);

        $this->assertSame('approved', $request->fresh()->status);
        $this->assertSame(ReportStatus::Coordination, $report->fresh()->status);
        $this->assertSame(['reporter@example.test'], $this->recipients());
        try {
            $request->approve($administrator);
            $this->fail('Pengajuan yang telah disetujui harus menolak persetujuan ulang.');
        } catch (ValidationException) {
            $this->assertSame(['reporter@example.test'], $this->recipients());
            $this->assertDatabaseCount('report_status_histories', 1);
        }
    }

    public function test_rejected_progress_does_not_email_the_reporter(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $report = Report::factory()->received()->create(['reporter_email' => 'reporter@example.test']);
        $request = ReportProgressRequest::factory()->create(['report_id' => $report->id]);

        $request->reject($administrator, 'Dokumentasi belum cukup untuk persetujuan.');

        $this->assertSame('rejected', $request->fresh()->status);
        $this->assertSame(ReportStatus::Received, $report->fresh()->status);
        $this->assertSame([], $this->recipients());
    }

    public function test_status_correction_emails_the_reporter_without_internal_reason(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $report = Report::factory()->completed()->create(['reporter_email' => 'reporter@example.test']);

        $report->correctStatus($administrator, ReportStatus::Received, 'Alasan koreksi internal rahasia.');

        $this->assertSame(ReportStatus::Received, $report->fresh()->status);
        $this->assertSame(['reporter@example.test'], $this->recipients());
        $this->assertStringNotContainsString('Alasan koreksi internal', $this->emails()->sole()->getTextBody());
    }

    public function test_public_note_update_emails_the_reporter(): void
    {
        $report = Report::factory()->received()->create(['reporter_email' => 'reporter@example.test']);

        $report->update(['public_update' => 'Informasi publik telah diperbarui.']);

        $this->assertSame(['reporter@example.test'], $this->recipients());
    }

    public function test_internal_edits_and_unchanged_saves_do_not_email_the_reporter(): void
    {
        $report = Report::factory()->create(['reporter_email' => 'reporter@example.test']);

        $report->save();
        $report->update(['internal_notes' => 'Catatan internal yang baru.']);

        $this->assertSame([], $this->recipients());
    }

    #[TestWith([null])]
    #[TestWith([''])]
    #[TestWith(['invalid-email'])]
    public function test_legacy_reports_without_valid_email_can_still_receive_progress_and_replies(?string $email): void
    {
        $report = Report::factory()->create(['reporter_email' => $email]);
        $operator = User::factory()->create();

        $report->advanceStatus($operator);
        Livewire::actingAs($operator)->test('admin.report-conversation', ['record' => $report])
            ->set('body', 'Laporan tetap bisa ditangani.')->call('send')->assertHasNoErrors();

        $this->assertSame(ReportStatus::Received, $report->fresh()->status);
        $this->assertDatabaseCount('anonymous_messages', 1);
        $this->assertSame([], $this->recipients());
    }

    /** @return Collection<int, Email> */
    private function emails(): Collection
    {
        return Mail::mailer('array')->getSymfonyTransport()->messages()
            ->map(fn (SentMessage $message): Email => $message->getOriginalMessage());
    }

    /** @return array<int, string> */
    private function recipients(): array
    {
        return $this->emails()->map(fn (Email $email): string => $email->getTo()[0]->getAddress())->sort()->values()->all();
    }

    /** @return array<string, int> */
    private function trackingSession(Report $report): array
    {
        return ["tracked_reports.{$report->id}" => now()->addMinutes(30)->getTimestamp()];
    }

    /** @return array<string, mixed> */
    private function submissionPayload(): array
    {
        $identity = ['reporter_name' => 'Pelapor Uji', 'reporter_email' => 'reporter@example.test'];
        $verification = $this->postJson(route('reports.verify'), $identity)->assertOk();

        return [
            ...$identity,
            'verification_id' => $verification->json('verification_id'),
            'incident_type' => 'kupva_tanpa_izin',
            'business_name' => 'Money Changer Contoh',
            'incident_date' => now()->subDay()->toDateString(),
            'incident_time' => '14:30',
            'description' => 'Terlihat kegiatan penukaran uang tanpa papan izin yang jelas.',
            'regency' => 'Kota Mataram',
            'latitude' => -8.5830695,
            'longitude' => 116.1161800,
            'location_confirmed' => '1',
            'evidence' => [UploadedFile::fake()->image('bukti.jpg')],
            'good_faith' => '1',
        ];
    }
}
