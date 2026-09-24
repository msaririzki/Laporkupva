<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackPublicReportRequest;
use App\Models\Report;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReportTrackingController extends Controller
{
    public function create(): View
    {
        return view('reports.track');
    }

    public function store(TrackPublicReportRequest $request): View
    {
        $report = Report::query()
            ->where('public_code', $request->string('tracking_code')->value())
            ->first();

        if (! $report || ! Hash::check($request->string('tracking_pin')->value(), $report->tracking_pin_hash)) {
            throw ValidationException::withMessages([
                'tracking_code' => 'Kode laporan atau PIN tidak cocok. Periksa kembali data Anda.',
            ]);
        }

        $report->load([
            'statusHistories' => fn ($query) => $query->oldest(),
        ]);

        return view('reports.status', ['report' => $report]);
    }
}
