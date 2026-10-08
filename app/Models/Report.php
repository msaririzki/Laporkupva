<?php

namespace App\Models;

use App\Enums\ReportStatus;
use App\Events\ReportRealtimeUpdated;
use App\Notifications\MailNotification;
use App\Notifications\ReportStatusUpdated;
use Database\Factories\ReportFactory;
use DomainException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

#[Fillable([
    'public_code',
    'tracking_pin_hash',
    'status',
    'incident_type',
    'business_name',
    'reporter_name',
    'reporter_email',
    'reporter_phone',
    'incident_date',
    'incident_time',
    'description',
    'is_ongoing',
    'province',
    'regency',
    'district',
    'village',
    'address',
    'latitude',
    'longitude',
    'location_accuracy',
    'public_update',
    'internal_notes',
    'received_at',
    'coordinated_at',
    'field_action_at',
    'result_reported_at',
    'completed_at',
])]
#[Hidden(['reporter_name', 'reporter_email', 'reporter_phone'])]
class Report extends Model
{
    /** @use HasFactory<ReportFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::updated(function (Report $report): void {
            if ($report->wasChanged(['status', 'public_update'])) {
                ReportRealtimeUpdated::dispatch($report, 'status');
                $report->notifyReporter(new ReportStatusUpdated($report->public_code, $report->status));
            }
        });
    }

    public function notifyReporter(MailNotification $notification): void
    {
        if (filled($this->reporter_email) && filter_var($this->reporter_email, FILTER_VALIDATE_EMAIL) !== false) {
            Notification::route('mail', $this->reporter_email)->notify($notification);
        }
    }

    /** @return HasMany<ReportEvidence, $this> */
    public function evidence(): HasMany
    {
        return $this->hasMany(ReportEvidence::class);
    }

    /** @return HasMany<ReportEvidence, $this> */
    public function submissionEvidence(): HasMany
    {
        return $this->hasMany(ReportEvidence::class)
            ->where('source', 'reporter_submission');
    }

    /** @return HasMany<ReportEvidence, $this> */
    public function activityEvidence(): HasMany
    {
        return $this->hasMany(ReportEvidence::class)
            ->where('source', 'admin_activity')
            ->latest();
    }

    /** @return HasMany<ReportStatusHistory, $this> */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(ReportStatusHistory::class)->oldest();
    }

    /** @return HasMany<AnonymousMessage, $this> */
    public function anonymousMessages(): HasMany
    {
        return $this->hasMany(AnonymousMessage::class)->oldest();
    }

    public function realtimeChannelName(): string
    {
        return 'reports.'.hash_hmac('sha256', (string) $this->getKey(), (string) config('app.key'));
    }

    /** @return HasMany<ReportProgressRequest, $this> */
    public function progressRequests(): HasMany
    {
        return $this->hasMany(ReportProgressRequest::class);
    }

    /** @return HasOne<ReportProgressRequest, $this> */
    public function pendingProgressRequest(): HasOne
    {
        return $this->hasOne(ReportProgressRequest::class)->where('status', 'pending')->latestOfMany();
    }

    /** @return HasOne<ReportProgressRequest, $this> */
    public function latestProgressRequest(): HasOne
    {
        return $this->hasOne(ReportProgressRequest::class)->latestOfMany();
    }

    public function advanceStatus(?User $user, ?string $publicNote = null, ?string $internalNote = null): bool
    {
        if ($user !== null) {
            Gate::forUser($user)->authorize('update', $this);
        }

        $fromStatus = $this->status;
        $toStatus = $fromStatus->next();

        if (! $toStatus) {
            return false;
        }

        if ($toStatus->requiresApproval() && ($user?->isSuperAdmin() !== true || ! $user->is_active)) {
            throw ValidationException::withMessages(['public_note' => 'Tahap ini memerlukan persetujuan Administrator. Ajukan pembaruan progres terlebih dahulu.']);
        }

        DB::transaction(function () use ($fromStatus, $internalNote, $publicNote, $toStatus, $user): void {
            $lockedReport = self::query()->lockForUpdate()->findOrFail($this->id);

            if ($lockedReport->status !== $fromStatus || $lockedReport->pendingProgressRequest()->exists()) {
                throw ValidationException::withMessages(['public_note' => 'Tahap laporan berubah atau sedang menunggu persetujuan Administrator.']);
            }

            $attributes = [
                'status' => $toStatus,
                'public_update' => filled($publicNote) ? trim($publicNote) : $toStatus->publicMessage($this->public_code),
            ];

            $timestampColumn = match ($toStatus) {
                ReportStatus::Received => 'received_at',
                ReportStatus::Coordination => 'coordinated_at',
                ReportStatus::FieldAction => 'field_action_at',
                ReportStatus::Completed => 'completed_at',
                ReportStatus::Submitted => null,
            };

            if ($timestampColumn) {
                $attributes[$timestampColumn] = now();
            }

            $this->update($attributes);
            $this->statusHistories()->create([
                'user_id' => $user?->getKey(),
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'public_note' => $attributes['public_update'],
                'internal_note' => filled($internalNote) ? trim($internalNote) : null,
            ]);
        });

        return true;
    }

    /**
     * @param  array<array-key, string>  $paths
     * @param  array<string, string>  $originalNames
     */
    public function storeActivityEvidence(
        ReportStatusHistory $history,
        User $user,
        array $paths,
        array $originalNames,
        ?string $caption,
    ): void {
        $this->validateActivityPhotoPaths($paths);
        $disk = Storage::disk('local');
        foreach ($paths as $path) {
            $this->evidence()->create([
                'report_status_history_id' => $history->getKey(),
                'uploaded_by_user_id' => $user->getKey(),
                'source' => 'admin_activity',
                'path' => $path,
                'original_name' => $originalNames[$path] ?? basename($path),
                'mime_type' => $disk->mimeType($path) ?: 'application/octet-stream',
                'size' => $disk->size($path),
                'caption' => filled($caption) ? trim($caption) : null,
            ]);
        }
    }

    /** @param array<array-key, string> $paths */
    public function validateActivityPhotoPaths(array $paths): void
    {
        foreach ($paths as $path) {
            if (! is_string($path) || ! str_starts_with($path, "report-activity/{$this->getKey()}/") || ! Storage::disk('local')->exists($path)) {
                throw ValidationException::withMessages(['activity_photos' => 'Lokasi foto kegiatan tidak valid.']);
            }
        }
    }

    public function correctStatus(User $user, ReportStatus $toStatus, string $reason, ?string $publicNote = null): void
    {
        $statuses = ReportStatus::cases();
        $fromPosition = array_search($this->status, $statuses, true);
        $toPosition = array_search($toStatus, $statuses, true);

        if (! $user->is_active || ! $user->isSuperAdmin() || $fromPosition === false || $toPosition === false || $toPosition >= $fromPosition) {
            throw new DomainException('Koreksi status tidak diizinkan.');
        }

        $fromStatus = $this->status;

        if ($this->pendingProgressRequest()->exists()) {
            throw ValidationException::withMessages(['status' => 'Selesaikan pengajuan progres yang menunggu persetujuan sebelum mengoreksi status.']);
        }

        DB::transaction(function () use ($fromStatus, $publicNote, $reason, $statuses, $toPosition, $toStatus, $user): void {
            $lockedReport = self::query()->lockForUpdate()->findOrFail($this->id);

            if ($lockedReport->status !== $fromStatus || $lockedReport->pendingProgressRequest()->exists()) {
                throw ValidationException::withMessages(['status' => 'Tahap laporan berubah atau sedang menunggu persetujuan Administrator.']);
            }

            $attributes = [
                'status' => $toStatus,
                'public_update' => $publicNote ?: "Status penanganan dikoreksi menjadi {$toStatus->label()}.",
            ];
            $timestampColumns = [
                ReportStatus::Received->value => 'received_at',
                ReportStatus::Coordination->value => 'coordinated_at',
                ReportStatus::FieldAction->value => 'field_action_at',
                ReportStatus::Completed->value => 'completed_at',
            ];

            foreach ($statuses as $position => $status) {
                if ($position > $toPosition && isset($timestampColumns[$status->value])) {
                    $attributes[$timestampColumns[$status->value]] = null;
                }
            }

            $this->update($attributes);
            $this->statusHistories()->create([
                'user_id' => $user->getKey(),
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'public_note' => $attributes['public_update'],
                'internal_note' => 'Koreksi status: '.trim($reason),
            ]);
        });
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => ReportStatus::class,
            'reporter_phone' => 'encrypted',
            'reporter_name' => 'encrypted',
            'reporter_email' => 'encrypted',
            'incident_date' => 'date',
            'incident_time' => 'datetime:H:i',
            'is_ongoing' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'location_accuracy' => 'decimal:2',
            'received_at' => 'datetime',
            'coordinated_at' => 'datetime',
            'field_action_at' => 'datetime',
            'result_reported_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}
