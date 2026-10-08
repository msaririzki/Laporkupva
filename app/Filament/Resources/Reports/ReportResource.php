<?php

namespace App\Filament\Resources\Reports;

use App\Enums\ReportStatus;
use App\Filament\Resources\ReportProgressRequests\ReportProgressRequestResource;
use App\Filament\Resources\Reports\Pages\EditReport;
use App\Filament\Resources\Reports\Pages\ListReports;
use App\Filament\Resources\Reports\Pages\ViewReport;
use App\Filament\Resources\Reports\Schemas\ReportForm;
use App\Filament\Resources\Reports\Schemas\ReportInfolist;
use App\Filament\Resources\Reports\Tables\ReportsTable;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\ReportProgressRequest;
use App\Models\User;
use App\Rules\NoHtml;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (auth()->user()?->canManageApplication() === true) {
            $query->with(['pendingProgressRequest.requester', 'latestProgressRequest.reviewer']);
        }

        if (auth()->user()?->canViewReporterIdentity() !== true) {
            $query->select([
                'id', 'public_code', 'status', 'incident_type', 'business_name',
                'incident_date', 'incident_time', 'description', 'is_ongoing',
                'province', 'regency', 'district', 'village', 'address',
                'latitude', 'longitude', 'location_accuracy', 'public_update',
                'internal_notes', 'received_at', 'coordinated_at', 'field_action_at',
                'completed_at', 'created_at', 'updated_at',
            ]);
        }

        return $query;
    }

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
            ->label(fn (Report $record): string => $record->status->next()?->requiresApproval() ? 'Ajukan progres' : 'Update progres')
            ->icon(Heroicon::OutlinedArrowRightCircle)
            ->color('primary')
            ->extraAttributes(['data-advance-report' => 'true'])
            ->authorize('update')
            ->visible(fn (Report $record): bool => auth()->user()?->canManageApplication() === true && $record->status->next() !== null && $record->pendingProgressRequest === null)
            ->modalIcon(Heroicon::OutlinedArrowRightCircle)
            ->modalHeading(fn (Report $record): string => $record->status->next()?->requiresApproval() ? 'Ajukan progres kepada Administrator' : 'Update progres')
            ->modalDescription(fn (Report $record): string => "{$record->status->label()} → {$record->status->next()?->label()}".($record->status->next()?->requiresApproval() ? '. Progres dan pesan masyarakat baru berubah setelah Administrator menyetujui pengajuan.' : ''))
            ->modalSubmitActionLabel(fn (Report $record): string => $record->status->next()?->requiresApproval() ? 'Kirim pengajuan' : 'Lanjutkan tahap')
            ->modalWidth(Width::TwoExtraLarge)
            ->extraModalWindowAttributes(['class' => 'tambora-action-modal tambora-action-modal--progress'])
            ->extraModalOverlayAttributes(['class' => 'tambora-action-modal-overlay'])
            ->schema([
                Hidden::make('expected_from_status')->default(fn (Report $record): string => $record->status->value)->required(),
                Grid::make([
                    'default' => 1,
                    'sm' => 2,
                ])->schema([
                    Textarea::make('public_note')
                        ->label('Informasi untuk pelapor')
                        ->default(fn (Report $record): ?string => $record->status->next()?->publicMessage($record->public_code))
                        ->placeholder('Tulis perkembangan yang dapat dibaca pelapor.')
                        ->helperText('Template sudah disesuaikan dengan nomor laporan dan tahap berikutnya. Periksa dan sesuaikan dengan kegiatan yang dilakukan; Anda dapat menambahkan keterangan sebelum menyimpan. Pesan ini akan tampil kepada pelapor.')
                        ->maxLength(1000)
                        ->rule(new NoHtml)
                        ->rows(8)
                        ->columnSpanFull(),
                    Textarea::make('internal_note')
                        ->label('Catatan Operator')
                        ->placeholder('Contoh: Hasil pemeriksaan atau koordinasi petugas.')
                        ->helperText('Hanya dapat dilihat Administrator dan Operator.')
                        ->maxLength(2000)
                        ->rule(new NoHtml)
                        ->rows(8)
                        ->columnSpanFull(),
                ]),
                self::activityPhotoUpload(),
            ])
            ->action(function (Report $record, array $data): void {
                Gate::authorize('update', $record);
                Gate::authorize('create', ReportEvidence::class);

                if (($data['expected_from_status'] ?? null) !== $record->status->value) {
                    throw ValidationException::withMessages(['public_note' => 'Tahap laporan sudah berubah. Tutup formulir dan muat ulang laporan sebelum mengajukan progres.']);
                }

                /** @var User $user */
                $user = auth()->user();

                if ($record->status->next()?->requiresApproval()) {
                    ReportProgressRequest::submit($record, $user, $data);
                    $record->unsetRelation('pendingProgressRequest');
                    $record->unsetRelation('latestProgressRequest');
                    Notification::make()->title('Pengajuan progres dikirim')
                        ->body("{$record->public_code} menunggu persetujuan Administrator. Status masyarakat tetap pada tahap {$record->status->label()}.")
                        ->warning()->send();

                    return;
                }

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

                    $record->storeActivityEvidence(
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
            ->authorize('update')
            ->visible(fn (): bool => auth()->user()?->canManageApplication() === true)
            ->label('Tambah dokumentasi')
            ->icon(Heroicon::OutlinedPhoto)
            ->color('gray')
            ->modalIcon(Heroicon::OutlinedPhoto)
            ->modalHeading('Tambah dokumentasi')
            ->modalDescription(fn (Report $record): string => "Tahap: {$record->status->label()} · hanya untuk Administrator dan Operator.")
            ->modalSubmitActionLabel('Simpan')
            ->modalWidth(Width::TwoExtraLarge)
            ->schema([
                Textarea::make('caption')
                    ->label('Catatan')
                    ->placeholder('Contoh: Pemeriksaan lokasi dan koordinasi dengan pihak terkait.')
                    ->maxLength(2000)
                    ->rule(new NoHtml)
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
                    $record->storeActivityEvidence(
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

    public static function approveProgressAction(): Action
    {
        return Action::make('approveProgress')
            ->label('Tinjau dan setujui')
            ->icon(Heroicon::OutlinedCheckBadge)
            ->color('success')
            ->authorize(fn (): bool => auth()->user()?->isSuperAdmin() === true && auth()->user()?->is_active === true)
            ->visible(fn (Report $record): bool => auth()->user()?->isSuperAdmin() === true && $record->pendingProgressRequest !== null)
            ->modalHeading('Tinjau pengajuan progres')
            ->modalDescription(fn (Report $record): string => "{$record->public_code}: {$record->status->label()} → {$record->pendingProgressRequest?->to_status->label()}. Persetujuan akan memperbarui status dan pesan yang dilihat masyarakat.")
            ->modalSubmitActionLabel('Setujui dan perbarui progres')
            ->modalWidth(Width::TwoExtraLarge)
            ->fillForm(fn (Report $record): array => [
                'progress_request_id' => $record->pendingProgressRequest?->id,
                'public_note' => $record->pendingProgressRequest?->public_note,
                'internal_note' => $record->pendingProgressRequest?->internal_note,
                'activity_photos' => $record->pendingProgressRequest?->activity_photos ?? [],
                'activity_photo_names' => $record->pendingProgressRequest?->activity_photo_names ?? [],
            ])
            ->schema([
                Hidden::make('progress_request_id')->required()->rule('integer'),
                Textarea::make('public_note')->label('Pesan untuk masyarakat')->required()->maxLength(1000)->rule(new NoHtml)->rows(8)
                    ->helperText('Periksa dan sesuaikan pesan sebelum menyetujui. Pesan ini akan ditampilkan kepada pelapor.'),
                Textarea::make('internal_note')->label('Catatan internal')->maxLength(2000)->rule(new NoHtml)->rows(3),
                self::activityPhotoUpload()->disabled()->dehydrated(false)->preventFilePathTampering(false)
                    ->helperText('Dokumentasi yang dilampirkan pengaju. Foto dicatat pada tahap laporan setelah disetujui.'),
            ])
            ->action(function (Report $record, array $data): void {
                $request = $record->progressRequests()->findOrFail($data['progress_request_id']);
                $request->approve(auth()->user(), $data['public_note'], $data['internal_note'] ?? null);
                $record->refresh();
                Notification::make()->title('Pengajuan progres disetujui')->body("{$record->public_code} kini berada pada tahap {$record->status->label()}.")->success()->send();
            });
    }

    public static function reviewProgressAction(): Action
    {
        return Action::make('reviewProgress')
            ->label('Tinjau pengajuan')->icon(Heroicon::OutlinedCheckBadge)->color('warning')
            ->visible(fn (Report $record): bool => auth()->user()?->isSuperAdmin() === true && $record->pendingProgressRequest !== null)
            ->url(fn (Report $record): string => ReportProgressRequestResource::getUrl('view', ['record' => $record->pendingProgressRequest]));
    }

    public static function rejectProgressAction(): Action
    {
        return Action::make('rejectProgress')
            ->label('Tolak pengajuan')
            ->icon(Heroicon::OutlinedXCircle)
            ->color('danger')
            ->authorize(fn (): bool => auth()->user()?->isSuperAdmin() === true && auth()->user()?->is_active === true)
            ->visible(fn (Report $record): bool => auth()->user()?->isSuperAdmin() === true && $record->pendingProgressRequest !== null)
            ->modalHeading('Tolak pengajuan progres')
            ->modalDescription('Status laporan masyarakat tetap pada tahap terakhir yang disetujui. Alasan penolakan dikirim kepada pengaju untuk diperbaiki.')
            ->modalSubmitActionLabel('Tolak dan kirim alasan')
            ->fillForm(fn (Report $record): array => ['progress_request_id' => $record->pendingProgressRequest?->id])
            ->schema([
                Hidden::make('progress_request_id')->required()->rule('integer'),
                Textarea::make('reason')->label('Alasan penolakan')->required()->minLength(10)->maxLength(1000)->rule(new NoHtml)->rows(4),
            ])
            ->action(function (Report $record, array $data): void {
                $record->progressRequests()->findOrFail($data['progress_request_id'])->reject(auth()->user(), $data['reason']);
                $record->refresh();
                Notification::make()->title('Pengajuan progres ditolak')->body('Alasan penolakan telah dikirim kepada pengaju. Status masyarakat tetap.')->warning()->send();
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

    public static function correctStatusAction(): Action
    {
        return Action::make('correctStatus')
            ->label('Koreksi status')
            ->icon(Heroicon::OutlinedArrowUturnLeft)
            ->color('warning')
            ->visible(fn (Report $record): bool => auth()->user()?->isSuperAdmin() === true && $record->status !== ReportStatus::Submitted && $record->pendingProgressRequest === null)
            ->modalHeading('Koreksi tahap penanganan')
            ->modalDescription('Khusus Administrator. Riwayat status lama tetap tersimpan dan alasan koreksi dicatat sebagai catatan internal.')
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
                    ->rule(new NoHtml)
                    ->rows(4),
                Textarea::make('public_note')
                    ->label('Keterangan untuk pelapor')
                    ->helperText('Opsional. Alasan internal tidak akan ditampilkan kepada pelapor.')
                    ->maxLength(1000)
                    ->rule(new NoHtml)
                    ->rows(3),
            ])
            ->action(function (Report $record, array $data): void {
                Gate::authorize('update', $record);
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
