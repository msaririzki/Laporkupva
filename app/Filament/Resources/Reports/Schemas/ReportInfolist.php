<?php

namespace App\Filament\Resources\Reports\Schemas;

use App\Models\Report;
use App\Models\ReportEvidence;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

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
                        SchemaView::make('filament.schemas.components.report-location-map')
                            ->columnSpanFull(),
                    ]),
                Section::make('Lampiran bukti')
                    ->description('Berkas tersimpan privat dan hanya dapat diakses oleh Admin TAMBORA.')
                    ->visible(fn (Report $record): bool => $record->evidence()->exists())
                    ->schema([
                        RepeatableEntry::make('evidence')
                            ->hiddenLabel()
                            ->columns(3)
                            ->schema([
                                TextEntry::make('original_name')
                                    ->label('Nama berkas')
                                    ->icon(Heroicon::OutlinedArrowDownTray)
                                    ->url(fn (ReportEvidence $record): string => route('admin.report-evidence.download', $record)),
                                TextEntry::make('mime_type')
                                    ->label('Tipe berkas'),
                                TextEntry::make('size')
                                    ->label('Ukuran')
                                    ->formatStateUsing(fn (int $state): string => self::formatBytes($state)),
                            ]),
                    ]),
                Section::make('Komunikasi anonim')
                    ->description('Percakapan tidak menampilkan identitas pelapor. Gunakan tombol Kirim pesan untuk meminta informasi tambahan.')
                    ->schema([
                        RepeatableEntry::make('anonymousMessages')
                            ->hiddenLabel()
                            ->placeholder('Belum ada percakapan anonim pada laporan ini.')
                            ->columns(3)
                            ->schema([
                                TextEntry::make('sender_type')
                                    ->label('Pengirim')
                                    ->badge()
                                    ->color(fn (string $state): string => $state === 'admin' ? 'primary' : 'gray')
                                    ->formatStateUsing(fn (string $state): string => $state === 'admin' ? 'Petugas TAMBORA' : 'Pelapor anonim'),
                                TextEntry::make('created_at')
                                    ->label('Waktu')
                                    ->dateTime('d M Y, H:i'),
                                TextEntry::make('body')
                                    ->label('Pesan')
                                    ->columnSpanFull(),
                            ]),
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

    private static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1_048_576) {
            return number_format($bytes / 1_048_576, 1, ',', '.').' MB';
        }

        return number_format($bytes / 1024, 1, ',', '.').' KB';
    }
}
