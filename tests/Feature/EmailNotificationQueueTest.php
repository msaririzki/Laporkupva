<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\ReportProgressRequest;
use App\Models\User;
use App\Notifications\Admin\NewReportSubmitted;
use App\Notifications\ReportSubmitted;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Notifications\Events\BroadcastNotificationCreated;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\WorkerOptions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\TestWith;
use RuntimeException;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class EmailNotificationQueueTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set([
            'mail.default' => 'array',
            'mail.from.address' => 'laporkupva@gmail.com',
            'mail.from.name' => 'TAMBORA (No Reply)',
            'queue.default' => 'database',
        ]);
    }

    public function test_reporter_email_is_encrypted_and_runs_only_after_transaction_commit(): void
    {
        $report = Report::factory()->create(['reporter_email' => 'private-reporter@example.test']);

        DB::transaction(function () use ($report): void {
            $report->advanceStatus(User::factory()->create());
            $this->assertDatabaseCount('jobs', 0);
            $this->assertCount(0, Mail::mailer('array')->getSymfonyTransport()->messages());
        });

        $this->assertDatabaseCount('jobs', 1);
        $payload = DB::table('jobs')->sole()->payload;
        $this->assertStringNotContainsString('private-reporter@example.test', $payload);
        $this->assertStringNotContainsString($report->public_code, $payload);
        app('queue')->connection('database')->pop()->fire();
        $email = Mail::mailer('array')->getSymfonyTransport()->messages()->sole()->getOriginalMessage();
        $this->assertSame('private-reporter@example.test', $email->getTo()[0]->getAddress());
        $this->assertStringContainsString('Laporan diterima', $email->getTextBody());
        $this->assertDatabaseCount('jobs', 0);
    }

    public function test_rollback_discards_email_and_report_updates(): void
    {
        $report = Report::factory()->create(['reporter_email' => 'reporter@example.test']);

        try {
            DB::transaction(function () use ($report): void {
                $report->advanceStatus(User::factory()->create());
                throw new RuntimeException('Simulasi transaksi gagal.');
            });
        } catch (RuntimeException) {
            $this->assertSame(ReportStatus::Submitted, $report->fresh()->status);
        }

        $this->assertDatabaseCount('jobs', 0);
        $this->assertCount(0, Mail::mailer('array')->getSymfonyTransport()->messages());
    }

    public function test_portal_notification_arrives_immediately_while_email_waits_in_queue(): void
    {
        $operator = User::factory()->create();
        $report = Report::factory()->create();
        Event::fake([BroadcastNotificationCreated::class]);

        $operator->notify(new NewReportSubmitted($report));

        $this->assertSame(1, $operator->notifications()->count());
        $this->assertDatabaseCount('jobs', 1);
        $this->assertCount(0, Mail::mailer('array')->getSymfonyTransport()->messages());
        app('queue')->connection('database')->pop()->fire();
        $this->assertCount(1, Mail::mailer('array')->getSymfonyTransport()->messages());
        $this->assertDatabaseCount('jobs', 0);
    }

    #[TestWith(['admin', false])]
    #[TestWith(['police', true])]
    #[TestWith(['super_admin', true])]
    public function test_queued_operator_email_rechecks_current_access_before_delivery(string $role, bool $active): void
    {
        $operator = User::factory()->create();
        Event::fake([BroadcastNotificationCreated::class]);
        $operator->notify(new NewReportSubmitted(Report::factory()->create()));

        $operator->update(['role' => $role, 'is_active' => $active]);
        app('queue')->connection('database')->pop()->fire();

        $this->assertCount(0, Mail::mailer('array')->getSymfonyTransport()->messages());
        $this->assertDatabaseCount('jobs', 0);
    }

    public function test_temporary_mail_failure_retries_without_losing_the_report_or_portal_notification(): void
    {
        $this->freezeTime();
        $operator = User::factory()->create();
        $report = Report::factory()->create();
        Event::fake([BroadcastNotificationCreated::class]);
        $operator->notify(new NewReportSubmitted($report));
        $isMailAvailable = false;
        $listener = function () use (&$isMailAvailable): void {
            if (! $isMailAvailable) {
                throw new TransportException('Simulasi SMTP tidak tersedia.');
            }
        };
        Event::listen(MessageSending::class, $listener);

        app('queue.worker')->runNextJob('database', 'default', new WorkerOptions(sleep: 0));

        $job = DB::table('jobs')->sole();
        $this->assertSame(1, $job->attempts);
        $this->assertSame(now()->addSeconds(60)->getTimestamp(), $job->available_at);
        $this->assertNull($job->reserved_at);
        $this->assertDatabaseHas('reports', ['id' => $report->id]);
        $this->assertSame(1, $operator->notifications()->count());
        $this->assertCount(0, Mail::mailer('array')->getSymfonyTransport()->messages());
        $isMailAvailable = true;
        $this->travel(61)->seconds();
        app('queue.worker')->runNextJob('database', 'default', new WorkerOptions(sleep: 0));
        $this->assertCount(1, Mail::mailer('array')->getSymfonyTransport()->messages());
        $this->assertSame(1, $operator->notifications()->count());
        $this->assertDatabaseCount('jobs', 0);
    }

    public function test_confirmation_uses_the_recipient_address_captured_when_the_report_was_submitted(): void
    {
        $report = Report::factory()->create(['reporter_email' => 'original@example.test']);
        $report->notifyReporter(new ReportSubmitted($report->public_code));

        $report->update(['reporter_email' => 'different@example.test']);
        app('queue')->connection('database')->pop()->fire();

        $email = Mail::mailer('array')->getSymfonyTransport()->messages()->sole()->getOriginalMessage();
        $this->assertSame('original@example.test', $email->getTo()[0]->getAddress());
    }

    public function test_approval_email_is_skipped_if_the_request_was_already_reviewed_in_the_portal(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $operator = User::factory()->create();
        $report = Report::factory()->received()->create();
        Event::fake([BroadcastNotificationCreated::class, DatabaseNotificationsSent::class]);
        $request = ReportProgressRequest::submit($report, $operator, []);

        $request->reject($administrator, 'Dokumentasi perlu dilengkapi sebelum disetujui.');
        app('queue')->connection('database')->pop()->fire();

        $this->assertCount(0, Mail::mailer('array')->getSymfonyTransport()->messages());
        $this->assertDatabaseCount('jobs', 0);
    }

    public function test_persistent_mail_failure_stops_after_three_attempts_and_reports_the_failure(): void
    {
        $this->freezeTime();
        $operator = User::factory()->create();
        $report = Report::factory()->create();
        Event::fake([BroadcastNotificationCreated::class, JobFailed::class]);
        Event::listen(MessageSending::class, fn (): never => throw new TransportException('Simulasi SMTP terus gagal.'));
        $operator->notify(new NewReportSubmitted($report));

        $worker = app('queue.worker');
        $worker->runNextJob('database', 'default', new WorkerOptions(sleep: 0));
        $this->travel(61)->seconds();
        $worker->runNextJob('database', 'default', new WorkerOptions(sleep: 0));
        $retry = DB::table('jobs')->sole();
        $this->assertSame(2, $retry->attempts);
        $this->assertSame(now()->addSeconds(300)->getTimestamp(), $retry->available_at);
        $this->travel(301)->seconds();
        $worker->runNextJob('database', 'default', new WorkerOptions(sleep: 0));

        Event::assertDispatched(JobFailed::class, fn (JobFailed $event): bool => $event->exception instanceof TransportException);
        $this->assertCount(0, Mail::mailer('array')->getSymfonyTransport()->messages());
        $this->assertDatabaseCount('jobs', 0);
        $this->assertDatabaseHas('reports', ['id' => $report->id]);
        $this->assertSame(1, $operator->notifications()->count());
    }
}
