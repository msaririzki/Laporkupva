<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Enums\UserRole;
use App\Http\Requests\StorePublicReportRequest;
use App\Http\Requests\VerifyPublicReporterRequest;
use App\Models\Report;
use App\Models\User;
use App\Notifications\Admin\NewReportSubmitted;
use App\Notifications\ReportSubmitted;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicReportController extends Controller
{
    public function create(): Response
    {
        return response()->view('reports.create', headers: ['Cache-Control' => 'private, no-store']);
    }

    public function verify(VerifyPublicReporterRequest $request): JsonResponse
    {
        $verificationId = (string) Str::uuid();
        $expiresAt = now()->addMinutes(45)->getTimestamp();
        $verifications = array_filter(
            $request->session()->get('report_verifications', []),
            fn (array $verification): bool => $verification['expires_at'] > now()->getTimestamp(),
        );
        $verifications = array_slice($verifications, -4, preserve_keys: true);
        $verifications[$verificationId] = [
            'identity' => $request->identityFingerprint(),
            'expires_at' => $expiresAt,
        ];
        $request->session()->put('report_verifications', $verifications);

        return response()->json([
            'verification_id' => $verificationId,
            'expires_at' => $expiresAt,
        ], headers: ['Cache-Control' => 'private, no-store']);
    }

    public function store(StorePublicReportRequest $request): RedirectResponse
    {
        $trackingSecret = Str::random(32);
        $validated = $request->safe()->except(['evidence', 'good_faith', 'verification_id', 'location_confirmed']);

        $report = DB::transaction(function () use ($request, $validated, $trackingSecret): Report {
            $report = Report::query()->create([
                ...$validated,
                'public_code' => $this->generatePublicCode(),
                'tracking_pin_hash' => Hash::make($trackingSecret),
                'status' => ReportStatus::Submitted,
                'province' => 'Nusa Tenggara Barat',
            ]);

            $report->statusHistories()->create([
                'from_status' => null,
                'to_status' => ReportStatus::Submitted,
                'public_note' => ReportStatus::Submitted->publicMessage($report->public_code),
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

        $request->session()->forget('report_verifications.'.$request->input('verification_id'));

        $admins = User::query()
            ->where('is_active', true)
            ->whereIn('role', [UserRole::Admin->value, UserRole::SuperAdmin->value])
            ->get();

        Notification::send($admins, new NewReportSubmitted($report));
        $admins->each(fn (User $admin) => DatabaseNotificationsSent::dispatch($admin));
        $report->notifyReporter(new ReportSubmitted($report->public_code));

        return to_route('reports.success')->with('submitted_report', [
            'code' => $report->public_code,
            'submitted_at' => $report->created_at->toIso8601String(),
        ]);
    }

    public function success(): View|RedirectResponse
    {
        $submittedReport = session('submitted_report');

        if (! is_array($submittedReport)) {
            return to_route('reports.create');
        }

        $trackingAccessToken = Crypt::encryptString(json_encode([
            'version' => 2,
            'code' => $submittedReport['code'],
        ], JSON_THROW_ON_ERROR));
        $trackingUrl = route('reports.track').'#access='.rawurlencode($trackingAccessToken);
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
