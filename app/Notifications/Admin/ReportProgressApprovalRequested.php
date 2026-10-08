<?php

namespace App\Notifications\Admin;

use App\Enums\UserRole;
use App\Filament\Resources\ReportProgressRequests\ReportProgressRequestResource;
use App\Models\ReportProgressRequest;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class ReportProgressApprovalRequested extends AccountNotification
{
    public function __construct(public ReportProgressRequest $request) {}

    protected function mailRecipientRole(): UserRole
    {
        return UserRole::SuperAdmin;
    }

    public function shouldSend(object $notifiable, string $channel): bool
    {
        return parent::shouldSend($notifiable, $channel)
            && ($channel !== 'mail' || $this->request->status === 'pending');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return $this->mailMessage('Pengajuan progres menunggu persetujuan · '.$this->request->report->public_code, 'Yth. Administrator,')
            ->line('Ada pengajuan pembaruan progres untuk laporan '.$this->request->report->public_code.'.')
            ->line('Tahap yang diajukan: '.$this->request->to_status->label().'.')
            ->action('Tinjau pengajuan', ReportProgressRequestResource::getUrl('view', ['record' => $this->request], panel: 'admin'))
            ->line('Periksa pesan dan dokumentasi di portal internal sebelum menyetujui pengajuan.')
            ->line('Email ini merupakan notifikasi otomatis (no-reply). Mohon tidak membalas email ini.');
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
