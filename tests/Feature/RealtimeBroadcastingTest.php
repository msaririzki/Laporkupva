<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Events\ReportRealtimeUpdated;
use App\Models\AnonymousMessage;
use App\Models\Report;
use App\Models\User;
use App\Notifications\Admin\NewReporterMessage;
use App\Notifications\Admin\NewReportSubmitted;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RealtimeBroadcastingTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_public_realtime_channel_is_stable_and_does_not_expose_the_report_number(): void
    {
        $report = Report::factory()->create(['public_code' => 'LKP-AB12-CD34']);

        $this->assertSame($report->realtimeChannelName(), $report->fresh()->realtimeChannelName());
        $this->assertStringStartsWith('reports.', $report->realtimeChannelName());
        $this->assertStringNotContainsString($report->public_code, $report->realtimeChannelName());
        $this->assertSame(72, strlen($report->realtimeChannelName()));
    }

    public function test_status_changes_dispatch_a_realtime_report_update_after_persistence(): void
    {
        $report = Report::factory()->create();
        Event::fake([ReportRealtimeUpdated::class]);

        $report->update([
            'status' => ReportStatus::Received,
            'public_update' => 'Laporan telah diterima petugas.',
        ]);

        Event::assertDispatched(
            ReportRealtimeUpdated::class,
            fn (ReportRealtimeUpdated $event): bool => $event->report->is($report) && $event->kind === 'status',
        );
    }

    public function test_new_conversation_messages_dispatch_a_realtime_report_update(): void
    {
        $report = Report::factory()->create();
        Event::fake([ReportRealtimeUpdated::class]);

        AnonymousMessage::factory()->create(['report_id' => $report->getKey()]);

        Event::assertDispatched(
            ReportRealtimeUpdated::class,
            fn (ReportRealtimeUpdated $event): bool => $event->report->is($report) && $event->kind === 'message',
        );
    }

    public function test_admin_notifications_support_database_and_realtime_delivery(): void
    {
        $admin = User::factory()->create();
        $report = Report::factory()->create();
        $notifications = [
            new NewReportSubmitted($report),
            new NewReporterMessage($report, 'Informasi tambahan dari pelapor.'),
        ];

        foreach ($notifications as $notification) {
            $this->assertSame(['database', 'broadcast'], $notification->via($admin));
            $this->assertSame('filament', $notification->toBroadcast($admin)->data['format']);
        }
    }
}
