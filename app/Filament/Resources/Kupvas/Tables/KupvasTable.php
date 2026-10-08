<?php

namespace App\Filament\Resources\Kupvas\Tables;

use App\Enums\NtbRegency;
use App\Filament\Resources\Kupvas\KupvaLocationAction;
use App\Models\Kupva;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KupvasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->stackedOnMobile()
            ->recordClasses('kupva-list-row')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama usaha')
                    ->searchable()
                    ->weight('bold')
                    ->limit(42)
                    ->wrap()
                    ->extraCellAttributes(['class' => 'kupva-list-cell kupva-list-cell-name'])
                    ->description(fn (Kupva $record): string => $record->license_number ?: 'Nomor izin belum tersedia'),
                TextColumn::make('office_type')
                    ->label('KP/KC')
                    ->placeholder('-')
                    ->badge(),
                TextColumn::make('location_source')
                    ->label('Titik peta')
                    ->state(fn (Kupva $record): string => $record->latitude === null || $record->longitude === null ? 'missing' : ($record->location_source ?? 'unknown'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'manual' => 'Sudah diperiksa',
                        'nominatim_area' => 'Perkiraan area',
                        'nominatim' => 'Perkiraan titik',
                        'missing' => 'Belum ada titik',
                        default => 'Belum diperiksa',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'manual' => 'success',
                        'nominatim_area' => 'warning',
                        'nominatim' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('license_number')
                    ->label('Nomor izin')
                    ->placeholder('-')
                    ->searchable()
                    ->copyable()
                    ->alignCenter()
                    ->extraCellAttributes(['class' => 'kupva-list-cell'])
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('license_status')->label('Status izin')->badge()->formatStateUsing(fn (string $state): string => match ($state) {
                    'active' => 'Aktif',
                    'expired' => 'Kedaluwarsa',
                    'suspended' => 'Dibekukan',
                    default => $state,
                })->color(fn (string $state): string => match ($state) {
                    'active' => 'success',
                    'expired' => 'danger',
                    'suspended' => 'warning',
                    default => 'gray',
                })->extraCellAttributes(['class' => 'kupva-list-cell']),
                TextColumn::make('regency')
                    ->label('Kabupaten/kota')
                    ->searchable()
                    ->wrap()
                    ->alignCenter()
                    ->visibleFrom('md')
                    ->extraCellAttributes(['class' => 'kupva-list-cell']),
                TextColumn::make('district')
                    ->label('Kecamatan')
                    ->placeholder('-')
                    ->searchable()
                    ->wrap()
                    ->alignCenter()
                    ->visibleFrom('lg')
                    ->toggleable()
                    ->extraCellAttributes(['class' => 'kupva-list-cell']),
                TextColumn::make('village')
                    ->label('Desa/kelurahan')
                    ->placeholder('-')
                    ->searchable()
                    ->wrap()
                    ->alignCenter()
                    ->visibleFrom('xl')
                    ->toggleable()
                    ->extraCellAttributes(['class' => 'kupva-list-cell']),
                TextColumn::make('license_expires_at')
                    ->label('Berlaku sampai')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->alignCenter()
                    ->visibleFrom('xl')
                    ->extraCellAttributes(['class' => 'kupva-list-cell']),
                IconColumn::make('is_active')
                    ->label('Beroperasi')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->extraCellAttributes(['class' => 'kupva-list-cell']),
            ])
            ->filters([
                SelectFilter::make('license_status')
                    ->label('Status izin')
                    ->native(false)
                    ->options([
                        'active' => 'Aktif',
                        'expired' => 'Kedaluwarsa',
                        'suspended' => 'Dibekukan',
                    ]),
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
            ->recordActions([
                KupvaLocationAction::make()->iconButton()->tooltip('Koreksi lokasi di peta'),
                ViewAction::make()->iconButton()->tooltip('Lihat detail KUPVA'),
                EditAction::make()->iconButton()->tooltip('Ubah data KUPVA'),
            ])
            ->emptyStateHeading('Data KUPVA belum tersedia')
            ->emptyStateDescription('Tambahkan data KUPVA untuk melengkapi referensi pengawasan.')
            ->emptyStateIcon('heroicon-o-building-office-2');
    }
}
