<?php

namespace App\Http\Controllers;

use App\Models\Kupva;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KupvaCsvController extends Controller
{
    /** @var list<string> */
    private const HEADERS = [
        'ID',
        'Nama Usaha',
        'Nomor Izin',
        'Status Izin',
        'Kabupaten/Kota',
        'Kecamatan',
        'Desa/Kelurahan',
        'Alamat',
        'Latitude',
        'Longitude',
        'Berlaku Sampai',
        'Beroperasi',
    ];

    public function export(Request $request): StreamedResponse
    {
        $this->authorizeAdmin($request);

        return response()->streamDownload(function (): void {
            $stream = fopen('php://output', 'wb');

            if ($stream === false) {
                return;
            }

            $this->writeHeader($stream);

            Kupva::query()
                ->orderBy('name')
                ->lazy(500)
                ->each(function (Kupva $kupva) use ($stream): void {
                    fputcsv($stream, [
                        $kupva->id,
                        $this->safeText($kupva->name),
                        $this->safeText($kupva->license_number),
                        match ($kupva->license_status) {
                            'active' => 'Aktif',
                            'expired' => 'Kedaluwarsa',
                            'suspended' => 'Dibekukan',
                            default => $kupva->license_status,
                        },
                        $this->safeText($kupva->regency),
                        $this->safeText($kupva->district),
                        $this->safeText($kupva->village),
                        $this->safeText($kupva->address),
                        $kupva->latitude,
                        $kupva->longitude,
                        $kupva->license_expires_at?->format('Y-m-d'),
                        $kupva->is_active ? 'Ya' : 'Tidak',
                    ], ',', '"', '');
                });

            fclose($stream);
        }, 'data-kupva-'.now()->format('Y-m-d').'.csv', $this->downloadHeaders());
    }

    public function template(Request $request): StreamedResponse
    {
        $this->authorizeAdmin($request);

        return response()->streamDownload(function (): void {
            $stream = fopen('php://output', 'wb');

            if ($stream === false) {
                return;
            }

            $this->writeHeader($stream);
            fclose($stream);
        }, 'template-impor-kupva.csv', $this->downloadHeaders());
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->canAccessPanel(Filament::getPanel('admin')) === true, 403);
    }

    /** @param resource $stream */
    private function writeHeader(mixed $stream): void
    {
        fwrite($stream, "\xEF\xBB\xBF");
        fputcsv($stream, self::HEADERS, ',', '"', '');
    }

    /** @return array<string, string> */
    private function downloadHeaders(): array
    {
        return [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'X-Content-Type-Options' => 'nosniff',
        ];
    }

    private function safeText(?string $value): string
    {
        $value ??= '';

        return preg_match('/^\s*[=+\-@]/u', $value) === 1 ? "'{$value}" : $value;
    }
}
