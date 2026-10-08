<?php

namespace App\Filament\Resources\Reports\Tables;

use App\Enums\NtbRegency;
use App\Enums\ReportStatus;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReportsTable
{
    /** @var array<string, string> */
    private const INCIDENT_TYPES = [
        'kupva_tanpa_izin' => 'KUPVA tanpa izin',
        'transaksi_mencurigakan' => 'Kecurangan transaksi',
        'pelanggaran_kurs' => 'Pelanggaran kurs',
        'penolakan_rupiah' => 'Penolakan Rupiah',
        'lainnya' => 'Lainnya',
    ];

    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->searchPlaceholder('Cari laporan…')
            ->searchDebounce('350ms')
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
                TextColumn::make('approval_status')
                    ->label('Persetujuan')
                    ->state(fn (Report $record): ?string => $record->pendingProgressRequest ? 'Menunggu persetujuan' : ($record->latestProgressRequest?->status === 'rejected' ? 'Pengajuan ditolak' : null))
                    ->description(fn (Report $record): ?string => $record->pendingProgressRequest?->to_status->label())
                    ->badge()->color('warning')->wrap()
                    ->visible(fn (): bool => auth()->user()?->canManageApplication() === true),
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
                Filter::make('pending_approval')->label('Menunggu persetujuan Administrator')
                    ->query(fn (Builder $query): Builder => $query->whereHas('pendingProgressRequest'))
                    ->visible(fn (): bool => auth()->user()?->canManageApplication() === true),
                SelectFilter::make('status')
                    ->label('Status')
                    ->native(false)
                    ->multiple()
                    ->options(ReportStatus::class),
                SelectFilter::make('incident_type')
                    ->label('Jenis laporan')
                    ->native(false)
                    ->options(array_diff_key(self::INCIDENT_TYPES, ['pelanggaran_kurs' => true])),
                SelectFilter::make('regency')
                    ->label('Wilayah')
                    ->native(false)
                    ->options(NtbRegency::class),
            ])
            ->filtersTriggerAction(
                fn (Action $action): Action => $action
                    ->button()
                    ->label('Filter')
                    ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                    ->color('gray'),
            )
            ->columnManagerTriggerAction(
                fn (Action $action): Action => $action
                    ->button()
                    ->label('Atur')
                    ->icon(Heroicon::OutlinedViewColumns)
                    ->color('gray'),
            )
            ->toolbarActions([
                Action::make('exportCsv')
                    ->label('Unduh CSV')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('gray')
                    ->url(fn (): string => route('admin.reports.export')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Buka')
                    ->button()
                    ->size('sm')
                    ->color('gray'),
                ReportResource::advanceStatusAction()
                    ->label(fn (Report $record): string => $record->status->next()?->requiresApproval() ? 'Ajukan progres' : 'Lanjutkan')
                    ->button()
                    ->size('sm')
                    ->tooltip(fn (Report $record): string => $record->status->next()?->requiresApproval() ? 'Ajukan tahap berikutnya kepada Administrator' : 'Lanjutkan ke tahap berikutnya'),
                ReportResource::reviewProgressAction()->button()->size('sm'),
            ])
            ->recordActionsColumnLabel('Aksi')
            ->recordClasses(fn (Report $record): string => match ($record->status) {
                ReportStatus::Submitted => 'report-list-row report-list-row--new',
                ReportStatus::Completed => 'report-list-row report-list-row--completed',
                default => 'report-list-row report-list-row--active',
            })
            ->emptyStateHeading('Belum ada laporan masyarakat')
            ->emptyStateDescription('Laporan yang masuk melalui portal publik akan tampil di halaman ini.')
            ->emptyStateIcon('heroicon-o-inbox');
    }
}
