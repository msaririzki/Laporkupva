<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Events\ReportRealtimeUpdated;
use App\Filament\Resources\ReportProgressRequests\ReportProgressRequestResource;
use App\Filament\Resources\Reports\Pages\ViewReport;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\ReportProgressRequest;
use App\Models\User;
use App\Notifications\Admin\ReportProgressApprovalRequested;
use App\Notifications\Admin\ReportProgressApprovalReviewed;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class ReportProgressApprovalTest extends TestCase
{
    use LazilyRefreshDatabase;

    #[TestWith(['received', 'coordination', 'coordinated_at'])]
    #[TestWith(['coordination', 'field_action', 'field_action_at'])]
    #[TestWith(['field_action', 'completed', 'completed_at'])]
    public function test_operator_submission_waits_for_administrator_before_publishing_progress(string $fromStatus, string $toStatus, string $timestamp): void
    {
        $operator = User::factory()->create();
        $administrator = User::factory()->superAdmin()->create();
        $police = User::factory()->police()->create();
        $inactiveAdministrator = User::factory()->superAdmin()->create(['is_active' => false]);
        $report = Report::factory()->create(['status' => $fromStatus, 'public_update' => 'Informasi yang telah disetujui.']);
        Notification::fake();
        Event::fake([ReportRealtimeUpdated::class]);
        $this->actingAs($operator);

        Livewire::test(ViewReport::class, ['record' => $report->id])
            ->assertActionVisible('advanceStatus')->callAction('advanceStatus', ['public_note' => 'Pembaruan kegiatan yang diajukan.'])
            ->assertHasNoFormErrors()->assertNotified();

        $request = ReportProgressRequest::query()->sole();
        $this->assertSame('pending', $request->status);
        $this->assertSame($toStatus, $request->to_status->value);
        $this->assertSame($fromStatus, $report->fresh()->status->value);
        $this->assertSame('Informasi yang telah disetujui.', $report->fresh()->public_update);
        $this->assertNull($report->fresh()->{$timestamp});
        $this->assertDatabaseCount('report_status_histories', 0);
        Notification::assertSentTo($administrator, ReportProgressApprovalRequested::class);
        Notification::assertNotSentTo($operator, ReportProgressApprovalRequested::class);
        Notification::assertNotSentTo($police, ReportProgressApprovalRequested::class);
        Notification::assertNotSentTo($inactiveAdministrator, ReportProgressApprovalRequested::class);
        Event::assertNotDispatched(ReportRealtimeUpdated::class);

        $this->get(ReportResource::getUrl('view', ['record' => $report]))->assertOk()->assertSee('Menunggu persetujuan Administrator');
        $this->post(route('reports.track.show'), ['tracking_code' => $report->public_code]);
        $this->get(route('reports.status', ['report' => $report->public_code]))->assertOk()->assertDontSee('Pembaruan kegiatan yang diajukan.');
        $this->actingAs($administrator);
        Livewire::test(ViewReport::class, ['record' => $report->id])
            ->assertActionHidden('advanceStatus')->assertActionVisible('approveProgress')->assertActionVisible('rejectProgress')
            ->callAction('approveProgress', ['public_note' => 'Pembaruan final setelah diperiksa Administrator.'])
            ->assertHasNoFormErrors()->assertNotified();

        $this->assertSame('approved', $request->fresh()->status);
        $this->assertSame($administrator->id, $request->fresh()->reviewed_by);
        $this->assertNotNull($request->fresh()->reviewed_at);
        $this->assertSame($toStatus, $report->fresh()->status->value);
        $this->assertNotNull($report->fresh()->{$timestamp});
        $this->assertDatabaseHas('report_status_histories', ['report_id' => $report->id, 'user_id' => $administrator->id, 'to_status' => $toStatus, 'public_note' => 'Pembaruan final setelah diperiksa Administrator.']);
        Notification::assertSentTo($operator, ReportProgressApprovalReviewed::class);
        Event::assertDispatched(ReportRealtimeUpdated::class);
        $this->get(route('reports.status', ['report' => $report->public_code]))->assertOk()->assertSee('Pembaruan final setelah diperiksa Administrator.');
    }

    public function test_rejection_preserves_public_status_and_allows_resubmission_with_feedback(): void
    {
        $operator = User::factory()->create();
        $administrator = User::factory()->superAdmin()->create();
        $report = Report::factory()->received()->create();
        $request = ReportProgressRequest::submit($report, $operator, ['public_note' => 'Koordinasi sedang dipersiapkan.']);
        Notification::fake();
        $this->actingAs($administrator);

        Livewire::test(ViewReport::class, ['record' => $report->id])
            ->callAction('rejectProgress', ['reason' => 'Lengkapi penjelasan hasil koordinasi sebelum diajukan kembali.'])
            ->assertHasNoFormErrors()->assertNotified();

        $this->assertSame('rejected', $request->fresh()->status);
        $this->assertSame(ReportStatus::Received, $report->fresh()->status);
        $this->assertDatabaseCount('report_status_histories', 0);
        Notification::assertSentTo($operator, ReportProgressApprovalReviewed::class, fn ($notification): bool => $notification->request->status === 'rejected');
        $this->actingAs($operator)->get(ReportResource::getUrl('view', ['record' => $report]))
            ->assertOk()->assertSee('Pengajuan progres ditolak')->assertSee('Lengkapi penjelasan hasil koordinasi');
        ReportProgressRequest::submit($report->fresh(), $operator, ['public_note' => 'Penjelasan koordinasi telah dilengkapi.']);
        $this->assertDatabaseCount('report_progress_requests', 2);
        $this->assertSame('pending', $report->fresh()->pendingProgressRequest->status);
    }

    public function test_pending_request_prevents_duplicate_submissions_and_repeated_approval(): void
    {
        $operator = User::factory()->create();
        $administrator = User::factory()->superAdmin()->create();
        $report = Report::factory()->received()->create();
        $request = ReportProgressRequest::submit($report, $operator, []);

        try {
            ReportProgressRequest::submit($report, $operator, []);
            $this->fail('Pengajuan ganda harus ditolak.');
        } catch (ValidationException) {
            $this->assertDatabaseCount('report_progress_requests', 1);
        }

        $request->approve($administrator);
        try {
            $request->approve($administrator);
            $this->fail('Persetujuan ulang harus ditolak.');
        } catch (ValidationException) {
            $this->assertDatabaseCount('report_status_histories', 1);
            $this->assertSame(ReportStatus::Coordination, $report->fresh()->status);
        }
    }

    #[TestWith(['admin'])]
    #[TestWith(['police'])]
    public function test_only_administrator_can_approve_or_reject(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);
        $request = ReportProgressRequest::factory()->create();

        try {
            $request->approve($user);
            $this->fail('Persetujuan tanpa izin harus ditolak.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
        try {
            $request->reject($user, 'Penolakan tanpa wewenang.');
            $this->fail('Penolakan tanpa izin harus ditolak.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
        $this->assertSame('pending', $request->fresh()->status);
        $this->assertDatabaseCount('report_status_histories', 0);
    }

    public function test_operator_cannot_bypass_approval_by_advancing_the_model_directly(): void
    {
        $report = Report::factory()->received()->create();

        $this->expectException(ValidationException::class);
        $report->advanceStatus(User::factory()->create());
    }

    public function test_activity_photos_are_attached_only_after_approval(): void
    {
        Storage::fake('local');
        $operator = User::factory()->create();
        $administrator = User::factory()->superAdmin()->create();
        $report = Report::factory()->received()->create();
        $path = "report-activity/{$report->id}/kegiatan.jpg";
        Storage::disk('local')->put($path, 'photo contents');

        $request = ReportProgressRequest::submit($report, $operator, ['activity_photos' => [$path], 'activity_photo_names' => [$path => 'Foto kegiatan.jpg']]);

        $this->assertDatabaseCount('report_evidence', 0);
        $request->approve($administrator);
        $evidence = ReportEvidence::query()->sole();
        $this->assertSame($path, $evidence->path);
        $this->assertSame($operator->id, $evidence->uploaded_by_user_id);
        $this->assertSame('coordination', $evidence->statusHistory->to_status->value);
    }

    public function test_approval_rolls_back_if_request_photos_are_missing(): void
    {
        Storage::fake('local');
        $operator = User::factory()->create();
        $administrator = User::factory()->superAdmin()->create();
        $report = Report::factory()->received()->create();
        $path = "report-activity/{$report->id}/photo.jpg";
        Storage::disk('local')->put($path, 'photo');
        $request = ReportProgressRequest::submit($report, $operator, ['activity_photos' => [$path]]);
        Storage::disk('local')->delete($path);
        Notification::fake();

        try {
            $request->approve($administrator);
            $this->fail('Persetujuan dengan dokumentasi hilang harus ditolak.');
        } catch (ValidationException) {
            $this->assertSame('pending', $request->fresh()->status);
            $this->assertSame(ReportStatus::Received, $report->fresh()->status);
            $this->assertDatabaseCount('report_status_histories', 0);
            Notification::assertNotSentTo($operator, ReportProgressApprovalReviewed::class);
        }
    }

    public function test_stale_request_cannot_be_approved_but_can_be_rejected(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $request = ReportProgressRequest::factory()->create();
        $request->report->update(['status' => ReportStatus::FieldAction]);

        try {
            $request->approve($administrator);
            $this->fail('Pengajuan dari tahap lama tidak boleh disetujui.');
        } catch (ValidationException) {
            $this->assertSame('pending', $request->fresh()->status);
        }

        $request->reject($administrator, 'Tahap sudah berubah, silakan ajukan pembaruan yang sesuai.');
        $this->assertSame('rejected', $request->fresh()->status);
        $this->assertDatabaseCount('report_status_histories', 0);
    }

    public function test_rejection_requires_a_reason_and_inactive_administrator_cannot_review(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $request = ReportProgressRequest::factory()->create();
        $this->actingAs($administrator);

        Livewire::test(ViewReport::class, ['record' => $request->report_id])
            ->callAction('rejectProgress', ['reason' => ''])->assertHasFormErrors(['reason']);
        $administrator->update(['is_active' => false]);
        try {
            $request->approve($administrator);
            $this->fail('Administrator nonaktif tidak boleh menyetujui.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
            $this->assertSame('pending', $request->fresh()->status);
        }
    }

    public function test_database_notifications_record_request_and_decision_with_review_link(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $operator = User::factory()->create();
        $report = Report::factory()->received()->create();

        $request = ReportProgressRequest::submit($report, $operator, []);

        $notification = $administrator->notifications()->sole();
        $this->assertSame('Pengajuan progres menunggu persetujuan', $notification->data['title']);
        $this->assertSame($request->id, $notification->data['progress_request_id']);
        $this->assertSame(ReportProgressRequestResource::getUrl('view', ['record' => $request], isAbsolute: false), $notification->data['actions'][0]['url']);
        $this->assertSame(['database', 'broadcast', 'mail'], (new ReportProgressApprovalRequested($request))->via($administrator));
        $this->assertSame('filament', (new ReportProgressApprovalRequested($request))->toBroadcast($administrator)->data['format']);
        $request->reject($administrator, 'Lengkapi dokumentasi koordinasi yang dilakukan oleh petugas.');
        $this->assertNotNull($notification->fresh()->read_at);
        $feedback = $operator->notifications()->sole();
        $this->assertSame('Pengajuan progres ditolak', $feedback->data['title']);
        $this->assertStringContainsString('Lengkapi dokumentasi', $feedback->data['body']);
    }

    public function test_old_approval_modal_cannot_approve_a_replacement_request(): void
    {
        $operator = User::factory()->create();
        $administrator = User::factory()->superAdmin()->create();
        $report = Report::factory()->received()->create();
        $originalRequest = ReportProgressRequest::submit($report, $operator, ['public_note' => 'Pengajuan pertama.']);
        $this->actingAs($administrator);
        $modal = Livewire::test(ViewReport::class, ['record' => $report->id])->mountAction('approveProgress');
        $originalRequest->reject($administrator, 'Lengkapi informasi koordinasi terlebih dahulu.');
        $replacementRequest = ReportProgressRequest::submit($report, $operator, ['public_note' => 'Pengajuan kedua yang telah diperbaiki.']);

        $modal->callMountedAction();

        $this->assertSame('pending', $replacementRequest->fresh()->status);
        $this->assertSame('Pengajuan kedua yang telah diperbaiki.', $replacementRequest->fresh()->public_note);
        $this->assertSame(ReportStatus::Received, $report->fresh()->status);
        $this->assertDatabaseCount('report_status_histories', 0);
    }
}
