<?php

namespace App\Http\Controllers;

use App\Models\Kupva;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use OpenSpout\Common\Entity\Cell\StringCell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\Common\Entity\Sheet;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KupvaCsvController extends Controller
{
    /** @var list<string> */
    private const EXPORT_HEADERS = [
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
        'Jenis Kantor',
    ];

    /** @var list<string> */
    private const TEMPLATE_HEADERS = [
        'Nama Usaha',
        'Nomor Izin',
        'Kabupaten/Kota',
        'Kecamatan',
        'Desa/Kelurahan',
        'Alamat',
        'Berlaku Sampai',
        'Jenis Kantor',
    ];

    public function export(Request $request): StreamedResponse
    {
        $this->authorizeAdmin($request);

        return response()->streamDownload(function (): void {
            $writer = $this->makeWriter();
            $writer->openToFile('php://output');
            $sheet = $writer->getCurrentSheet();
            $sheet->setName('Data KUPVA');
            $this->configureDataSheet($sheet, self::EXPORT_HEADERS);
            $writer->addRow($this->textRow(self::EXPORT_HEADERS, $this->headerStyle()));

            Kupva::query()
                ->orderBy('name')
                ->lazy(500)
                ->each(function (Kupva $kupva) use ($writer): void {
                    $writer->addRow($this->textRow([
                        $kupva->name,
                        $kupva->license_number,
                        match ($kupva->license_status) {
                            'active' => 'Aktif',
                            'expired' => 'Kedaluwarsa',
                            'suspended' => 'Dibekukan',
                            default => $kupva->license_status,
                        },
                        $kupva->regency,
                        $kupva->district,
                        $kupva->village,
                        $kupva->address,
                        $kupva->latitude,
                        $kupva->longitude,
                        $kupva->license_expires_at?->format('Y-m-d'),
                        $kupva->is_active ? 'Ya' : 'Tidak',
                        $kupva->office_type,
                    ]));
                });

            $writer->close();
        }, 'data-kupva-'.now()->format('Y-m-d').'.xlsx', $this->downloadHeaders());
    }

    public function template(Request $request): StreamedResponse
    {
        $this->authorizeAdmin($request);

        return response()->streamDownload(function (): void {
            $writer = $this->makeWriter();
            $writer->openToFile('php://output');

            $dataSheet = $writer->getCurrentSheet();
            $dataSheet->setName('Data KUPVA');
            $this->configureDataSheet($dataSheet, self::TEMPLATE_HEADERS);
            $writer->addRow($this->textRow(self::TEMPLATE_HEADERS, $this->headerStyle()));

            $guideSheet = $writer->addNewSheetAndMakeItCurrent();
            $guideSheet->setName('Petunjuk');
            $guideSheet->setColumnWidth(24, 1);
            $guideSheet->setColumnWidth(78, 2);
            $writer->addRow($this->textRow(['Petunjuk pengisian template KUPVA', ''], $this->titleStyle()));
            $writer->addRow($this->textRow(['Kolom', 'Cara mengisi'], $this->headerStyle()));

            foreach ($this->templateInstructions() as $instruction) {
                $writer->addRow($this->textRow($instruction, $this->bodyStyle()));
            }

            $writer->close();
        }, 'template-impor-kupva.xlsx', $this->downloadHeaders());
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->canAccessPanel(Filament::getPanel('admin')) === true, 403);
    }

    private function makeWriter(): Writer
    {
        $writer = new Writer;
        $writer->setCreator('TAMBORA - Bank Indonesia NTB');

        return $writer;
    }

    /** @param list<string> $headers */
    private function configureDataSheet(Sheet $sheet, array $headers): void
    {
        $columnWidths = [
            'Nama Usaha' => 30,
            'Nomor Izin' => 24,
            'Status Izin' => 18,
            'Kabupaten/Kota' => 28,
            'Kecamatan' => 22,
            'Desa/Kelurahan' => 24,
            'Alamat' => 42,
            'Latitude' => 16,
            'Longitude' => 16,
            'Berlaku Sampai' => 18,
            'Beroperasi' => 18,
            'Jenis Kantor' => 18,
        ];

        foreach ($headers as $columnIndex => $header) {
            $sheet->setColumnWidth($columnWidths[$header], $columnIndex + 1);
        }
    }

    private function headerStyle(): Style
    {
        return (new Style)
            ->setFontBold()
            ->setFontColor(Color::WHITE)
            ->setBackgroundColor('1D4ED8')
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER)
            ->setShouldWrapText();
    }

    private function titleStyle(): Style
    {
        return (new Style)
            ->setFontBold()
            ->setFontSize(14)
            ->setFontColor('172B4A')
            ->setBackgroundColor('DBEAFE');
    }

    private function bodyStyle(): Style
    {
        return (new Style)
            ->setFontColor('334155')
            ->setCellVerticalAlignment(CellVerticalAlignment::TOP)
            ->setShouldWrapText();
    }

    /**
     * @param  list<mixed>  $values
     */
    private function textRow(array $values, ?Style $style = null): Row
    {
        return new Row(array_map(
            fn (mixed $value): StringCell => new StringCell((string) ($value ?? ''), $style),
            $values,
        ));
    }

    /** @return list<array{string, string}> */
    private function templateInstructions(): array
    {
        return [
            ['Nama Usaha', 'Wajib. Tulis nama resmi penyelenggara KUPVA.'],
            ['Nomor Izin', 'Isi jika tersedia dan harus unik. Jika kosong, alamat wajib diisi; data dicocokkan berdasarkan nama, alamat, dan jenis kantor.'],
            ['Kabupaten/Kota', 'Wajib. Gunakan nama lengkap salah satu dari 10 kabupaten/kota di NTB.'],
            ['Kecamatan & Desa/Kelurahan', 'Isi sesuai alamat resmi agar data mudah ditemukan masyarakat.'],
            ['Alamat', 'Wajib jika nomor izin kosong. Tulis alamat atau patokan lokasi yang mudah dikenali.'],
            ['Jenis Kantor', 'Opsional. Isi KP untuk kantor pusat atau KC untuk kantor cabang.'],
            ['Berlaku Sampai', 'Opsional. Gunakan format YYYY-MM-DD, contoh 2027-12-31.'],
            ['Status otomatis', 'Data baru otomatis disimpan dengan status izin Aktif dan Beroperasi. Status dapat diubah dari halaman edit.'],
            ['Sebelum impor', 'Sistem akan menampilkan data baru, perubahan, data yang sama, dan duplikat untuk diperiksa sebelum disimpan.'],
            ['Berkas BI', 'Data KUPVA BB di NTB dari BI dapat diunggah langsung. Kabupaten/kota dikenali dari alamat. Tanggal teks BI menggunakan MM/DD/YYYY. Nomor telepon tidak diimpor.'],
        ];
    }

    /** @return array<string, string> */
    private function downloadHeaders(): array
    {
        return [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ];
    }
}
