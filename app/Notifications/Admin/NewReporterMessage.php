<?php

namespace App\Notifications\Admin;

use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewReporterMessage extends Notification
{
    use Queueable;

    public function __construct(public Report $report, public string $messageBody) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Pesan baru dari pelapor')
            ->body("{$this->report->public_code}: ".Str::limit($this->messageBody, 100))
            ->icon(Heroicon::OutlinedChatBubbleLeftRight)
            ->iconColor('success')
            ->actions([
                Action::make('viewConversation')
                    ->label('Buka percakapan')
                    ->url(ReportResource::getUrl('view', ['record' => $this->report], isAbsolute: false))
                    ->button()
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
