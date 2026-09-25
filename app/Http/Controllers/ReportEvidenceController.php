<?php

namespace App\Http\Controllers;

use App\Models\ReportEvidence;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportEvidenceController extends Controller
{
    /** @var array<string, string> */
    private const DOWNLOADABLE_MIME_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'application/pdf' => 'pdf',
    ];

    /** @var list<string> */
    private const PREVIEWABLE_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public function __invoke(ReportEvidence $reportEvidence): StreamedResponse
    {
        Gate::authorize('view', $reportEvidence);

        abort_unless(Storage::disk('local')->exists($reportEvidence->path), 404);
        abort_unless(array_key_exists($reportEvidence->mime_type, self::DOWNLOADABLE_MIME_TYPES), 404);

        return Storage::disk('local')->download(
            $reportEvidence->path,
            $this->safeDownloadName($reportEvidence),
            [
                'Cache-Control' => 'private, no-store',
                'Content-Type' => $reportEvidence->mime_type,
                'X-Content-Type-Options' => 'nosniff',
            ],
        );
    }

    public function preview(ReportEvidence $reportEvidence): StreamedResponse
    {
        Gate::authorize('view', $reportEvidence);

        abort_unless(Storage::disk('local')->exists($reportEvidence->path), 404);
        abort_unless(in_array($reportEvidence->mime_type, self::PREVIEWABLE_MIME_TYPES, true), 404);

        return Storage::disk('local')->response(
            $reportEvidence->path,
            $this->safeDownloadName($reportEvidence),
            [
                'Content-Type' => $reportEvidence->mime_type,
                'Content-Disposition' => 'inline; filename="'.$this->safeDownloadName($reportEvidence).'"',
                'Cache-Control' => 'private, no-store',
                'Content-Security-Policy' => "sandbox; default-src 'none'",
                'X-Content-Type-Options' => 'nosniff',
            ],
        );
    }

    private function safeDownloadName(ReportEvidence $reportEvidence): string
    {
        $extension = self::DOWNLOADABLE_MIME_TYPES[$reportEvidence->mime_type];
        $baseName = pathinfo(str_replace(["\0", "\r", "\n"], '', $reportEvidence->original_name), PATHINFO_FILENAME);
        $safeBaseName = Str::slug(Str::limit($baseName, 120, '')) ?: 'lampiran';

        return "{$safeBaseName}.{$extension}";
    }
}
