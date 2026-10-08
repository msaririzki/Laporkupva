<?php

namespace App\Notifications\Admin;

use App\Filament\Resources\Reports\ReportResource;
use App\Models\ReportProgressRequest;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ReportProgressApprovalReviewed extends Notification
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
        $approved = $this->request->status === 'approved';
        $body = $approved
            ? "{$this->request->report->public_code}: pengajuan tahap {$this->request->to_status->label()} telah disetujui Administrator. Progres dan pesan untuk masyarakat sudah diperbarui."
            : "{$this->request->report->public_code}: pengajuan tahap {$this->request->to_status->label()} ditolak. Alasan: {$this->request->rejection_reason}. Perbaiki pengajuan sebelum mengirim kembali.";

        return FilamentNotification::make()->title($approved ? 'Pengajuan progres disetujui' : 'Pengajuan progres ditolak')
            ->body($body)->icon($approved ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')->iconColor($approved ? 'success' : 'danger')
            ->actions([Action::make('viewProgress')->label('Lihat laporan')
                ->url(ReportResource::getUrl('view', ['record' => $this->request->report], isAbsolute: false).'#persetujuan-progres')->button()->markAsRead()]);
    }
}
