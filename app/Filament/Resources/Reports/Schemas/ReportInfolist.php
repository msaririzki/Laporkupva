<?php

namespace App\Filament\Resources\Reports\Schemas;

use App\Models\Report;
use App\Models\ReportEvidence;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
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
                SchemaView::make('filament.schemas.components.report-status-progress')
                    ->columnSpanFull(),
                Grid::make([
                    'default' => 1,
                    '@5xl' => 12,
                ])
                    ->gridContainer()
                    ->schema([
                        Group::make([
                            Section::make('Ringkasan laporan')
                                ->description('Informasi utama dan kronologi yang dikirim oleh pelapor anonim.')
                                ->icon(Heroicon::OutlinedDocumentText)
                                ->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                ])
                                ->schema([
                                    TextEntry::make('public_code')
                                        ->label('Kode laporan')
                                        ->copyable()
                                        ->weight('bold'),
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
                                        ->label('Lampiran dari pelapor')
                                        ->state(fn (Report $record): string => $record->submissionEvidence()->count().' berkas'),
                                    TextEntry::make('description')
                                        ->label('Kronologi')
                                        ->prose()
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Lokasi terlapor')
                                ->description('Pastikan titik peta sesuai dengan petunjuk lokasi sebelum koordinasi lapangan.')
                                ->icon(Heroicon::OutlinedMapPin)
                                ->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                ])
                                ->schema([
                                    TextEntry::make('regency')->label('Kabupaten/kota'),
                                    TextEntry::make('district')->label('Kecamatan')->placeholder('-'),
                                    TextEntry::make('village')->label('Desa/kelurahan')->placeholder('-'),
                                    TextEntry::make('coordinates')
                                        ->label('Koordinat')
                                        ->state(fn (Report $record): string => "{$record->latitude}, {$record->longitude}")
                                        ->copyable(),
                                    TextEntry::make('address')
                                        ->label('Petunjuk alamat')
                                        ->placeholder('-')
                                        ->columnSpanFull(),
                                    SchemaView::make('filament.schemas.components.report-location-map')
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Lampiran dari pelapor')
                                ->description('Berkas awal yang dikirim masyarakat. Tersimpan privat dan hanya dapat diakses Admin TAMBORA.')
                                ->icon(Heroicon::OutlinedPaperClip)
                                ->visible(fn (Report $record): bool => $record->submissionEvidence()->exists())
                                ->collapsible()
                                ->schema([
                                    RepeatableEntry::make('submissionEvidence')
                                        ->hiddenLabel()
                                        ->columns([
                                            'default' => 1,
                                            'sm' => 3,
                                        ])
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
                            Section::make('Bukti kegiatan petugas')
                                ->description('Dokumentasi internal pada setiap tahap penanganan. Foto tidak ditampilkan kepada pelapor atau pengunjung umum.')
                                ->icon(Heroicon::OutlinedPhoto)
                                ->visible(fn (Report $record): bool => $record->activityEvidence()->exists())
                                ->collapsible()
                                ->schema([
                                    RepeatableEntry::make('activityEvidence')
                                        ->hiddenLabel()
                                        ->grid([
                                            'default' => 1,
                                            'lg' => 2,
                                        ])
                                        ->columns([
                                            'default' => 1,
                                            'sm' => 2,
                                        ])
                                        ->schema([
                                            ImageEntry::make('preview_url')
                                                ->hiddenLabel()
                                                ->state(fn (ReportEvidence $record): string => route('admin.report-evidence.preview', $record))
                                                ->url(fn (ReportEvidence $record): string => route('admin.report-evidence.download', $record))
                                                ->openUrlInNewTab()
                                                ->imageHeight('180px')
                                                ->extraImgAttributes([
                                                    'class' => 'w-full rounded-xl object-cover',
                                                    'loading' => 'lazy',
                                                ])
                                                ->columnSpanFull(),
                                            TextEntry::make('statusHistory.to_status')
                                                ->label('Tahap kegiatan')
                                                ->badge(),
                                            TextEntry::make('created_at')
                                                ->label('Diunggah')
                                                ->dateTime('d M Y, H:i'),
                                            TextEntry::make('uploadedBy.name')
                                                ->label('Petugas')
                                                ->placeholder('Admin TAMBORA'),
                                            TextEntry::make('original_name')
                                                ->label('Nama foto')
                                                ->limit(32),
                                            TextEntry::make('caption')
                                                ->label('Catatan kegiatan')
                                                ->placeholder('Tidak ada catatan tambahan')
                                                ->columnSpanFull(),
                                        ]),
                                ]),
                        ])->columnSpan([
                            'default' => 1,
                            '@5xl' => 7,
                        ]),
                        Group::make([
                            Section::make('Catatan penanganan')
                                ->description('Ringkasan terbaru untuk pelapor dan catatan kerja internal petugas.')
                                ->icon(Heroicon::OutlinedClipboardDocumentCheck)
                                ->schema([
                                    TextEntry::make('status')
                                        ->label('Tahap saat ini')
                                        ->badge(),
                                    TextEntry::make('public_update')
                                        ->label('Pembaruan untuk pelapor')
                                        ->placeholder('Belum ada pembaruan tambahan'),
                                    TextEntry::make('internal_notes')
                                        ->label('Catatan internal')
                                        ->placeholder('Belum ada catatan internal'),
                                ]),
                            Section::make('Riwayat penanganan')
                                ->description('Jejak setiap perubahan tahap laporan dari awal hingga selesai.')
                                ->icon(Heroicon::OutlinedClock)
                                ->schema([
                                    RepeatableEntry::make('statusHistories')
                                        ->hiddenLabel()
                                        ->placeholder('Belum ada riwayat perubahan status.')
                                        ->columns([
                                            'default' => 1,
                                            'sm' => 2,
                                        ])
                                        ->schema([
                                            TextEntry::make('to_status')
                                                ->label('Tahap')
                                                ->badge(),
                                            TextEntry::make('created_at')
                                                ->label('Waktu')
                                                ->dateTime('d M Y, H:i'),
                                            TextEntry::make('user.name')
                                                ->label('Petugas')
                                                ->placeholder('Sistem'),
                                            TextEntry::make('public_note')
                                                ->label('Pembaruan publik')
                                                ->placeholder('-')
                                                ->columnSpanFull(),
                                            TextEntry::make('internal_note')
                                                ->label('Catatan koreksi internal')
                                                ->placeholder('-')
                                                ->columnSpanFull(),
                                        ]),
                                ]),
                            Section::make('Komunikasi anonim')
                                ->description('Gunakan tombol Hubungi pelapor di atas untuk meminta informasi tambahan tanpa mengetahui identitasnya.')
                                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                                ->collapsible()
                                ->schema([
                                    RepeatableEntry::make('anonymousMessages')
                                        ->hiddenLabel()
                                        ->placeholder('Belum ada percakapan anonim pada laporan ini.')
                                        ->columns([
                                            'default' => 1,
                                            'sm' => 2,
                                        ])
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
                        ])->columnSpan([
                            'default' => 1,
                            '@5xl' => 5,
                        ]),
                    ])
                    ->columnSpanFull(),
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
