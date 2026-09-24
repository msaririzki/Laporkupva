<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackPublicReportRequest;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function show(Request $request, Report $report): View
    {
        $expiresAt = (int) $request->session()->get("tracked_reports.{$report->getKey()}", 0);

        abort_if($expiresAt < now()->getTimestamp(), 404);

        $report->load([
            'statusHistories' => fn ($query) => $query->oldest(),
            'anonymousMessages' => fn ($query) => $query->oldest(),
        ]);

        return view('reports.status', ['report' => $report]);
    }
}
