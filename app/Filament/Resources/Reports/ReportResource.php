<?php

namespace App\Filament\Resources\Reports;

use App\Enums\ReportStatus;
use App\Filament\Resources\Reports\Pages\EditReport;
use App\Filament\Resources\Reports\Pages\ListReports;
use App\Filament\Resources\Reports\Pages\ViewReport;
use App\Filament\Resources\Reports\Schemas\ReportForm;
use App\Filament\Resources\Reports\Schemas\ReportInfolist;
use App\Filament\Resources\Reports\Tables\ReportsTable;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\ReportStatusHistory;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RuntimeException;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $modelLabel = 'laporan';

    protected static ?string $pluralModelLabel = 'Laporan masyarakat';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $slug = 'laporan';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'public_code';

    public static function form(Schema $schema): Schema
    {
        return ReportForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function advanceStatusAction(): Action
    {
        return Action::make('advanceStatus')
            ->label('Update progres')
            ->icon(Heroicon::OutlinedArrowRightCircle)
            ->color('primary')
            ->visible(fn (Report $record): bool => $record->status->next() !== null)
            ->modalIcon(Heroicon::OutlinedArrowRightCircle)
            ->modalHeading('Update progres penanganan')
            ->modalDescription(fn (Report $record): string => "Lanjutkan dari {$record->status->label()} ke {$record->status->next()?->label()}.")
            ->modalSubmitActionLabel('Simpan & lanjutkan')
            ->modalWidth(Width::FourExtraLarge)
            ->schema([
                Grid::make([
                    'default' => 1,
                    'md' => 2,
                ])->schema([
                    Textarea::make('public_note')
                        ->label('Keterangan untuk pelapor')
                        ->placeholder('Tulis perkembangan yang aman dibaca pelapor.')
                        ->helperText('Terlihat oleh pelapor.')
                        ->maxLength(1000)
                        ->rows(2),
                    Textarea::make('internal_note')
                        ->label('Catatan internal')
                        ->placeholder('Contoh: Tim memeriksa lokasi dan berkoordinasi dengan pihak terkait.')
                        ->helperText('Hanya untuk admin.')
                        ->maxLength(2000)
                        ->rows(2),
                ]),
                self::activityPhotoUpload(),
            ])
            ->action(function (Report $record, array $data): void {
                Gate::authorize('create', ReportEvidence::class);

                /** @var User $user */
                $user = auth()->user();

                DB::transaction(function () use ($data, $record, $user): void {
                    $advanced = $record->advanceStatus(
                        $user,
                        $data['public_note'] ?? null,
                        $data['internal_note'] ?? null,
                    );

                    if (! $advanced) {
                        throw new RuntimeException('Laporan tidak dapat dilanjutkan ke tahap berikutnya.');
                    }

                    $history = $record->statusHistories()
                        ->where('user_id', $user->getKey())
                        ->where('to_status', $record->status->value)
                        ->latest('id')
                        ->firstOrFail();

                    self::storeActivityEvidence(
                        $record,
                        $history,
                        $user,
                        $data['activity_photos'] ?? [],
                        $data['activity_photo_names'] ?? [],
                        $data['internal_note'] ?? null,
                    );
                });

                $record->unsetRelation('evidence');
                $record->unsetRelation('activityEvidence');
                $record->unsetRelation('statusHistories');

                Notification::make()
                    ->title('Status laporan diperbarui')
                    ->body("{$record->public_code} kini berada pada tahap {$record->status->label()}.")
                    ->success()
                    ->send();
            });
    }

    public static function addActivityEvidenceAction(): Action
    {
        return Action::make('addActivityEvidence')
            ->label('Tambah dokumentasi')
            ->icon(Heroicon::OutlinedPhoto)
            ->color('gray')
            ->modalIcon(Heroicon::OutlinedPhoto)
            ->modalHeading('Tambah dokumentasi')
            ->modalDescription(fn (Report $record): string => "Tahap: {$record->status->label()} · hanya untuk admin.")
            ->modalSubmitActionLabel('Simpan')
            ->modalWidth(Width::TwoExtraLarge)
            ->schema([
                Textarea::make('caption')
                    ->label('Catatan')
                    ->placeholder('Contoh: Pemeriksaan lokasi dan koordinasi dengan pihak terkait.')
                    ->maxLength(2000)
                    ->rows(2),
                self::activityPhotoUpload(required: true),
            ])
            ->action(function (Report $record, array $data): void {
                Gate::authorize('create', ReportEvidence::class);

                /** @var User $user */
                $user = auth()->user();
                $history = $record->statusHistories()
                    ->where('to_status', $record->status->value)
                    ->latest('id')
                    ->firstOrFail();

                DB::transaction(function () use ($data, $history, $record, $user): void {
                    self::storeActivityEvidence(
                        $record,
                        $history,
                        $user,
                        $data['activity_photos'] ?? [],
                        $data['activity_photo_names'] ?? [],
                        $data['caption'] ?? null,
                    );
                });

                $record->unsetRelation('evidence');
                $record->unsetRelation('activityEvidence');
                $record->unsetRelation('statusHistories');

                Notification::make()
                    ->title('Dokumentasi disimpan')
                    ->body('Foto tersimpan pada tahap '.$record->status->label().'.')
                    ->success()
                    ->send();
            });
    }

    private static function activityPhotoUpload(bool $required = false): FileUpload
    {
        return FileUpload::make('activity_photos')
            ->label($required ? 'Foto kegiatan' : 'Foto kegiatan (opsional)')
            ->helperText('JPG, PNG, atau WebP · maksimal 6 foto · otomatis diperkecil.')
            ->placeholder('Pilih atau seret foto')
            ->image()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->rule(Rule::dimensions()->maxWidth(2048)->maxHeight(2048))
            ->multiple()
            ->required($required)
            ->maxFiles(6)
            ->maxSize(12288)
            ->maxParallelUploads(2)
            ->appendFiles()
            ->panelLayout('grid')
            ->itemPanelAspectRatio('3:4')
            ->extraAttributes(['class' => 'activity-photo-upload'])
            ->disk('local')
            ->directory(fn (Report $record): string => "report-activity/{$record->getKey()}")
            ->visibility('private')
            ->storeFileNamesIn('activity_photo_names')
            ->preventFilePathTampering()
            ->automaticallyResizeImagesMode('contain')
            ->automaticallyResizeImagesToWidth('2048')
            ->automaticallyResizeImagesToHeight('2048')
            ->automaticallyUpscaleImagesWhenResizing(false)
            ->uploadingMessage('Mengoptimalkan dan mengunggah foto…');
    }

    /**
     * @param  array<array-key, string>  $paths
     * @param  array<string, string>  $originalNames
     */
    private static function storeActivityEvidence(
        Report $report,
        ReportStatusHistory $history,
        User $user,
        array $paths,
        array $originalNames,
        ?string $caption,
    ): void {
        $disk = Storage::disk('local');
        $directory = "report-activity/{$report->getKey()}/";

        foreach ($paths as $path) {
            if (! is_string($path) || ! str_starts_with($path, $directory) || ! $disk->exists($path)) {
                throw new RuntimeException('Lokasi foto kegiatan tidak valid.');
            }

            $report->evidence()->create([
                'report_status_history_id' => $history->getKey(),
                'uploaded_by_user_id' => $user->getKey(),
                'source' => 'admin_activity',
                'path' => $path,
                'original_name' => $originalNames[$path] ?? basename($path),
                'mime_type' => $disk->mimeType($path) ?: 'application/octet-stream',
                'size' => $disk->size($path),
                'caption' => filled($caption) ? trim($caption) : null,
            ]);
        }
    }

    public static function sendMessageAction(): Action
    {
        return Action::make('sendMessage')
            ->label('Hubungi pelapor')
            ->icon(Heroicon::OutlinedChatBubbleLeftRight)
            ->color('gray')
            ->modalHeading('Kirim pesan anonim kepada pelapor')
            ->modalDescription('Pelapor dapat membaca dan membalas pesan ini menggunakan kode laporan dan PIN miliknya.')
            ->modalSubmitActionLabel('Kirim pesan')
            ->schema([
                Textarea::make('body')
                    ->label('Pesan untuk pelapor')
                    ->placeholder('Contoh: Mohon tambahkan petunjuk lokasi yang lebih rinci.')
                    ->required()
                    ->minLength(2)
                    ->maxLength(2000)
                    ->rows(5),
            ])
            ->action(function (Report $record, array $data): void {
                $record->anonymousMessages()->create([
                    'user_id' => auth()->id(),
                    'sender_type' => 'admin',
                    'body' => trim($data['body']),
                ]);
                $record->unsetRelation('anonymousMessages');

                Notification::make()
                    ->title('Pesan dikirim')
                    ->body("Pesan untuk pelapor {$record->public_code} berhasil dikirim.")
                    ->success()
                    ->send();
            });
    }

    public static function correctStatusAction(): Action
    {
        return Action::make('correctStatus')
            ->label('Koreksi status')
            ->icon(Heroicon::OutlinedArrowUturnLeft)
            ->color('warning')
            ->visible(fn (Report $record): bool => auth()->user()?->isSuperAdmin() === true && $record->status !== ReportStatus::Submitted)
            ->modalHeading('Koreksi tahap penanganan')
            ->modalDescription('Khusus Super Admin. Riwayat status lama tetap tersimpan dan alasan koreksi dicatat sebagai catatan internal.')
            ->modalSubmitActionLabel('Simpan koreksi')
            ->schema([
                Select::make('status')
                    ->label('Kembalikan ke tahap')
                    ->options(fn (Report $record): array => self::previousStatusOptions($record))
                    ->required(),
                Textarea::make('reason')
                    ->label('Alasan koreksi')
                    ->required()
                    ->minLength(10)
                    ->maxLength(1000)
                    ->rows(4),
                Textarea::make('public_note')
                    ->label('Keterangan untuk pelapor')
                    ->helperText('Opsional. Alasan internal tidak akan ditampilkan kepada pelapor.')
                    ->maxLength(1000)
                    ->rows(3),
            ])
            ->action(function (Report $record, array $data): void {
                $record->correctStatus(
                    auth()->user(),
                    ReportStatus::from($data['status']),
                    $data['reason'],
                    $data['public_note'] ?? null,
                );

                Notification::make()
                    ->title('Status berhasil dikoreksi')
                    ->body("{$record->public_code} dikembalikan ke tahap {$record->status->label()}.")
                    ->warning()
                    ->send();
            });

    }

    /** @return array<string, string> */
    private static function previousStatusOptions(Report $report): array
    {
        $options = [];

        foreach (ReportStatus::cases() as $status) {
            if ($status === $report->status) {
                break;
            }

            $options[$status->value] = $status->label();
        }

        return $options;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReports::route('/'),
            'view' => ViewReport::route('/{record}'),
            'edit' => EditReport::route('/{record}/edit'),
        ];
    }
}
