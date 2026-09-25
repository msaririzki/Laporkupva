<?php

namespace App\Filament\Widgets;

use App\Enums\ReportStatus;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentReports extends TableWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 2,
        'xl' => 12,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->heading('Laporan terbaru')
            ->query(fn (): Builder => Report::query()
                ->select(['id', 'public_code', 'status', 'business_name', 'regency', 'created_at'])
                ->latest()
                ->limit(6))
            ->headerActions([
                Action::make('viewAll')
                    ->label('Lihat semua')
                    ->icon('heroicon-m-arrow-right')
                    ->color('gray')
                    ->url(ReportResource::getUrl('index')),
            ])
            ->columns([
                TextColumn::make('public_code')
                    ->label('Laporan')
                    ->description(fn (Report $record): string => $record->business_name ?: 'Tempat/usaha tidak disebutkan')
                    ->icon('heroicon-m-document-text')
                    ->iconColor('primary')
                    ->weight('bold')
                    ->wrap()
                    ->copyable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (ReportStatus $state): string => $this->statusLabel($state))
                    ->badge(),
                TextColumn::make('regency')
                    ->label('Wilayah')
                    ->icon('heroicon-m-map-pin')
                    ->iconColor('gray')
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->icon('heroicon-m-clock')
                    ->iconColor('gray')
                    ->since()
                    ->visibleFrom('lg')
                    ->dateTimeTooltip('d M Y, H:i'),
            ])
            ->recordUrl(fn (Report $record): string => ReportResource::getUrl('view', ['record' => $record]))
            ->recordClasses('dashboard-recent-report')
            ->stackedOnMobile()
            ->emptyStateHeading('Belum ada laporan')
            ->emptyStateIcon('heroicon-o-document-text')
            ->paginated(false);
    }

    private function statusLabel(ReportStatus $status): string
    {
        return match ($status) {
            ReportStatus::Submitted => 'Baru',
            ReportStatus::Received => 'Diterima',
            ReportStatus::Coordination => 'Koordinasi',
            ReportStatus::FieldAction => 'Ke lapangan',
            ReportStatus::ResultReport => 'Hasil',
            ReportStatus::Completed => 'Selesai',
        };
    }
}
