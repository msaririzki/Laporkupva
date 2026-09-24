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
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

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
            ->modalDescription(fn (Report $record): string => "Tahap saat ini: {$record->status->label()}. Setelah disimpan, laporan dilanjutkan ke tahap {$record->status->next()?->label()}.")
            ->modalSubmitActionLabel('Simpan dan lanjutkan tahap')
            ->schema([
                Textarea::make('public_note')
                    ->label('Keterangan untuk pelapor')
                    ->placeholder('Jelaskan perkembangan secara singkat tanpa memuat informasi internal atau data sensitif.')
                    ->helperText('Keterangan ini dapat dilihat oleh pelapor anonim.')
                    ->maxLength(1000)
                    ->rows(4),
            ])
            ->action(function (Report $record, array $data): void {
                $record->advanceStatus(auth()->user(), $data['public_note'] ?? null);

                Notification::make()
                    ->title('Status laporan diperbarui')
                    ->body("{$record->public_code} kini berada pada tahap {$record->status->label()}.")
                    ->success()
                    ->send();
            });
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
