<?php

namespace App\Filament\Resources\ReportProgressRequests\Pages;

use App\Filament\Resources\ReportProgressRequests\ReportProgressRequestResource;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\ReportProgressRequest;
use App\Rules\NoHtml;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View;

class ViewReportProgressRequest extends ViewRecord
{
    protected static string $resource = ReportProgressRequestResource::class;

    public function getTitle(): string
    {
        return 'Pengajuan '.$this->getRecord()->report->public_code;
    }

    public function getSubheading(): ?string
    {
        $request = $this->getRecord();
        $decision = match ($request->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Menunggu persetujuan',
        };

        return "{$decision} · {$request->from_status->label()} → {$request->to_status->label()} · Diajukan oleh ".($request->requester?->name ?? 'Petugas').' pada '.$request->created_at->translatedFormat('d M Y, H:i');
    }

    public function previewPhotoAction(): Action
    {
        return Action::make('previewPhoto')
            ->authorize('view')
            ->modalHeading('Pratinjau foto dokumentasi')
            ->modalDescription('Foto ditampilkan langsung tanpa meninggalkan halaman persetujuan.')
            ->modalContent(function (ReportProgressRequest $record, array $arguments): View {
                $paths = array_values($record->activity_photos ?? []);
                $index = filter_var($arguments['photo'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
                abort_unless($index !== false && array_key_exists($index, $paths), 404);

                return view('filament.reports.progress-photo-preview', [
                    'photoUrl' => route('admin.report-progress-photos.preview', ['reportProgressRequest' => $record, 'photo' => $index]),
                    'photoName' => $record->activity_photo_names[$paths[$index]] ?? 'Dokumentasi '.($index + 1),
                ]);
            })
            ->modalWidth(Width::ScreenTwoExtraLarge)
            ->extraModalWindowAttributes(['class' => 'tambora-image-preview-modal'])
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approveProgress')
                ->label('Setujui pengajuan')->icon(Heroicon::OutlinedCheckBadge)->color('success')
                ->authorize('review')->visible(fn (ReportProgressRequest $record): bool => $record->status === 'pending')
                ->modalHeading('Setujui pengajuan progres')
                ->modalDescription(fn (ReportProgressRequest $record): string => "{$record->report->public_code} akan diperbarui ke tahap {$record->to_status->label()}. Periksa pesan yang akan diterima pelapor sebelum menyetujui.")
                ->modalWidth(Width::TwoExtraLarge)->modalSubmitActionLabel('Setujui dan perbarui progres')
                ->fillForm(fn (ReportProgressRequest $record): array => ['public_note' => $record->public_note])
                ->schema([
                    Textarea::make('public_note')->label('Informasi untuk pelapor')->required()->maxLength(1000)->rule(new NoHtml)->rows(8),
                ])
                ->action(function (ReportProgressRequest $record, array $data): void {
                    $record->approve(auth()->user(), $data['public_note']);
                    $record->refresh();
                    Notification::make()->title('Pengajuan progres disetujui')
                        ->body("{$record->report->public_code} kini berada pada tahap {$record->to_status->label()}. Operator telah diberi notifikasi.")
                        ->success()->send();
                }),
            Action::make('rejectProgress')
                ->label('Tolak pengajuan')->icon(Heroicon::OutlinedXCircle)->color('danger')
                ->authorize('review')->visible(fn (ReportProgressRequest $record): bool => $record->status === 'pending')
                ->modalHeading('Tolak pengajuan progres')
                ->modalDescription('Jelaskan bagian catatan atau dokumentasi yang harus diperbaiki. Alasan penolakan dikirim kepada Operator agar dapat merevisi dan mengajukan ulang.')
                ->modalSubmitActionLabel('Tolak dan kirim alasan')
                ->schema([
                    Textarea::make('reason')->label('Alasan penolakan')->required()->minLength(10)->maxLength(1000)->rule(new NoHtml)->rows(6),
                ])
                ->action(function (ReportProgressRequest $record, array $data): void {
                    $record->reject(auth()->user(), $data['reason']);
                    $record->refresh();
                    Notification::make()->title('Pengajuan progres ditolak')
                        ->body('Alasan penolakan telah dikirim kepada Operator. Status masyarakat tetap pada tahap terakhir yang disetujui.')
                        ->warning()->send();
                }),
            Action::make('viewReport')->label('Detail laporan lengkap')->icon(Heroicon::OutlinedDocumentText)->color('gray')
                ->url(fn (ReportProgressRequest $record): string => ReportResource::getUrl('view', ['record' => $record->report])),
        ];
    }
}
