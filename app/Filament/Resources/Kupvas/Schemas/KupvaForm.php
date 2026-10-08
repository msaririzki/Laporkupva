<?php

namespace App\Filament\Resources\Kupvas\Schemas;

use App\Enums\NtbRegency;
use App\Rules\NoHtml;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class KupvaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data KUPVA')
                    ->description('Isi identitas dan lokasi utama. Status awal otomatis aktif dan beroperasi.')
                    ->icon(Heroicon::OutlinedBuildingOffice2)
                    ->iconColor('primary')
                    ->extraAttributes(['class' => 'kupva-form-section kupva-identity-section'])
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama usaha')
                            ->placeholder('Contoh: PT Valas Nusantara')
                            ->prefixIcon(Heroicon::OutlinedBuildingOffice)
                            ->autocomplete('organization')
                            ->required()
                            ->maxLength(255)
                            ->rule(new NoHtml),
                        TextInput::make('license_number')
                            ->label('Nomor izin')
                            ->placeholder('Contoh: KEP-123/BI/2026')
                            ->prefixIcon(Heroicon::OutlinedIdentification)
                            ->helperText('Boleh kosong jika nomor izin belum tersedia pada data BI.')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->rule(new NoHtml),
                        Select::make('office_type')
                            ->label('Jenis kantor')
                            ->options(['KP' => 'Kantor pusat (KP)', 'KC' => 'Kantor cabang (KC)'])
                            ->in(['KP', 'KC']),
                        Select::make('regency')
                            ->label('Kabupaten/kota')
                            ->placeholder('Pilih wilayah')
                            ->options(NtbRegency::class)
                            ->enum(NtbRegency::class)
                            ->prefixIcon(Heroicon::OutlinedMapPin)
                            ->searchable()
                            ->required(),
                        TextInput::make('district')
                            ->label('Kecamatan')
                            ->placeholder('Nama kecamatan')
                            ->maxLength(120)
                            ->rule(new NoHtml),
                        TextInput::make('village')
                            ->label('Desa/kelurahan')
                            ->placeholder('Nama desa atau kelurahan')
                            ->maxLength(120)
                            ->rule(new NoHtml),
                        Textarea::make('address')
                            ->label('Alamat lengkap')
                            ->placeholder('Nama jalan, nomor bangunan, dan patokan lokasi')
                            ->helperText('Tuliskan patokan jika lokasi usaha sulit ditemukan.')
                            ->rows(3)
                            ->rule(new NoHtml)
                            ->columnSpanFull(),
                    ]),
                Section::make('Status dan masa berlaku')
                    ->description('Ubah bagian ini hanya jika status KUPVA perlu diperbarui.')
                    ->icon(Heroicon::OutlinedCheckBadge)
                    ->iconColor('primary')
                    ->extraAttributes(['class' => 'kupva-form-section kupva-status-section'])
                    ->columnSpanFull()
                    ->hiddenOn('create')
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        ToggleButtons::make('license_status')
                            ->label('Status izin')
                            ->options([
                                'active' => 'Aktif',
                                'expired' => 'Kedaluwarsa',
                                'suspended' => 'Dibekukan',
                            ])
                            ->colors([
                                'active' => 'success',
                                'expired' => 'warning',
                                'suspended' => 'danger',
                            ])
                            ->icons([
                                'active' => Heroicon::OutlinedCheckBadge,
                                'expired' => Heroicon::OutlinedCalendarDays,
                                'suspended' => Heroicon::OutlinedShieldExclamation,
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 3,
                            ])
                            ->required(),
                        DatePicker::make('license_expires_at')
                            ->label('Berlaku sampai')
                            ->placeholder('Pilih tanggal')
                            ->prefixIcon(Heroicon::OutlinedCalendarDays)
                            ->displayFormat('d M Y')
                            ->native(false)
                            ->closeOnDateSelection(),
                        ToggleButtons::make('is_active')
                            ->label('Status operasional')
                            ->options([
                                1 => 'Beroperasi',
                                0 => 'Tidak beroperasi',
                            ])
                            ->colors([
                                1 => 'success',
                                0 => 'danger',
                            ])
                            ->icons([
                                1 => Heroicon::OutlinedCheckCircle,
                                0 => Heroicon::OutlinedXCircle,
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                            ])
                            ->required()
                            ->extraFieldWrapperAttributes(['class' => 'kupva-operational-status'])
                            ->columnSpanFull(),
                    ]),
                Section::make('Koordinat peta (opsional)')
                    ->description('Buka bagian ini jika titik lokasi sudah diketahui.')
                    ->icon(Heroicon::OutlinedMap)
                    ->iconColor('gray')
                    ->extraAttributes(['class' => 'kupva-form-section kupva-coordinate-section'])
                    ->columnSpanFull()
                    ->hiddenOn('create')
                    ->collapsible()
                    ->collapsed()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->placeholder('-8.5830695')
                            ->inputMode('decimal')
                            ->numeric()
                            ->minValue(-11)
                            ->maxValue(-8)
                            ->helperText('Rentang wilayah NTB: -11 sampai -8.'),
                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->placeholder('116.1161800')
                            ->inputMode('decimal')
                            ->numeric()
                            ->minValue(115)
                            ->maxValue(120)
                            ->helperText('Rentang wilayah NTB: 115 sampai 120.'),
                        Select::make('location_source')
                            ->label('Ketepatan titik lokasi')
                            ->options(['nominatim' => 'Perkiraan dari alamat', 'manual' => 'Titik telah diperiksa petugas'])
                            ->in(['nominatim', 'manual'])
                            ->helperText('Pilih titik telah diperiksa setelah memastikan koordinat sesuai lokasi usaha.')
                            ->columnSpanFull(),
                        Textarea::make('location_match_address')
                            ->label('Alamat hasil pencarian peta')
                            ->disabled()
                            ->dehydrated(false)
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
