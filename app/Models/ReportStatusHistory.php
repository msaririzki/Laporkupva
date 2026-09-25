<?php

namespace App\Models;

use App\Enums\ReportStatus;
use Database\Factories\ReportStatusHistoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['report_id', 'user_id', 'from_status', 'to_status', 'public_note', 'internal_note'])]
class ReportStatusHistory extends Model
{
    /** @use HasFactory<ReportStatusHistoryFactory> */
    use HasFactory;

    /** @return BelongsTo<Report, $this> */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<ReportEvidence, $this> */
    public function activityEvidence(): HasMany
    {
        return $this->hasMany(ReportEvidence::class)
            ->where('source', 'admin_activity');
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'from_status' => ReportStatus::class,
            'to_status' => ReportStatus::class,
        ];
    }
}
