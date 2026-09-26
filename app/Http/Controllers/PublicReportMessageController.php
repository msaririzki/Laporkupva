<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreAnonymousMessageRequest;
use App\Models\Report;
use App\Models\User;
use App\Notifications\Admin\NewReporterMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class PublicReportMessageController extends Controller
{
    public function __invoke(StoreAnonymousMessageRequest $request, Report $report): RedirectResponse
    {
        $messageBody = $request->string('body')->value();

        $report->anonymousMessages()->create([
            'user_id' => null,
            'sender_type' => 'reporter',
            'body' => $messageBody,
        ]);

        Notification::send(
            User::query()
                ->where('is_active', true)
                ->whereIn('role', [UserRole::Admin->value, UserRole::SuperAdmin->value])
                ->get(),
            new NewReporterMessage($report, $messageBody),
        );

        return redirect()
            ->route('reports.status', ['report' => $report->public_code])
            ->with('message_sent', 'Pesan Anda berhasil dikirim kepada petugas TAMBORA.');
    }
}
