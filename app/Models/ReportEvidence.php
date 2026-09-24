<?php

namespace App\Models;

use Database\Factories\ReportEvidenceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['report_id', 'path', 'original_name', 'mime_type', 'size'])]
class ReportEvidence extends Model
{
    /** @use HasFactory<ReportEvidenceFactory> */
    use HasFactory;

    /** @return BelongsTo<Report, $this> */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
