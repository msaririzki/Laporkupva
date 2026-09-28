<?php

namespace Tests\Feature;

use App\Filament\Resources\Kupvas\KupvaCsvImporter;
use App\Filament\Resources\Kupvas\Pages\ListKupvas;
use App\Models\Kupva;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Writer\XLSX\Writer;
use Tests\TestCase;

class KupvaCsvControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_a_real_excel_kupva_template(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.kupvas.template'));

        $response
            ->assertDownload('template-impor-kupva.xlsx')
            ->assertStreamed();

        $content = $response->streamedContent();
        $workbook = $this->readXlsx($content);

        $this->assertStringStartsWith('PK', $content);
        $this->assertSame(['Data KUPVA', 'Petunjuk'], array_keys($workbook));
        $this->assertSame(
            ['Nama Usaha', 'Nomor Izin', 'Kabupaten/Kota', 'Kecamatan', 'Desa/Kelurahan'],
            array_slice($workbook['Data KUPVA'][0], 0, 5),
        );
        $this->assertNotContains('ID', $workbook['Data KUPVA'][0]);
        $this->assertSame('Petunjuk pengisian template KUPVA', $workbook['Petunjuk'][0][0]);
    }

    public function test_admin_can_export_kupva_data_that_can_be_imported_again(): void
    {
        $this->travelTo('2026-09-28 10:00:00');
        $admin = User::factory()->create();
        $kupva = Kupva::factory()->create([
            'name' => '=KUPVA Berbahaya',
            'license_number' => 'KUPVA-NTB-9001',
            'regency' => 'Kota Mataram',
            'district' => 'Selaparang',
            'village' => 'Rembiga',
            'license_status' => 'active',
            'license_expires_at' => '2027-12-31',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.kupvas.export'));

        $response
            ->assertDownload('data-kupva-2026-09-28.xlsx')
            ->assertStreamed();

        $rows = $this->readXlsx($response->streamedContent())['Data KUPVA'];
        $dataRow = $rows[1];

        $this->assertSame('=KUPVA Berbahaya', $dataRow[0]);
        $this->assertSame($kupva->license_number, $dataRow[1]);
        $this->assertSame('Kota Mataram', $dataRow[3]);
        $this->assertSame('2027-12-31', $dataRow[9]);
        $this->assertSame('Ya', $dataRow[10]);
    }

    public function test_guest_must_login_before_downloading_kupva_csv_files(): void
    {
        $this->get(route('admin.kupvas.export'))->assertRedirect('/admin/login');
        $this->get(route('admin.kupvas.template'))->assertRedirect('/admin/login');
    }

    public function test_admin_can_import_a_csv_from_the_kupva_page(): void
    {
        $admin = User::factory()->create();
        $file = UploadedFile::fake()->createWithContent('kupva.csv', implode("\n", [
            'Nama Usaha,Nomor Izin,Kabupaten/Kota,Kecamatan,Desa/Kelurahan,Alamat,Berlaku Sampai',
            'PT Lombok Valas,KUPVA-NTB-7777,Kabupaten Lombok Barat,Batulayar,Senggigi,Jalan Raya Senggigi,2027-12-31',
        ]));

        $this->actingAs($admin);

        Livewire::test(ListKupvas::class)
            ->assertActionExists('importSpreadsheet')
            ->callAction('importSpreadsheet', ['file' => $file])
            ->assertNotified('Data KUPVA berhasil diimpor');

        $this->assertDatabaseHas('kupvas', [
            'name' => 'PT Lombok Valas',
            'license_number' => 'KUPVA-NTB-7777',
            'license_status' => 'active',
            'is_active' => true,
        ]);
    }

    public function test_kupva_import_modal_shows_the_excel_template_and_preview_steps(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin);

        Livewire::test(ListKupvas::class)
            ->assertActionExists('importSpreadsheet');

        $this->view('filament.kupvas.import-upload-help', [
            'templateUrl' => route('admin.kupvas.template'),
        ])
            ->assertSee('Gunakan format yang sudah disiapkan')
            ->assertSee('Unduh template Excel');
    }

    public function test_importer_creates_new_rows_and_updates_matching_license_numbers(): void
    {
        Kupva::factory()->create([
            'name' => 'Nama Lama',
            'license_number' => 'KUPVA-NTB-1001',
        ]);

        $file = UploadedFile::fake()->createWithContent('kupva.csv', implode("\n", [
            'Nama Usaha,Nomor Izin,Status Izin,Kabupaten/Kota,Kecamatan,Desa/Kelurahan,Alamat,Latitude,Longitude,Berlaku Sampai,Beroperasi',
            'PT Nusa Valas,KUPVA-NTB-1001,Aktif,Kota Mataram,Selaparang,Rembiga,Jalan Adi Sucipto,-8.5830695,116.1161800,2027-12-31,Ya',
            'PT Samawa Valuta,KUPVA-NTB-1002,Dibekukan,Kabupaten Sumbawa,Sumbawa,Brang Biji,Jalan Garuda,-8.4932000,117.4202000,2027-06-30,Tidak',
        ]));

        $result = app(KupvaCsvImporter::class)->import($file);

        $this->assertSame([
            'created' => 1,
            'updated' => 1,
            'unchanged' => 0,
            'duplicates' => 0,
        ], $result);
        $this->assertDatabaseCount('kupvas', 2);
        $this->assertDatabaseHas('kupvas', [
            'name' => 'PT Nusa Valas',
            'license_number' => 'KUPVA-NTB-1001',
            'district' => 'Selaparang',
            'village' => 'Rembiga',
            'is_active' => true,
        ]);
        $this->assertDatabaseHas('kupvas', [
            'name' => 'PT Samawa Valuta',
            'license_status' => 'suspended',
            'is_active' => false,
        ]);
    }

    public function test_importer_rejects_the_whole_file_when_one_row_is_invalid(): void
    {
        $file = UploadedFile::fake()->createWithContent('kupva.csv', implode("\n", [
            'Nama Usaha,Nomor Izin,Kabupaten/Kota',
            'PT Nusa Valas,KUPVA-NTB-1001,Kota Mataram',
            'PT Salah Wilayah,KUPVA-NTB-1002,Kota Denpasar',
        ]));

        try {
            app(KupvaCsvImporter::class)->import($file);
            $this->fail('Impor seharusnya ditolak.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('Baris 3', implode(' ', $exception->errors()['file']));
        }

        $this->assertDatabaseCount('kupvas', 0);
    }

    public function test_excel_import_preview_explains_changes_and_skips_identical_duplicates(): void
    {
        $kupva = Kupva::factory()->create([
            'name' => 'Nama Lama',
            'license_number' => 'KUPVA-NTB-2001',
            'regency' => 'Kota Mataram',
            'district' => 'Selaparang',
            'village' => 'Rembiga',
            'address' => 'Jalan Lama',
            'latitude' => '-8.5830695',
            'longitude' => '116.1161800',
            'license_expires_at' => '2027-12-31',
            'is_active' => true,
        ]);

        $file = $this->makeXlsxUpload([
            [
                'Nama Baru',
                'KUPVA-NTB-2001',
                'Aktif',
                'Kota Mataram',
                'Selaparang',
                'Rembiga',
                'Jalan Baru',
                '-8.5830695',
                '116.1161800',
                '2027-12-31',
                'Ya',
            ],
            [
                'Nama Baru',
                'KUPVA-NTB-2001',
                'Aktif',
                'Kota Mataram',
                'Selaparang',
                'Rembiga',
                'Jalan Baru',
                '-8.5830695',
                '116.1161800',
                '2027-12-31',
                'Ya',
            ],
        ]);

        $analysis = app(KupvaCsvImporter::class)->analyze($file);

        $this->assertTrue($analysis['can_import']);
        $this->assertSame(1, $analysis['summary']['updated']);
        $this->assertSame(1, $analysis['summary']['duplicates']);
        $this->assertSame(['Nama usaha', 'Alamat'], array_column($analysis['items'][0]['changes'], 'label'));
        $this->assertSame('Nama Lama', $analysis['items'][0]['changes'][0]['before']);
        $this->assertSame('Nama Baru', $analysis['items'][0]['changes'][0]['after']);

        $result = app(KupvaCsvImporter::class)->import($file);

        $this->assertSame(1, $result['updated']);
        $this->assertSame(1, $result['duplicates']);
        $this->assertDatabaseCount('kupvas', 1);
        $this->assertDatabaseHas('kupvas', [
            'id' => $kupva->id,
            'name' => 'Nama Baru',
            'address' => 'Jalan Baru',
        ]);
    }

    public function test_importer_blocks_conflicting_duplicate_rows(): void
    {
        $file = UploadedFile::fake()->createWithContent('kupva.csv', implode("\n", [
            'Nama Usaha,Nomor Izin,Kabupaten/Kota,Alamat',
            'PT Nusa Valas,KUPVA-NTB-3001,Kota Mataram,Jalan A',
            'PT Nusa Valas,KUPVA-NTB-3001,Kota Mataram,Jalan B',
        ]));

        $analysis = app(KupvaCsvImporter::class)->analyze($file);

        $this->assertFalse($analysis['can_import']);
        $this->assertSame(1, $analysis['summary']['duplicates']);
        $this->assertTrue($analysis['items'][1]['is_conflict']);
        $this->assertStringContainsString('isi berbeda', $analysis['errors'][0]);

        $this->expectException(ValidationException::class);

        app(KupvaCsvImporter::class)->import($file);
    }

    /**
     * @param  list<list<mixed>>  $rows
     */
    private function makeXlsxUpload(array $rows): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'tambora-kupva-');
        $this->assertNotFalse($path);

        $writer = new Writer;
        $writer->openToFile($path);
        $writer->addRow(Row::fromValues([
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
        ]));

        foreach ($rows as $row) {
            $writer->addRow(Row::fromValues($row));
        }

        $writer->close();

        return new UploadedFile(
            $path,
            'data-kupva.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true,
        );
    }

    public function test_simple_import_updates_only_supplied_fields_and_preserves_existing_status(): void
    {
        Kupva::factory()->create([
            'name' => 'Nama Lama',
            'license_number' => 'KUPVA-NTB-4001',
            'license_status' => 'suspended',
            'regency' => 'Kota Mataram',
            'district' => 'Selaparang',
            'address' => 'Alamat lama',
            'is_active' => false,
        ]);
        $file = UploadedFile::fake()->createWithContent('kupva.csv', implode("\n", [
            'Nama Usaha,Nomor Izin,Kabupaten/Kota',
            'Nama Baru,KUPVA-NTB-4001,Kota Mataram',
        ]));

        $result = app(KupvaCsvImporter::class)->import($file);

        $this->assertSame(1, $result['updated']);
        $this->assertDatabaseHas('kupvas', [
            'name' => 'Nama Baru',
            'license_number' => 'KUPVA-NTB-4001',
            'license_status' => 'suspended',
            'district' => 'Selaparang',
            'address' => 'Alamat lama',
            'is_active' => false,
        ]);
    }

    /** @return array<string, list<list<mixed>>> */
    private function readXlsx(string $content): array
    {
        $path = tempnam(sys_get_temp_dir(), 'tambora-xlsx-');
        $this->assertNotFalse($path);
        file_put_contents($path, $content);

        $reader = new Reader;
        $reader->open($path);
        $workbook = [];

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                $rows = [];

                foreach ($sheet->getRowIterator() as $row) {
                    $rows[] = $row->toArray();
                }

                $workbook[$sheet->getName()] = $rows;
            }
        } finally {
            $reader->close();
            unlink($path);
        }

        return $workbook;
    }
}
