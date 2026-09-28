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
            $writer = $this->makeWriter();
            $writer->openToFile('php://output');
            $sheet = $writer->getCurrentSheet();
            $sheet->setName('Data KUPVA');
            $this->configureDataSheet($sheet);
            $writer->addRow($this->textRow(self::HEADERS, $this->headerStyle()));

            Kupva::query()
                ->orderBy('name')
                ->lazy(500)
                ->each(function (Kupva $kupva) use ($writer): void {
                    $writer->addRow($this->textRow([
                        (string) $kupva->id,
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
            $this->configureDataSheet($dataSheet);
            $writer->addRow($this->textRow(self::HEADERS, $this->headerStyle()));

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

    private function configureDataSheet(Sheet $sheet): void
    {
        $sheet->setColumnWidth(10, 1);
        $sheet->setColumnWidth(30, 2);
        $sheet->setColumnWidth(24, 3);
        $sheet->setColumnWidth(18, 4);
        $sheet->setColumnWidth(28, 5);
        $sheet->setColumnWidth(22, 6);
        $sheet->setColumnWidth(24, 7);
        $sheet->setColumnWidth(42, 8);
        $sheet->setColumnWidth(16, 9, 10);
        $sheet->setColumnWidth(18, 11, 12);
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
            ['ID', 'Kosongkan untuk data baru. Isi ID dari hasil ekspor jika ingin memperbarui data tertentu.'],
            ['Nama Usaha', 'Wajib. Tulis nama resmi penyelenggara KUPVA.'],
            ['Nomor Izin', 'Wajib untuk data baru dan harus unik. Data dengan nomor izin yang sama akan diperbarui, bukan dibuat ganda.'],
            ['Status Izin', 'Isi salah satu: Aktif, Kedaluwarsa, atau Dibekukan.'],
            ['Kabupaten/Kota', 'Wajib. Gunakan nama lengkap salah satu dari 10 kabupaten/kota di NTB.'],
            ['Kecamatan & Desa/Kelurahan', 'Isi sesuai alamat resmi agar data mudah ditemukan masyarakat.'],
            ['Latitude & Longitude', 'Opsional. Gunakan koordinat wilayah NTB; latitude -11 sampai -8 dan longitude 115 sampai 120.'],
            ['Berlaku Sampai', 'Opsional. Gunakan format YYYY-MM-DD, contoh 2027-12-31.'],
            ['Beroperasi', 'Wajib. Isi Ya atau Tidak.'],
            ['Sebelum impor', 'Sistem akan menampilkan data baru, perubahan, data yang sama, dan duplikat untuk diperiksa sebelum disimpan.'],
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
