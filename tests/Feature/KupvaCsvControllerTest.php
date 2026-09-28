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
use Tests\TestCase;

class KupvaCsvControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_an_excel_compatible_kupva_template(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.kupvas.template'));

        $response
            ->assertDownload('template-impor-kupva.csv')
            ->assertStreamed();

        $content = $response->streamedContent();

        $this->assertStringStartsWith("\xEF\xBB\xBF", $content);
        $this->assertStringContainsString('ID,"Nama Usaha","Nomor Izin","Status Izin",Kabupaten/Kota', $content);
        $this->assertSame(1, substr_count(trim($content), "\n") + 1);
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
            ->assertDownload('data-kupva-2026-09-28.csv')
            ->assertStreamed();

        $content = $response->streamedContent();

        $this->assertStringContainsString($kupva->license_number, $content);
        $this->assertStringContainsString("'=KUPVA Berbahaya", $content);
        $this->assertStringContainsString('"Kota Mataram",Selaparang,Rembiga', $content);
        $this->assertStringContainsString('2027-12-31,Ya', $content);
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
            'ID,Nama Usaha,Nomor Izin,Status Izin,Kabupaten/Kota,Kecamatan,Desa/Kelurahan,Alamat,Latitude,Longitude,Berlaku Sampai,Beroperasi',
            ',PT Lombok Valas,KUPVA-NTB-7777,Aktif,Kabupaten Lombok Barat,Batulayar,Senggigi,Jalan Raya Senggigi,-8.4919000,116.0456000,2027-12-31,Ya',
        ]));

        $this->actingAs($admin);

        Livewire::test(ListKupvas::class)
            ->assertActionExists('importCsv')
            ->callAction('importCsv', ['file' => $file])
            ->assertNotified('Data KUPVA berhasil diimpor');

        $this->assertDatabaseHas('kupvas', [
            'name' => 'PT Lombok Valas',
            'license_number' => 'KUPVA-NTB-7777',
        ]);
    }

    public function test_importer_creates_new_rows_and_updates_matching_license_numbers(): void
    {
        Kupva::factory()->create([
            'name' => 'Nama Lama',
            'license_number' => 'KUPVA-NTB-1001',
        ]);

        $file = UploadedFile::fake()->createWithContent('kupva.csv', implode("\n", [
            'ID,Nama Usaha,Nomor Izin,Status Izin,Kabupaten/Kota,Kecamatan,Desa/Kelurahan,Alamat,Latitude,Longitude,Berlaku Sampai,Beroperasi',
            ',PT Nusa Valas,KUPVA-NTB-1001,Aktif,Kota Mataram,Selaparang,Rembiga,Jalan Adi Sucipto,-8.5830695,116.1161800,2027-12-31,Ya',
            ',PT Samawa Valuta,KUPVA-NTB-1002,Dibekukan,Kabupaten Sumbawa,Sumbawa,Brang Biji,Jalan Garuda,-8.4932000,117.4202000,2027-06-30,Tidak',
        ]));

        $result = app(KupvaCsvImporter::class)->import($file);

        $this->assertSame(['created' => 1, 'updated' => 1], $result);
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
            'ID,Nama Usaha,Nomor Izin,Status Izin,Kabupaten/Kota,Kecamatan,Desa/Kelurahan,Alamat,Latitude,Longitude,Berlaku Sampai,Beroperasi',
            ',PT Nusa Valas,KUPVA-NTB-1001,Aktif,Kota Mataram,Selaparang,Rembiga,Jalan Adi Sucipto,-8.5830695,116.1161800,2027-12-31,Ya',
            ',PT Salah Wilayah,KUPVA-NTB-1002,Aktif,Kota Denpasar,Denpasar Barat,Dauh Puri,Jalan Teuku Umar,-8.6500000,115.2100000,2027-12-31,Ya',
        ]));

        try {
            app(KupvaCsvImporter::class)->import($file);
            $this->fail('Impor seharusnya ditolak.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('Baris 3', implode(' ', $exception->errors()['file']));
        }

        $this->assertDatabaseCount('kupvas', 0);
    }
}
