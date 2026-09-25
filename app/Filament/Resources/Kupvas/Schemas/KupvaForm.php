<?php

namespace App\Filament\Resources\Kupvas\Schemas;

use App\Enums\NtbRegency;
use App\Rules\NoHtml;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KupvaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas KUPVA')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->label('Nama usaha')->required()->maxLength(255)->rule(new NoHtml),
                        TextInput::make('license_number')->label('Nomor izin')->unique(ignoreRecord: true)->maxLength(255)->rule(new NoHtml),
                        Select::make('license_status')->label('Status izin')->options([
                            'active' => 'Aktif',
                            'expired' => 'Kedaluwarsa',
                            'suspended' => 'Dibekukan',
                        ])->default('active')->required(),
                        DatePicker::make('license_expires_at')->label('Berlaku sampai'),
                        Toggle::make('is_active')->label('Aktif beroperasi')->default(true)->required(),
                    ]),
                Section::make('Lokasi')
                    ->columns(2)
                    ->schema([
                        Select::make('regency')->label('Kabupaten/kota')->options(NtbRegency::class)->enum(NtbRegency::class)->searchable()->required(),
                        TextInput::make('district')->label('Kecamatan')->maxLength(120)->rule(new NoHtml),
                        TextInput::make('village')->label('Desa/kelurahan')->maxLength(120)->rule(new NoHtml),
                        Textarea::make('address')->label('Alamat')->rows(3)->rule(new NoHtml)->columnSpanFull(),
                        TextInput::make('latitude')->label('Latitude')->numeric()->minValue(-11)->maxValue(-8),
                        TextInput::make('longitude')->label('Longitude')->numeric()->minValue(115)->maxValue(120),
                    ]),
            ]);
    }
}
