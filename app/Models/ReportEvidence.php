<?php

namespace App\Models;

use Database\Factories\ReportEvidenceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'report_id',
    'report_status_history_id',
    'uploaded_by_user_id',
    'source',
    'path',
    'original_name',
    'mime_type',
    'size',
    'caption',
])]
class ReportEvidence extends Model
{
    /** @use HasFactory<ReportEvidenceFactory> */
    use HasFactory;

    /** @return BelongsTo<Report, $this> */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /** @return BelongsTo<ReportStatusHistory, $this> */
    public function statusHistory(): BelongsTo
    {
        return $this->belongsTo(ReportStatusHistory::class, 'report_status_history_id');
    }

    /** @return BelongsTo<User, $this> */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
