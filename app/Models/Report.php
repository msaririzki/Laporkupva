<?php

namespace App\Models;

use App\Enums\ReportStatus;
use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

#[Fillable([
    'public_code',
    'tracking_pin_hash',
    'status',
    'incident_type',
    'business_name',
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
class Report extends Model
{
    /** @use HasFactory<ReportFactory> */
    use HasFactory;

    /** @return HasMany<ReportEvidence, $this> */
    public function evidence(): HasMany
    {
        return $this->hasMany(ReportEvidence::class);
    }

    /** @return HasMany<ReportStatusHistory, $this> */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(ReportStatusHistory::class);
    }

    /** @return HasMany<AnonymousMessage, $this> */
    public function anonymousMessages(): HasMany
    {
        return $this->hasMany(AnonymousMessage::class);
    }

    public function advanceStatus(?User $user, ?string $publicNote = null): bool
    {
        $fromStatus = $this->status;
        $toStatus = $fromStatus->next();

        if (! $toStatus) {
            return false;
        }

        DB::transaction(function () use ($fromStatus, $publicNote, $toStatus, $user): void {
            $attributes = [
                'status' => $toStatus,
                'public_update' => $publicNote ?: $toStatus->description(),
            ];

            $timestampColumn = match ($toStatus) {
                ReportStatus::Received => 'received_at',
                ReportStatus::Coordination => 'coordinated_at',
                ReportStatus::FieldAction => 'field_action_at',
                ReportStatus::ResultReport => 'result_reported_at',
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
            ]);
        });

        return true;
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => ReportStatus::class,
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
