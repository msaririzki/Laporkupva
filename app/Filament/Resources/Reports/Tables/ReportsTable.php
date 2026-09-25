<?php

namespace App\Filament\Resources\Reports\Tables;

use App\Enums\ReportStatus;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReportsTable
{
    /** @var array<string, string> */
    private const INCIDENT_TYPES = [
        'kupva_tanpa_izin' => 'KUPVA tanpa izin',
        'transaksi_mencurigakan' => 'Transaksi mencurigakan',
        'pelanggaran_kurs' => 'Pelanggaran kurs',
        'penolakan_rupiah' => 'Penolakan Rupiah',
        'lainnya' => 'Lainnya',
    ];

    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('public_code')
                    ->label('Kode laporan')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->description(fn (Report $record): string => $record->created_at->diffForHumans()),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('incident_type')
                    ->label('Jenis laporan')
                    ->formatStateUsing(fn (string $state): string => self::INCIDENT_TYPES[$state] ?? $state)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('business_name')
                    ->label('Tempat / jenis laporan')
                    ->placeholder('Tidak disebutkan')
                    ->searchable()
                    ->limit(36)
                    ->wrap()
                    ->visibleFrom('md')
                    ->description(fn (Report $record): string => self::INCIDENT_TYPES[$record->incident_type] ?? $record->incident_type),
                TextColumn::make('regency')
                    ->label('Wilayah')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->visibleFrom('lg'),
                IconColumn::make('is_ongoing')
                    ->label('Berlangsung')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->since()
                    ->dateTimeTooltip('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(ReportStatus::class),
                SelectFilter::make('incident_type')
                    ->label('Jenis laporan')
                    ->options(self::INCIDENT_TYPES),
                SelectFilter::make('regency')
                    ->label('Wilayah')
                    ->options(fn (): array => Report::query()->distinct()->orderBy('regency')->pluck('regency', 'regency')->all()),
            ])
            ->toolbarActions([
                Action::make('exportCsv')
                    ->label('Ekspor CSV')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('gray')
                    ->url(fn (): string => route('admin.reports.export')),
            ])
            ->recordActions([
                ViewAction::make()->iconButton()->tooltip('Lihat detail laporan'),
                ReportResource::advanceStatusAction()->iconButton()->tooltip('Lanjutkan ke tahap berikutnya'),
            ])
            ->emptyStateHeading('Belum ada laporan masyarakat')
            ->emptyStateDescription('Laporan yang masuk melalui portal publik akan tampil di halaman ini.')
            ->emptyStateIcon('heroicon-o-inbox');
    }
}
