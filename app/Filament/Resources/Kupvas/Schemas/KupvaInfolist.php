<?php

namespace App\Filament\Resources\Kupvas\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KupvaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi KUPVA')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name')->label('Nama usaha')->weight('bold'),
                        TextEntry::make('license_number')->label('Nomor izin')->placeholder('-')->copyable(),
                        TextEntry::make('license_status')->label('Status izin')->badge()->formatStateUsing(fn (string $state): string => match ($state) {
                            'active' => 'Aktif',
                            'expired' => 'Kedaluwarsa',
                            'suspended' => 'Dibekukan',
                            default => $state,
                        }),
                        TextEntry::make('license_expires_at')->label('Berlaku sampai')->date('d F Y')->placeholder('-'),
                        IconEntry::make('is_active')->label('Aktif beroperasi')->boolean(),
                    ]),
                Section::make('Lokasi')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('regency')->label('Kabupaten/kota'),
                        TextEntry::make('district')->label('Kecamatan')->placeholder('-'),
                        TextEntry::make('village')->label('Desa/kelurahan')->placeholder('-'),
                        TextEntry::make('address')->label('Alamat')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('latitude')->label('Latitude')->placeholder('-'),
                        TextEntry::make('longitude')->label('Longitude')->placeholder('-'),
                    ]),
            ]);
    }
}
