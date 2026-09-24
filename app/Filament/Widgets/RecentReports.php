<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentReports extends TableWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Laporan terbaru')
            ->description('Laporan masyarakat yang paling baru masuk ke sistem.')
            ->query(fn (): Builder => Report::query()->latest()->limit(6))
            ->columns([
                TextColumn::make('public_code')
                    ->label('Kode')
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('business_name')
                    ->label('Tempat/usaha')
                    ->placeholder('Tidak disebutkan')
                    ->limit(32),
                TextColumn::make('regency')
                    ->label('Wilayah'),
                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->since()
                    ->dateTimeTooltip('d M Y, H:i'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn (Report $record): string => ReportResource::getUrl('view', ['record' => $record])),
            ])
            ->recordUrl(fn (Report $record): string => ReportResource::getUrl('view', ['record' => $record]))
            ->paginated(false);
    }
}
