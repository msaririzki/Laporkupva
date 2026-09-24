<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    /** @var array<string, string> */
    private const INCIDENT_TYPES = [
        'kupva_tanpa_izin' => 'Dugaan KUPVA tanpa izin',
        'transaksi_mencurigakan' => 'Transaksi penukaran mencurigakan',
        'pelanggaran_kurs' => 'Informasi kurs tidak wajar/tidak transparan',
        'penolakan_rupiah' => 'Penolakan penggunaan Rupiah',
        'lainnya' => 'Lainnya terkait penukaran valuta asing',
    ];

    public function __invoke(Request $request): StreamedResponse
    {
        abort_unless($request->user()?->canAccessPanel(Filament::getPanel('admin')) === true, 403);

        $filename = 'laporan-tambora-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function (): void {
            $stream = fopen('php://output', 'wb');

            if ($stream === false) {
                return;
            }

            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, [
                'Kode Laporan',
                'Tanggal Laporan',
                'Jenis Laporan',
                'Nama Tempat/Usaha',
                'Kabupaten/Kota',
                'Kecamatan',
                'Desa/Kelurahan',
                'Alamat/Patokan',
                'Latitude',
                'Longitude',
                'Status',
                'Masih Berlangsung',
                'Pembaruan Terakhir',
                'Pembaruan Publik',
                'Catatan Internal',
            ], ',', '"', '');

            Report::query()
                ->select([
                    'id',
                    'public_code',
                    'created_at',
                    'incident_type',
                    'business_name',
                    'regency',
                    'district',
                    'village',
                    'address',
                    'latitude',
                    'longitude',
                    'status',
                    'is_ongoing',
                    'updated_at',
                    'public_update',
                    'internal_notes',
                ])
                ->lazyById(500)
                ->each(function (Report $report) use ($stream): void {
                    fputcsv($stream, [
                        $report->public_code,
                        $report->created_at->format('Y-m-d H:i:s'),
                        self::INCIDENT_TYPES[$report->incident_type] ?? $report->incident_type,
                        $this->safeText($report->business_name),
                        $this->safeText($report->regency),
                        $this->safeText($report->district),
                        $this->safeText($report->village),
                        $this->safeText($report->address),
                        $report->latitude,
                        $report->longitude,
                        $report->status->label(),
                        $report->is_ongoing ? 'Ya' : 'Tidak',
                        $report->updated_at->format('Y-m-d H:i:s'),
                        $this->safeText($report->public_update),
                        $this->safeText($report->internal_notes),
                    ], ',', '"', '');
                });

            fclose($stream);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function safeText(?string $value): string
    {
        $value ??= '';

        if (preg_match('/^\s*[=+\-@]/u', $value) === 1) {
            return "'{$value}";
        }

        return $value;
    }
}
