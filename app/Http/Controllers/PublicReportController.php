<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Http\Requests\StorePublicReportRequest;
use App\Models\Report;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicReportController extends Controller
{
    public function create(): View
    {
        return view('reports.create');
    }

    public function store(StorePublicReportRequest $request): RedirectResponse
    {
        $pin = (string) random_int(100000, 999999);
        $validated = $request->safe()->except(['evidence', 'good_faith']);

        $report = DB::transaction(function () use ($request, $validated, $pin): Report {
            $report = Report::query()->create([
                ...$validated,
                'public_code' => $this->generatePublicCode(),
                'tracking_pin_hash' => Hash::make($pin),
                'status' => ReportStatus::Submitted,
                'province' => 'Nusa Tenggara Barat',
            ]);

            $report->statusHistories()->create([
                'from_status' => null,
                'to_status' => ReportStatus::Submitted,
                'public_note' => ReportStatus::Submitted->description(),
            ]);

            foreach ($request->file('evidence', []) as $file) {
                $path = $file->store("report-evidence/{$report->id}", 'local');

                $report->evidence()->create([
                    'path' => $path,
                    'original_name' => $this->safeOriginalName($file),
                    'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
                    'size' => $file->getSize(),
                ]);
            }

            return $report;
        });

        return to_route('reports.success')->with('submitted_report', [
            'code' => $report->public_code,
            'pin' => $pin,
            'submitted_at' => $report->created_at->toIso8601String(),
        ]);
    }

    public function success(): View|RedirectResponse
    {
        $submittedReport = session('submitted_report');

        if (! is_array($submittedReport)) {
            return to_route('reports.create');
        }

        $trackingUrl = route('reports.track', ['code' => $submittedReport['code']]);
        $trackingQrCode = (new QRCode(new QROptions([
            'eccLevel' => EccLevel::M,
            'outputBase64' => true,
        ])))->render($trackingUrl);

        return view('reports.success', [
            'submittedReport' => $submittedReport,
            'trackingQrCode' => $trackingQrCode,
            'trackingUrl' => $trackingUrl,
        ]);
    }

    private function generatePublicCode(): string
    {
        do {
            $code = 'LKP-'.Str::upper(Str::random(4)).'-'.Str::upper(Str::random(4));
        } while (Report::query()->where('public_code', $code)->exists());

        return $code;
    }

    private function safeOriginalName(UploadedFile $file): string
    {
        $extension = match ($file->getMimeType()) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'application/pdf' => 'pdf',
            default => 'bin',
        };
        $baseName = pathinfo(str_replace(["\0", "\r", "\n"], '', $file->getClientOriginalName()), PATHINFO_FILENAME);
        $safeBaseName = Str::slug(Str::limit($baseName, 120, '')) ?: 'lampiran';

        return "{$safeBaseName}.{$extension}";
    }
}
