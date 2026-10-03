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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Components\Wizard\Step;
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
                    ->label('Unduh template Excel')
                    ->icon(Heroicon::OutlinedDocumentArrowDown)
                    ->url(fn (): string => route('admin.kupvas.template'))
                    ->extraAttributes(['download' => 'template-impor-kupva.xlsx']),
                Action::make('importSpreadsheet')
                    ->label('Impor data')
                    ->icon(Heroicon::OutlinedArrowUpTray)
                    ->modalHeading('Impor data KUPVA')
                    ->modalDescription('Periksa data baru, perubahan, dan duplikat sebelum menyimpannya.')
                    ->modalSubmitActionLabel('Simpan hasil impor')
                    ->modalWidth(Width::FiveExtraLarge)
                    ->closeModalByClickingAway(false)
                    ->extraModalWindowAttributes(['class' => 'tambora-action-modal tambora-kupva-import-modal'])
                    ->extraModalOverlayAttributes(['class' => 'tambora-action-modal-overlay'])
                    ->steps([
                        Step::make('Pilih berkas')
                            ->description('Excel atau CSV')
                            ->icon(Heroicon::OutlinedDocumentArrowUp)
                            ->schema([
                                SchemaView::make('filament.kupvas.import-upload-help')
                                    ->viewData(fn (): array => [
                                        'templateUrl' => route('admin.kupvas.template'),
                                    ]),
                                FileUpload::make('file')
                                    ->label('Berkas data KUPVA')
                                    ->helperText('Maksimal 5 MB dan 1.000 baris. Berkas lama berformat CSV tetap dapat digunakan.')
                                    ->acceptedFileTypes([
                                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                        'text/csv',
                                        'application/csv',
                                        'application/vnd.ms-excel',
                                        'text/plain',
                                    ])
                                    ->maxSize(5120)
                                    ->storeFiles(false)
                                    ->visibility('private')
                                    ->required(),
                            ]),
                        Step::make('Periksa data')
                            ->description('Pratinjau sebelum disimpan')
                            ->icon(Heroicon::OutlinedMagnifyingGlass)
                            ->schema([
                                SchemaView::make('filament.kupvas.import-preview')
                                    ->viewData(function (Get $get): array {
                                        $file = $get('file');

                                        return [
                                            'analysis' => $file instanceof UploadedFile
                                                ? app(KupvaCsvImporter::class)->analyze($file)
                                                : null,
                                        ];
                                    }),
                            ]),
                    ])
                    ->action(function (Action $action, array $data): void {
                        $file = $data['file'] ?? null;

                        if (! $file instanceof UploadedFile) {
                            Notification::make()
                                ->title('Berkas belum dipilih')
                                ->danger()
                                ->send();

                            $action->halt();
                        }

                        try {
                            $result = app(KupvaCsvImporter::class)->import($file);
                        } catch (ValidationException $exception) {
                            Notification::make()
                                ->title('Impor belum dapat disimpan')
                                ->body(collect($exception->errors())->flatten()->take(4)->implode(' '))
                                ->danger()
                                ->send();

                            $action->halt();
                        }

                        $details = [
                            "{$result['created']} data baru",
                            "{$result['updated']} diperbarui",
                        ];

                        if ($result['unchanged'] > 0) {
                            $details[] = "{$result['unchanged']} tanpa perubahan";
                        }

                        if ($result['duplicates'] > 0) {
                            $details[] = "{$result['duplicates']} duplikat dilewati";
                        }

                        Notification::make()
                            ->title('Data KUPVA berhasil diimpor')
                            ->body(implode(', ', $details).'.')
                            ->success()
                            ->send();
                    }),
                Action::make('exportSpreadsheet')
                    ->label('Ekspor data Excel')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->url(fn (): string => route('admin.kupvas.export'))
                    ->extraAttributes(['download' => true]),
            ])
                ->button()
                ->label('Kelola data')
                ->icon(Heroicon::OutlinedTableCells)
                ->color('gray'),
            CreateAction::make()
                ->label('Tambah KUPVA'),
        ];
    }
}
