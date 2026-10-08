<?php

namespace App\Notifications\Admin;

use App\Enums\UserRole;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class NewReportSubmitted extends AccountNotification
{
    public function __construct(public Report $report) {}

    protected function mailRecipientRole(): UserRole
    {
        return UserRole::Admin;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return $this->mailMessage('Laporan baru masuk · '.$this->report->public_code, 'Yth. Operator,')
            ->line('Laporan '.$this->report->public_code.' dari '.$this->report->regency.' telah masuk dan menunggu pemeriksaan.')
            ->action('Tinjau laporan', ReportResource::getUrl('view', ['record' => $this->report], panel: 'admin'))
            ->line('Buka portal internal untuk melihat rincian laporan dan bukti pendukung.')
            ->line('Email ini merupakan notifikasi otomatis (no-reply). Mohon tidak membalas email ini.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            ...$this->filamentNotification()->getDatabaseMessage(),
            'report_id' => $this->report->getKey(),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return $this->filamentNotification()->getBroadcastMessage();
    }

    private function filamentNotification(): FilamentNotification
    {
        return FilamentNotification::make()
            ->title('Laporan baru masuk')
            ->body("{$this->report->public_code} dari {$this->report->regency} menunggu untuk ditinjau.")
            ->icon(Heroicon::OutlinedDocumentPlus)
            ->iconColor('primary')
            ->actions([
                Action::make('viewReport')
                    ->label('Lihat laporan')
                    ->url($this->reportConversationUrl())
                    ->button()
                    ->markAsRead(),
            ]);
    }

    private function reportConversationUrl(): string
    {
        return ReportResource::getUrl('view', ['record' => $this->report], isAbsolute: false).'#komunikasi-anonim';
    }
}
