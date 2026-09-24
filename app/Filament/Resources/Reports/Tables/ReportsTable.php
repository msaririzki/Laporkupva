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
                    ->label('Kode')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('incident_type')
                    ->label('Jenis laporan')
                    ->formatStateUsing(fn (string $state): string => self::INCIDENT_TYPES[$state] ?? $state)
                    ->searchable(),
                TextColumn::make('business_name')
                    ->label('Tempat/usaha')
                    ->placeholder('Tidak disebutkan')
                    ->searchable()
                    ->limit(28),
                TextColumn::make('regency')
                    ->label('Wilayah')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_ongoing')
                    ->label('Berlangsung')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->since()
                    ->dateTimeTooltip('d M Y, H:i')
                    ->sortable(),
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
                ViewAction::make()->label('Detail'),
                ReportResource::advanceStatusAction()->iconButton()->tooltip('Lanjutkan ke tahap berikutnya'),
            ])
            ->emptyStateHeading('Belum ada laporan masyarakat')
            ->emptyStateDescription('Laporan yang masuk melalui portal publik akan tampil di halaman ini.')
            ->emptyStateIcon('heroicon-o-inbox');
    }
}
