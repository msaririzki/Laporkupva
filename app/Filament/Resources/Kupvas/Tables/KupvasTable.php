<?php

namespace App\Filament\Resources\Kupvas\Tables;

use App\Models\Kupva;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
            ->columns([
                TextColumn::make('name')->label('Nama usaha')->searchable()->sortable()->weight('bold'),
                TextColumn::make('license_number')->label('Nomor izin')->placeholder('-')->searchable()->copyable(),
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
                }),
                TextColumn::make('regency')->label('Wilayah')->searchable()->sortable(),
                TextColumn::make('license_expires_at')->label('Berlaku sampai')->date('d M Y')->placeholder('-')->sortable(),
                IconColumn::make('is_active')->label('Beroperasi')->boolean(),
            ])
            ->filters([
                SelectFilter::make('license_status')->label('Status izin')->options([
                    'active' => 'Aktif',
                    'expired' => 'Kedaluwarsa',
                    'suspended' => 'Dibekukan',
                ]),
                SelectFilter::make('regency')->label('Wilayah')->options(fn (): array => Kupva::query()->distinct()->orderBy('regency')->pluck('regency', 'regency')->all()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->emptyStateHeading('Data KUPVA belum tersedia')
            ->emptyStateDescription('Tambahkan data KUPVA untuk melengkapi referensi pengawasan.')
            ->emptyStateIcon('heroicon-o-building-office-2');
    }
}
