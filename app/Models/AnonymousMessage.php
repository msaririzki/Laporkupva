<?php

namespace App\Models;

use App\Events\ReportRealtimeUpdated;
use Database\Factories\AnonymousMessageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['report_id', 'user_id', 'sender_type', 'body', 'attachment_path', 'read_at'])]
class AnonymousMessage extends Model
{
    /** @use HasFactory<AnonymousMessageFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (AnonymousMessage $message): void {
            ReportRealtimeUpdated::dispatch($message->report, 'message');
        });
    }

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

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }
}
