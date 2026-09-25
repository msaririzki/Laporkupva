<?php

namespace App\Http\Controllers;

use App\Models\ReportEvidence;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportEvidenceController extends Controller
{
    public function __invoke(ReportEvidence $reportEvidence): StreamedResponse
    {
        Gate::authorize('view', $reportEvidence);

        abort_unless(Storage::disk('local')->exists($reportEvidence->path), 404);

        return Storage::disk('local')->download(
            $reportEvidence->path,
            basename($reportEvidence->original_name),
            ['Content-Type' => $reportEvidence->mime_type],
        );
    }

    public function preview(ReportEvidence $reportEvidence): StreamedResponse
    {
        Gate::authorize('view', $reportEvidence);

        abort_unless(Storage::disk('local')->exists($reportEvidence->path), 404);

        return Storage::disk('local')->response(
            $reportEvidence->path,
            basename($reportEvidence->original_name),
            [
                'Content-Type' => $reportEvidence->mime_type,
                'Content-Disposition' => 'inline; filename="'.basename($reportEvidence->original_name).'"',
                'Cache-Control' => 'private, max-age=300',
            ],
        );
    }
}
