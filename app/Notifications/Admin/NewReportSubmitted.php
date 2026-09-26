<?php

namespace App\Notifications\Admin;

use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReportSubmitted extends Notification
{
    use Queueable;

    public function __construct(public Report $report) {}

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
            ->title('Laporan baru masuk')
            ->body("{$this->report->public_code} dari {$this->report->regency} menunggu untuk ditinjau.")
            ->icon(Heroicon::OutlinedDocumentPlus)
            ->iconColor('primary')
            ->actions([
                Action::make('viewReport')
                    ->label('Lihat laporan')
                    ->url(ReportResource::getUrl('view', ['record' => $this->report], isAbsolute: false))
                    ->button()
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
