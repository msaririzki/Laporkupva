<?php

namespace App\Filament\Resources\Reports\Schemas;

use App\Models\Report;
use App\Models\ReportEvidence;
use Filament\Actions\Action;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View;

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
                                ->headerActions([
                                    Action::make('openGoogleMaps')
                                        ->label('Buka di Google Maps')
                                        ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                                        ->button()
                                        ->url(fn (Report $record): string => self::googleMapsDirectionsUrl($record))
                                        ->openUrlInNewTab(),
                                ])
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
                                        ->grid([
                                            'default' => 1,
                                            'md' => 2,
                                            'xl' => 3,
                                        ])
                                        ->columns([
                                            'default' => 1,
                                            'sm' => 2,
                                        ])
                                        ->schema([
                                            ImageEntry::make('preview_url')
                                                ->hiddenLabel()
                                                ->state(fn (ReportEvidence $record): string => route('admin.report-evidence.preview', $record))
                                                ->visible(fn (ReportEvidence $record): bool => self::isPreviewableImage($record))
                                                ->action(self::previewAction('previewSubmissionEvidenceImage'))
                                                ->alt(fn (ReportEvidence $record): string => 'Pratinjau '.$record->original_name)
                                                ->imageHeight('220px')
                                                ->extraImgAttributes([
                                                    'class' => 'w-full rounded-xl bg-gray-950 object-contain p-2',
                                                    'loading' => 'lazy',
                                                ])
                                                ->columnSpanFull(),
                                            TextEntry::make('original_name')
                                                ->label('Nama berkas')
                                                ->icon(fn (ReportEvidence $record): ?Heroicon => self::isPreviewableImage($record)
                                                    ? null
                                                    : Heroicon::OutlinedArrowDownTray)
                                                ->url(fn (ReportEvidence $record): ?string => self::isPreviewableImage($record)
                                                    ? null
                                                    : route('admin.report-evidence.download', $record))
                                                ->columnSpanFull(),
                                            TextEntry::make('preview_hint')
                                                ->hiddenLabel()
                                                ->state('Klik foto untuk memperbesar')
                                                ->icon(Heroicon::OutlinedArrowsPointingOut)
                                                ->color('primary')
                                                ->visible(fn (ReportEvidence $record): bool => self::isPreviewableImage($record))
                                                ->action(self::previewAction('previewSubmissionEvidence'))
                                                ->columnSpanFull(),
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
                                                ->action(self::previewAction('previewActivityEvidence'))
                                                ->alt(fn (ReportEvidence $record): string => 'Pratinjau '.$record->original_name)
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
                                ->description('Tanggapi informasi tambahan dari pelapor.')
                                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                                ->extraAttributes(['id' => 'komunikasi-anonim'])
                                ->schema([
                                    Livewire::make('admin.report-conversation')
                                        ->key(fn (?Report $record): string => 'report-conversation-'.$record?->getKey()),
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

    private static function googleMapsDirectionsUrl(Report $report): string
    {
        return 'https://www.google.com/maps/dir/?'.http_build_query([
            'api' => 1,
            'destination' => "{$report->latitude},{$report->longitude}",
            'travelmode' => 'driving',
        ], encoding_type: PHP_QUERY_RFC3986);
    }

    private static function previewAction(string $name): Action
    {
        return Action::make($name)
            ->modalHeading(fn (ReportEvidence $record): string => 'Pratinjau '.$record->original_name)
            ->modalDescription('Foto ditampilkan langsung tanpa meninggalkan halaman laporan.')
            ->modalContent(fn (ReportEvidence $record): View => view(
                'filament.reports.evidence-preview',
                ['reportEvidence' => $record],
            ))
            ->modalWidth(Width::ScreenTwoExtraLarge)
            ->extraModalWindowAttributes(['class' => 'tambora-image-preview-modal'])
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup');
    }

    private static function isPreviewableImage(ReportEvidence $reportEvidence): bool
    {
        return in_array($reportEvidence->mime_type, [
            'image/jpeg',
            'image/png',
            'image/webp',
        ], true);
    }
}
