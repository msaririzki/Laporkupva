<?php

namespace App\Filament\Resources\Reports\Schemas;

use App\Models\Report;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReportInfolist
{
    /** @var array<string, string> */
    private const INCIDENT_TYPES = [
        'kupva_tanpa_izin' => 'Dugaan KUPVA tanpa izin',
        'transaksi_mencurigakan' => 'Transaksi penukaran mencurigakan',
        'pelanggaran_kurs' => 'Informasi kurs tidak wajar/tidak transparan',
        'penolakan_rupiah' => 'Penolakan penggunaan Rupiah',
        'lainnya' => 'Lainnya terkait penukaran valuta asing',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ringkasan laporan')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('public_code')
                            ->label('Kode laporan')
                            ->copyable()
                            ->weight('bold'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge(),
                        TextEntry::make('created_at')
                            ->label('Dikirim pada')
                            ->dateTime('d M Y, H:i'),
                        TextEntry::make('incident_type')
                            ->label('Jenis laporan')
                            ->formatStateUsing(fn (string $state): string => self::INCIDENT_TYPES[$state] ?? $state),
                        TextEntry::make('business_name')
                            ->label('Nama tempat/usaha')
                            ->placeholder('Tidak disebutkan'),
                        IconEntry::make('is_ongoing')
                            ->label('Masih berlangsung')
                            ->boolean(),
                        TextEntry::make('incident_date')
                            ->label('Tanggal kejadian')
                            ->date('d F Y'),
                        TextEntry::make('incident_time')
                            ->label('Perkiraan waktu')
                            ->time('H:i')
                            ->placeholder('Tidak disebutkan'),
                        TextEntry::make('evidence_count')
                            ->label('Jumlah lampiran')
                            ->state(fn (Report $record): string => $record->evidence()->count().' berkas'),
                        TextEntry::make('description')
                            ->label('Kronologi')
                            ->prose()
                            ->columnSpanFull(),
                    ]),
                Section::make('Lokasi terlapor')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('regency')->label('Kabupaten/kota'),
                        TextEntry::make('district')->label('Kecamatan')->placeholder('-'),
                        TextEntry::make('village')->label('Desa/kelurahan')->placeholder('-'),
                        TextEntry::make('address')->label('Petunjuk alamat')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('coordinates')
                            ->label('Koordinat')
                            ->state(fn (Report $record): string => "{$record->latitude}, {$record->longitude}")
                            ->copyable(),
                    ]),
                Section::make('Catatan penanganan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('public_update')
                            ->label('Pembaruan untuk pelapor')
                            ->placeholder('Belum ada pembaruan tambahan'),
                        TextEntry::make('internal_notes')
                            ->label('Catatan internal')
                            ->placeholder('Belum ada catatan internal'),
                    ]),
            ]);
    }
}
