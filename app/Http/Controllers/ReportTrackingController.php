<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackPublicReportRequest;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReportTrackingController extends Controller
{
    public function create(): View
    {
        return view('reports.track');
    }

    public function store(TrackPublicReportRequest $request): RedirectResponse
    {
        $report = Report::query()
            ->where('public_code', $request->string('tracking_code')->value())
            ->first();

        if (! $report || ! Hash::check($request->string('tracking_pin')->value(), $report->tracking_pin_hash)) {
            throw ValidationException::withMessages([
                'tracking_code' => 'Kode laporan atau PIN tidak cocok. Periksa kembali data Anda.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->put(
            "tracked_reports.{$report->getKey()}",
            now()->addMinutes((int) config('tambora.tracking_session_minutes'))->getTimestamp(),
        );

        return redirect()->route('reports.status', ['report' => $report->public_code]);
    }

    public function show(Request $request, Report $report): Response
    {
        $this->ensureTrackingSessionIsValid($request, $report);

        $report->load([
            'statusHistories' => fn ($query) => $query->oldest(),
            'anonymousMessages' => fn ($query) => $query->oldest(),
        ]);

        return response()->view('reports.status', [
            'report' => $report,
            'statusVersion' => $this->statusVersion($report),
        ], headers: [
            'Cache-Control' => 'private, no-store, max-age=0',
            'Pragma' => 'no-cache',
            'Referrer-Policy' => 'no-referrer',
            'X-Robots-Tag' => 'noindex, nofollow, noarchive',
        ]);
    }

    public function updates(Request $request, Report $report): JsonResponse
    {
        $this->ensureTrackingSessionIsValid($request, $report);

        return response()->json([
            'version' => $this->statusVersion($report),
        ], headers: [
            'Cache-Control' => 'private, no-store, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    private function ensureTrackingSessionIsValid(Request $request, Report $report): void
    {
        $expiresAt = (int) $request->session()->get("tracked_reports.{$report->getKey()}", 0);

        abort_if($expiresAt < now()->getTimestamp(), 404);
    }

    private function statusVersion(Report $report): string
    {
        $report
            ->loadCount(['statusHistories', 'anonymousMessages'])
            ->loadMax(['statusHistories', 'anonymousMessages'], 'id');

        return hash('sha256', implode('|', [
            $report->status->value,
            $report->updated_at?->toJSON() ?? '',
            (string) $report->status_histories_count,
            (string) ($report->status_histories_max_id ?? 0),
            (string) $report->anonymous_messages_count,
            (string) ($report->anonymous_messages_max_id ?? 0),
        ]));
    }
}
