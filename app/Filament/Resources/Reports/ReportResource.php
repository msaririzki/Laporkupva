<?php

namespace App\Filament\Resources\Reports;

use App\Filament\Resources\Reports\Pages\EditReport;
use App\Filament\Resources\Reports\Pages\ListReports;
use App\Filament\Resources\Reports\Pages\ViewReport;
use App\Filament\Resources\Reports\Schemas\ReportForm;
use App\Filament\Resources\Reports\Schemas\ReportInfolist;
use App\Filament\Resources\Reports\Tables\ReportsTable;
use App\Models\Report;
use BackedEnum;
use Filament\Actions\Action;
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
            ->label(fn (Report $record): string => $record->status->next()?->label() ?? 'Selesai')
            ->icon(Heroicon::OutlinedArrowRightCircle)
            ->color('primary')
            ->visible(fn (Report $record): bool => $record->status->next() !== null)
            ->modalHeading('Lanjutkan tahap penanganan')
            ->modalDescription(fn (Report $record): string => "Status akan diperbarui menjadi: {$record->status->next()?->label()}.")
            ->modalSubmitActionLabel('Perbarui status')
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

    public static function getPages(): array
    {
        return [
            'index' => ListReports::route('/'),
            'view' => ViewReport::route('/{record}'),
            'edit' => EditReport::route('/{record}/edit'),
        ];
    }
}
