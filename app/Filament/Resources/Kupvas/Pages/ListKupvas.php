<?php

namespace App\Filament\Resources\Kupvas\Pages;

use App\Filament\Resources\Kupvas\KupvaCsvImporter;
use App\Filament\Resources\Kupvas\KupvaResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class ListKupvas extends ListRecords
{
    protected static string $resource = KupvaResource::class;

    public function getSubheading(): ?string
    {
        return 'Kelola referensi penyelenggara KUPVA dan pantau status izin operasionalnya.';
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('downloadTemplate')
                    ->label('Unduh template CSV')
                    ->icon(Heroicon::OutlinedDocumentArrowDown)
                    ->url(fn (): string => route('admin.kupvas.template')),
                Action::make('importCsv')
                    ->label('Impor data CSV')
                    ->icon(Heroicon::OutlinedArrowUpTray)
                    ->modalHeading('Impor data KUPVA')
                    ->modalDescription('Gunakan template agar kolom terbaca dengan benar. Kosongkan ID untuk data baru; data lama diperbarui berdasarkan ID atau nomor izin.')
                    ->modalSubmitActionLabel('Mulai impor')
                    ->modalWidth(Width::Large)
                    ->schema([
                        FileUpload::make('file')
                            ->label('Berkas CSV')
                            ->helperText('CSV maksimal 5 MB dan 1.000 baris. Seluruh data diperiksa sebelum disimpan.')
                            ->acceptedFileTypes([
                                'text/csv',
                                'application/csv',
                                'application/vnd.ms-excel',
                                'text/plain',
                            ])
                            ->maxSize(5120)
                            ->storeFiles(false)
                            ->visibility('private')
                            ->required(),
                    ])
                    ->action(function (Action $action, array $data): void {
                        $file = $data['file'] ?? null;

                        if (! $file instanceof UploadedFile) {
                            $action->failure();

                            return;
                        }

                        try {
                            $result = app(KupvaCsvImporter::class)->import($file);
                        } catch (ValidationException $exception) {
                            Notification::make()
                                ->title('Impor belum dapat diproses')
                                ->body(collect($exception->errors())->flatten()->take(4)->implode(' '))
                                ->danger()
                                ->send();

                            $action->failure();

                            return;
                        }

                        Notification::make()
                            ->title('Data KUPVA berhasil diimpor')
                            ->body("{$result['created']} data baru ditambahkan dan {$result['updated']} data diperbarui.")
                            ->success()
                            ->send();
                    }),
                Action::make('exportCsv')
                    ->label('Ekspor data CSV')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->url(fn (): string => route('admin.kupvas.export')),
            ])
                ->button()
                ->label('Impor / ekspor')
                ->icon(Heroicon::OutlinedArrowsUpDown)
                ->color('gray'),
            CreateAction::make()
                ->label('Tambah KUPVA'),
        ];
    }
}
