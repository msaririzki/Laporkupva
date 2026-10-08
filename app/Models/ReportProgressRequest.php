<?php

namespace App\Models;

use App\Enums\ReportStatus;
use App\Enums\UserRole;
use App\Notifications\Admin\ReportProgressApprovalRequested;
use App\Notifications\Admin\ReportProgressApprovalReviewed;
use Database\Factories\ReportProgressRequestFactory;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

#[Fillable(['report_id', 'requested_by', 'reviewed_by', 'from_status', 'to_status', 'status', 'public_note', 'internal_note', 'activity_photos', 'activity_photo_names', 'rejection_reason', 'reviewed_at'])]
class ReportProgressRequest extends Model
{
    /** @use HasFactory<ReportProgressRequestFactory> */
    use HasFactory;

    /** @return BelongsTo<Report, $this> */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /** @return BelongsTo<User, $this> */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /** @return BelongsTo<User, $this> */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /** @param array{public_note?: string|null, internal_note?: string|null, activity_photos?: array<array-key, string>, activity_photo_names?: array<string, string>} $data */
    public static function submit(Report $report, User $user, array $data): self
    {
        Gate::forUser($user)->authorize('update', $report);

        return DB::transaction(function () use ($report, $user, $data): self {
            $lockedReport = Report::query()->lockForUpdate()->findOrFail($report->id);
            $nextStatus = $lockedReport->status->next();

            if ($lockedReport->status !== $report->status || ! $nextStatus?->requiresApproval() || $lockedReport->pendingProgressRequest()->exists()) {
                throw ValidationException::withMessages(['public_note' => 'Tahap laporan berubah atau sudah ada pengajuan yang menunggu persetujuan. Muat ulang laporan sebelum melanjutkan.']);
            }

            $lockedReport->validateActivityPhotoPaths($data['activity_photos'] ?? []);
            $request = self::query()->create([
                'report_id' => $lockedReport->id,
                'requested_by' => $user->id,
                'from_status' => $lockedReport->status,
                'to_status' => $nextStatus,
                'status' => 'pending',
                'public_note' => filled($data['public_note'] ?? null) ? trim($data['public_note']) : $nextStatus->publicMessage($lockedReport->public_code),
                'internal_note' => $data['internal_note'] ?? null,
                'activity_photos' => $data['activity_photos'] ?? [],
                'activity_photo_names' => $data['activity_photo_names'] ?? [],
            ]);

            DB::afterCommit(function () use ($request): void {
                $administrators = User::query()->where('role', UserRole::SuperAdmin)->where('is_active', true)->get();
                Notification::send($administrators, new ReportProgressApprovalRequested($request));
                $administrators->each(fn (User $administrator) => DatabaseNotificationsSent::dispatch($administrator));
            });

            return $request;
        });
    }

    public function approve(User $user, ?string $publicNote = null, ?string $internalNote = null): void
    {
        $this->review($user, true, $publicNote, $internalNote);
    }

    public function reject(User $user, string $reason): void
    {
        $this->review($user, false, reason: $reason);
    }

    private function review(User $user, bool $approved, ?string $publicNote = null, ?string $internalNote = null, ?string $reason = null): void
    {
        abort_unless($user->is_active && $user->isSuperAdmin(), 403);

        DB::transaction(function () use ($user, $approved, $publicNote, $internalNote, $reason): void {
            $report = Report::query()->lockForUpdate()->findOrFail($this->report_id);
            $request = self::query()->lockForUpdate()->findOrFail($this->id);

            if ($request->status !== 'pending' || ($approved && ($report->status !== $request->from_status || $report->status->next() !== $request->to_status))) {
                throw ValidationException::withMessages([$approved ? 'public_note' : 'reason' => 'Pengajuan sudah diproses atau tahap laporan telah berubah. Muat ulang laporan untuk melihat status terbaru.']);
            }

            if (! $approved && mb_strlen(trim($reason ?? '')) < 10) {
                throw ValidationException::withMessages(['reason' => 'Jelaskan alasan penolakan minimal 10 karakter.']);
            }

            $request->update([
                'status' => $approved ? 'approved' : 'rejected',
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
                'public_note' => $publicNote ?? $request->public_note,
                'internal_note' => $internalNote ?? $request->internal_note,
                'rejection_reason' => $approved ? null : trim($reason),
            ]);

            if ($approved) {
                $report->advanceStatus($user, $request->public_note, $request->internal_note);
                $history = $report->statusHistories()->reorder('id', 'desc')->firstOrFail();
                $report->storeActivityEvidence($history, $request->requester ?? $user, $request->activity_photos ?? [], $request->activity_photo_names ?? [], $request->internal_note);
            }

            $this->setRawAttributes($request->getAttributes(), true);
            DB::afterCommit(function () use ($request): void {
                if ($request->requester?->is_active) {
                    $request->requester->notify(new ReportProgressApprovalReviewed($request));
                    DatabaseNotificationsSent::dispatch($request->requester);
                }

                DB::table('notifications')->where('type', ReportProgressApprovalRequested::class)
                    ->where('data->progress_request_id', $request->id)->whereNull('read_at')->update(['read_at' => now()]);
            });
        });
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['from_status' => ReportStatus::class, 'to_status' => ReportStatus::class, 'activity_photos' => 'array', 'activity_photo_names' => 'array', 'reviewed_at' => 'datetime'];
    }
}
