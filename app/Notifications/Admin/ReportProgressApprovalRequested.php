<?php

namespace App\Notifications\Admin;

use App\Filament\Resources\ReportProgressRequests\ReportProgressRequestResource;
use App\Models\ReportProgressRequest;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ReportProgressApprovalRequested extends Notification
{
    public function __construct(public ReportProgressRequest $request) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [...$this->filamentNotification()->getDatabaseMessage(), 'report_id' => $this->request->report_id, 'progress_request_id' => $this->request->id];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return $this->filamentNotification()->getBroadcastMessage();
    }

    private function filamentNotification(): FilamentNotification
    {
        return FilamentNotification::make()->title('Pengajuan progres menunggu persetujuan')
            ->body("{$this->request->report->public_code}: {$this->request->requester?->name} mengajukan tahap {$this->request->to_status->label()}. Periksa pesan dan dokumentasi sebelum menyetujui.")
            ->icon('heroicon-o-check-badge')->iconColor('warning')
            ->actions([Action::make('reviewProgress')->label('Tinjau pengajuan')
                ->url(ReportProgressRequestResource::getUrl('view', ['record' => $this->request], isAbsolute: false))->button()->markAsRead()]);
    }
}
