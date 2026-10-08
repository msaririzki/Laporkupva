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
use Illuminate\Support\Str;

class NewReporterMessage extends AccountNotification
{
    public function __construct(public Report $report, public string $messageBody) {}

    protected function mailRecipientRole(): UserRole
    {
        return UserRole::Admin;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return $this->mailMessage('Pesan baru dari pelapor · '.$this->report->public_code, 'Yth. Operator,')
            ->line('Pelapor mengirim pesan baru pada laporan '.$this->report->public_code.'.')
            ->action('Buka percakapan', ReportResource::getUrl('view', ['record' => $this->report], panel: 'admin').'#komunikasi-anonim')
            ->line('Isi pesan tersedia di portal internal. Silakan masuk untuk membaca dan menanggapi pesan.')
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
            ->title('Pesan baru dari pelapor')
            ->body("{$this->report->public_code}: ".Str::limit($this->messageBody, 100))
            ->icon(Heroicon::OutlinedChatBubbleLeftRight)
            ->iconColor('success')
            ->actions([
                Action::make('viewConversation')
                    ->label('Buka percakapan')
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
