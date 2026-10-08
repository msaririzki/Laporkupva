<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Events\ReportRealtimeUpdated;
use App\Filament\Resources\ReportProgressRequests\Pages\ListReportProgressRequests;
use App\Filament\Resources\ReportProgressRequests\Pages\ViewReportProgressRequest;
use App\Filament\Resources\ReportProgressRequests\ReportProgressRequestResource;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use App\Models\ReportProgressRequest;
use App\Models\User;
use App\Notifications\Admin\ReportProgressApprovalReviewed;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class ReportProgressReviewPageTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_queue_opens_on_pending_requests_and_separates_reviewed_history(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $pending = ReportProgressRequest::factory()->create();
        $approved = ReportProgressRequest::factory()->create(['status' => 'approved']);
        $rejected = ReportProgressRequest::factory()->create(['status' => 'rejected']);
        $this->actingAs($administrator);

        Livewire::test(ListReportProgressRequests::class)
            ->assertCanSeeTableRecords([$pending])->assertCanNotSeeTableRecords([$approved, $rejected])
            ->set('activeTab', 'approved')->assertCanSeeTableRecords([$approved])->assertCanNotSeeTableRecords([$pending, $rejected])
            ->set('activeTab', 'rejected')->assertCanSeeTableRecords([$rejected])->assertCanNotSeeTableRecords([$pending, $approved]);

        $this->assertSame('1', ReportProgressRequestResource::getNavigationBadge());
    }

    public function test_review_page_prioritizes_current_operator_note_and_documentation_with_full_report_link(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $report = Report::factory()->received()->create(['reporter_name' => 'Identitas pelapor rahasia']);
        ReportProgressRequest::factory()->for($report)->create(['status' => 'rejected', 'internal_note' => 'Catatan pengajuan sebelumnya.']);
        $request = ReportProgressRequest::factory()->for($report)->create([
            'internal_note' => 'Koordinasi dengan APH telah dilaksanakan dan dituangkan dalam dokumentasi.',
            'activity_photos' => ["report-activity/{$report->id}/koordinasi.png"],
            'activity_photo_names' => ["report-activity/{$report->id}/koordinasi.png" => 'Dokumentasi koordinasi.png'],
        ]);

        $this->actingAs($administrator)->get(ReportProgressRequestResource::getUrl('view', ['record' => $request]))
            ->assertSeeInOrder(['Catatan Operator', $request->internal_note, 'Foto dokumentasi pengajuan', 'Dokumentasi koordinasi.png', 'Informasi untuk pelapor'])
            ->assertSee(route('admin.report-progress-photos.preview', ['reportProgressRequest' => $request, 'photo' => 0]), false)
            ->assertSee(ReportResource::getUrl('view', ['record' => $report]), false)
            ->assertDontSee('Catatan pengajuan sebelumnya.')->assertDontSee('Identitas pelapor rahasia');
    }

    #[TestWith(['super_admin', true, true])]
    #[TestWith(['super_admin', false, false])]
    #[TestWith(['admin', true, false])]
    #[TestWith(['police', true, false])]
    public function test_review_permissions_are_limited_to_active_administrators(string $role, bool $active, bool $allowed): void
    {
        $user = User::factory()->create(['role' => $role, 'is_active' => $active]);
        $request = ReportProgressRequest::factory()->create();

        $this->assertSame($allowed, Gate::forUser($user)->allows('viewAny', ReportProgressRequest::class));
        $this->assertSame($allowed, Gate::forUser($user)->allows('view', $request));
        $this->assertSame($allowed, Gate::forUser($user)->allows('review', $request));
        $this->assertFalse(Gate::forUser($user)->allows('create', ReportProgressRequest::class));
        $this->assertFalse(Gate::forUser($user)->allows('update', $request));
        $this->assertFalse(Gate::forUser($user)->allows('delete', $request));
    }

    #[TestWith(['admin', true])]
    #[TestWith(['police', true])]
    #[TestWith(['super_admin', false])]
    public function test_unprivileged_accounts_cannot_open_approval_queue_or_private_photos(string $role, bool $active): void
    {
        $user = User::factory()->create(['role' => $role, 'is_active' => $active]);
        $request = ReportProgressRequest::factory()->create();
        $this->actingAs($user);

        $this->get(ReportProgressRequestResource::getUrl())->assertForbidden();
        $this->get(ReportProgressRequestResource::getUrl('view', ['record' => $request]))->assertForbidden();
        $this->get(route('admin.report-progress-photos.preview', ['reportProgressRequest' => $request, 'photo' => 0]))->assertForbidden();
        $this->assertFalse(ReportProgressRequestResource::canViewAny());
    }

    public function test_guest_must_sign_in_to_open_review_and_documentation(): void
    {
        $request = ReportProgressRequest::factory()->create();

        $this->get(ReportProgressRequestResource::getUrl('view', ['record' => $request]))->assertRedirect();
        $this->get(route('admin.report-progress-photos.preview', ['reportProgressRequest' => $request, 'photo' => 0]))->assertRedirect();
    }

    public function test_administrator_can_approve_from_focused_page_without_changing_operator_note(): void
    {
        Storage::fake('local');
        $administrator = User::factory()->superAdmin()->create();
        $request = ReportProgressRequest::factory()->create(['internal_note' => 'Koordinasi telah dilakukan dengan petugas APH.']);
        $path = UploadedFile::fake()->image('koordinasi.png')->store("report-activity/{$request->report_id}", 'local');
        $request->update(['activity_photos' => [$path], 'activity_photo_names' => [$path => 'koordinasi.png']]);
        Notification::fake();
        Event::fake([ReportRealtimeUpdated::class]);
        $this->actingAs($administrator);

        Livewire::test(ViewReportProgressRequest::class, ['record' => $request->id])
            ->callAction('approveProgress', ['public_note' => 'Koordinasi penanganan laporan telah disetujui oleh Administrator.'])
            ->assertHasNoFormErrors()->assertNotified()
            ->assertActionHidden('approveProgress')->assertActionHidden('rejectProgress');

        $this->assertSame('approved', $request->fresh()->status);
        $this->assertSame(ReportStatus::Coordination, $request->report->fresh()->status);
        $this->assertDatabaseHas('report_status_histories', ['report_id' => $request->report_id, 'internal_note' => 'Koordinasi telah dilakukan dengan petugas APH.']);
        $this->assertDatabaseHas('report_evidence', ['report_id' => $request->report_id, 'path' => $path, 'uploaded_by_user_id' => $request->requested_by]);
        Notification::assertSentTo($request->requester, ReportProgressApprovalReviewed::class);
        Event::assertDispatched(ReportRealtimeUpdated::class);
    }

    public function test_administrator_can_reject_with_actionable_reason_from_focused_page(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $request = ReportProgressRequest::factory()->create();
        Notification::fake();
        Event::fake([ReportRealtimeUpdated::class]);
        $this->actingAs($administrator);

        Livewire::test(ViewReportProgressRequest::class, ['record' => $request->id])
            ->callAction('rejectProgress', ['reason' => 'Tambahkan foto koordinasi dan jelaskan hasil pertemuan dengan APH.'])
            ->assertHasNoFormErrors()->assertNotified()->assertSee('Tambahkan foto koordinasi')
            ->assertActionHidden('approveProgress')->assertActionHidden('rejectProgress');

        $this->assertSame('rejected', $request->fresh()->status);
        $this->assertSame(ReportStatus::Received, $request->report->fresh()->status);
        $this->assertDatabaseCount('report_status_histories', 0);
        Notification::assertSentTo($request->requester, ReportProgressApprovalReviewed::class,
            fn ($notification): bool => $notification->request->rejection_reason === 'Tambahkan foto koordinasi dan jelaskan hasil pertemuan dengan APH.');
        Event::assertNotDispatched(ReportRealtimeUpdated::class);
    }

    #[TestWith([''])]
    #[TestWith(['pendek'])]
    #[TestWith(['<script>alert(1)</script>'])]
    public function test_rejection_from_focused_page_requires_clear_plain_text_reason(string $reason): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $request = ReportProgressRequest::factory()->create();
        Notification::fake();
        $this->actingAs($administrator);

        Livewire::test(ViewReportProgressRequest::class, ['record' => $request->id])
            ->callAction('rejectProgress', ['reason' => $reason])->assertHasFormErrors(['reason']);

        $this->assertSame('pending', $request->fresh()->status);
        Notification::assertNothingSent();
    }

    public function test_documentation_is_served_privately_and_only_from_requested_photo_index(): void
    {
        Storage::fake('local');
        $administrator = User::factory()->superAdmin()->create();
        $request = ReportProgressRequest::factory()->create();
        $path = UploadedFile::fake()->image('koordinasi.png')->store("report-activity/{$request->report_id}", 'local');
        $request->update(['activity_photos' => [$path]]);
        $this->actingAs($administrator);

        $this->get(route('admin.report-progress-photos.preview', ['reportProgressRequest' => $request, 'photo' => 0]))
            ->assertOk()->assertHeader('content-type', 'image/png')->assertHeader('cache-control', 'no-store, private')
            ->assertHeader('content-security-policy', "sandbox; default-src 'none'")->assertHeader('x-content-type-options', 'nosniff');
        $this->get(route('admin.report-progress-photos.preview', ['reportProgressRequest' => $request, 'photo' => 1]))->assertNotFound();
    }

    #[TestWith(['report-activity/other-report/photo.png'])]
    #[TestWith(['report-activity/{report}/../photo.png'])]
    #[TestWith(['report-activity/{report}/missing.png'])]
    public function test_unrelated_unsafe_and_missing_documentation_paths_are_not_served(string $path): void
    {
        Storage::fake('local');
        $administrator = User::factory()->superAdmin()->create();
        $request = ReportProgressRequest::factory()->create();
        $request->update(['activity_photos' => [str_replace('{report}', (string) $request->report_id, $path)]]);

        $this->actingAs($administrator)->get(route('admin.report-progress-photos.preview', ['reportProgressRequest' => $request, 'photo' => 0]))->assertNotFound();
    }

    public function test_active_content_cannot_be_displayed_as_a_documentation_image(): void
    {
        Storage::fake('local');
        $administrator = User::factory()->superAdmin()->create();
        $request = ReportProgressRequest::factory()->create();
        $path = "report-activity/{$request->report_id}/photo.png";
        Storage::disk('local')->put($path, '<html><script>alert(1)</script></html>');
        $request->update(['activity_photos' => [$path]]);

        $this->actingAs($administrator)->get(route('admin.report-progress-photos.preview', ['reportProgressRequest' => $request, 'photo' => 0]))->assertNotFound();
    }

    public function test_notes_and_photo_names_are_escaped_on_review_page(): void
    {
        $administrator = User::factory()->superAdmin()->create();
        $request = ReportProgressRequest::factory()->create([
            'internal_note' => '<script>alert("note")</script>',
            'activity_photos' => ['report-activity/photo.png'],
            'activity_photo_names' => ['report-activity/photo.png' => '<script>alert("photo")</script>'],
        ]);

        $this->actingAs($administrator)->get(ReportProgressRequestResource::getUrl('view', ['record' => $request]))
            ->assertSee('<script>')->assertSee('alert(')->assertSee('<script>alert("photo")</script>')
            ->assertDontSee('<script>alert("note")</script>', false)->assertDontSee('<script>alert("photo")</script>', false);
    }
}
