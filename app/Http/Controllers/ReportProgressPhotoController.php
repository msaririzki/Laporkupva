<?php

namespace App\Http\Controllers;

use App\Models\ReportProgressRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportProgressPhotoController extends Controller
{
    public function __invoke(ReportProgressRequest $reportProgressRequest, int $photo): StreamedResponse
    {
        Gate::authorize('view', $reportProgressRequest);

        $path = array_values($reportProgressRequest->activity_photos ?? [])[$photo] ?? null;
        abort_unless(is_string($path) && str_starts_with($path, "report-activity/{$reportProgressRequest->report_id}/")
            && ! str_contains($path, '..') && ! str_contains($path, '\\'), 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($path), 404);
        $mimeType = $disk->mimeType($path);
        abort_unless(in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true), 404);

        return $disk->response($path, "dokumentasi-{$reportProgressRequest->id}-{$photo}", [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'private, no-store',
            'Content-Security-Policy' => "sandbox; default-src 'none'",
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
