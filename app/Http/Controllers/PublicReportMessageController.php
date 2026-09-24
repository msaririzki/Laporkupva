<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnonymousMessageRequest;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;

class PublicReportMessageController extends Controller
{
    public function __invoke(StoreAnonymousMessageRequest $request, Report $report): RedirectResponse
    {
        $report->anonymousMessages()->create([
            'user_id' => null,
            'sender_type' => 'reporter',
            'body' => $request->string('body')->value(),
        ]);

        return redirect()
            ->route('reports.status', ['report' => $report->public_code])
            ->with('message_sent', 'Pesan Anda berhasil dikirim kepada petugas TAMBORA.');
    }
}
