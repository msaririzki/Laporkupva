<?php

namespace Tests\Feature;

use App\Filament\Resources\Kupvas\KupvaCsvImporter;
use App\Filament\Resources\Kupvas\Pages\EditKupva;
use App\Filament\Resources\Kupvas\Pages\ListKupvas;
use App\Jobs\ResolveKupvaLocation;
use App\Models\Kupva;
use App\Models\User;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use PHPUnit\Framework\Attributes\TestWith;
use RuntimeException;
use Tests\TestCase;

class KupvaBiLicenseImportTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_new_bi_workbook_imports_all_offices_with_their_source_license_numbers(): void
    {
        Queue::fake([ResolveKupvaLocation::class]);
        $this->actingAs(User::factory()->create());

        Livewire::test(ListKupvas::class)->callAction('importSpreadsheet', ['file' => $this->makeBiUpload()])
            ->assertNotified('Data KUPVA berhasil diimpor');

        $this->assertDatabaseCount('kupvas', 21);
        $this->assertSame(21, Kupva::query()->whereNotNull('license_number')->count());
        $this->assertDatabaseHas('kupvas', ['name' => 'PT Dikky Valuta Asing', 'license_number' => '27/2/KEP.GBI/Mtr/2025']);
        $this->assertSame('2030-03-03', Kupva::query()->where('name', 'PT Dikky Valuta Asing')->firstOrFail()->license_expires_at->format('Y-m-d'));
        $this->assertSame(2, Kupva::query()->where('name', 'PT Echa Creative Mandiri')->where('license_number', '25/06/KEP.GBI/Mtr/2023')->count());
        $this->assertSame(3, Kupva::query()->where('name', 'PT Multigraha Kelola Valas')->where('license_number', '28/2/KEP.GBI/MTR/2026')->count());
        $this->assertSame(4, Kupva::query()->where('license_number', '28/3/KEP.GBI/MTR/2026')->count());
        Queue::assertPushed(ResolveKupvaLocation::class, 21);
    }

    public function test_new_workbook_fills_existing_office_licenses_without_duplicates_or_resetting_admin_status(): void
    {
        $importer = app(KupvaCsvImporter::class);
        $oldFixture = json_decode(file_get_contents(base_path('tests/Fixtures/kupva-bi.json')), true, flags: JSON_THROW_ON_ERROR);
        $importer->import($this->makeBiUpload($oldFixture));
        $originalIds = Kupva::query()->orderBy('id')->pluck('id')->all();
        $office = Kupva::query()->where('name', 'PT Echa Creative Mandiri')->where('office_type', 'KC')->firstOrFail();
        $office->update(['license_status' => 'suspended', 'is_active' => false, 'latitude' => -8.35, 'longitude' => 116.05]);

        $analysis = $importer->analyze($this->makeBiUpload());

        $this->assertTrue($analysis['can_import']);
        $this->assertSame(['new' => 0, 'updated' => 21, 'unchanged' => 0, 'duplicates' => 0, 'invalid' => 0], $analysis['summary']);
        $this->assertSame('Nomor izin', $analysis['items'][0]['changes'][0]['label']);
        $this->assertSame('27/2/KEP.GBI/Mtr/2025', $analysis['items'][0]['changes'][0]['after']);

        $result = $importer->import($this->makeBiUpload());

        $this->assertSame(['created' => 0, 'updated' => 21, 'unchanged' => 0, 'duplicates' => 0], $result);
        $this->assertSame($originalIds, Kupva::query()->orderBy('id')->pluck('id')->all());
        $this->assertDatabaseHas('kupvas', ['id' => $office->id, 'license_number' => '25/06/KEP.GBI/Mtr/2023', 'license_status' => 'suspended', 'is_active' => false, 'latitude' => -8.35, 'longitude' => 116.05]);
    }

    public function test_reimporting_shared_license_numbers_keeps_each_office_unchanged(): void
    {
        $importer = app(KupvaCsvImporter::class);
        $importer->import($this->makeBiUpload());

        $result = $importer->import($this->makeBiUpload());

        $this->assertSame(['created' => 0, 'updated' => 0, 'unchanged' => 21, 'duplicates' => 0], $result);
        $this->assertDatabaseCount('kupvas', 21);
    }

    #[TestWith([''])]
    #[TestWith(['-'])]
    #[TestWith(['–'])]
    #[TestWith(['—'])]
    public function test_blank_license_values_do_not_erase_an_existing_number(string $licenseNumber): void
    {
        $fixture = $this->biFixture();
        $fixture['rows'] = [$fixture['rows'][0]];
        $fixture['rows'][0][7] = $licenseNumber;
        $office = Kupva::factory()->create([
            'name' => $fixture['rows'][0][1], 'address' => $fixture['rows'][0][3], 'office_type' => 'KP',
            'license_number' => 'BI-KEEP-001', 'regency' => 'Kabupaten Lombok Utara',
        ]);

        app(KupvaCsvImporter::class)->import($this->makeBiUpload($fixture));

        $this->assertDatabaseCount('kupvas', 1);
        $this->assertSame('BI-KEEP-001', $office->fresh()->license_number);
    }

    public function test_unsafe_license_text_blocks_import_before_any_office_is_saved(): void
    {
        $fixture = $this->biFixture();
        $fixture['rows'][0][7] = '<script>alert(1)</script>';

        $analysis = app(KupvaCsvImporter::class)->analyze($this->makeBiUpload($fixture));

        $this->assertFalse($analysis['can_import']);
        $this->assertSame(1, $analysis['summary']['invalid']);
        $this->assertStringContainsString('Baris 2', $analysis['errors'][0]);
        $this->assertDatabaseCount('kupvas', 0);
    }

    public function test_duplicate_office_with_conflicting_license_numbers_is_reported(): void
    {
        $fixture = $this->biFixture();
        $duplicate = $fixture['rows'][0];
        $duplicate[7] = 'BI-CONFLICT-001';
        $fixture['rows'][] = $duplicate;

        $analysis = app(KupvaCsvImporter::class)->analyze($this->makeBiUpload($fixture));

        $this->assertFalse($analysis['can_import']);
        $this->assertSame(1, $analysis['summary']['duplicates']);
        $this->assertStringContainsString('isi berbeda', $analysis['errors'][0]);
    }

    public function test_legacy_file_without_office_identity_cannot_overwrite_an_arbitrary_shared_license(): void
    {
        $importer = app(KupvaCsvImporter::class);
        $importer->import($this->makeBiUpload());
        $file = UploadedFile::fake()->createWithContent('kupva.csv', "Nama Usaha,Nomor Izin,Kabupaten/Kota\nPT Nama Baru,28/3/KEP.GBI/MTR/2026,Kota Mataram");

        $analysis = $importer->analyze($file);

        $this->assertFalse($analysis['can_import']);
        $this->assertStringContainsString('Nomor izin digunakan oleh beberapa kantor', $analysis['errors'][0]);
        $this->assertDatabaseMissing('kupvas', ['name' => 'PT Nama Baru']);
        $this->assertDatabaseCount('kupvas', 21);
    }

    public function test_exported_offices_with_shared_licenses_can_be_reimported(): void
    {
        $importer = app(KupvaCsvImporter::class);
        $importer->import($this->makeBiUpload());
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('admin.kupvas.export'));
        $file = UploadedFile::fake()->createWithContent('kupva.xlsx', $response->streamedContent());

        $result = $importer->import($file);

        $this->assertSame(['created' => 0, 'updated' => 0, 'unchanged' => 21, 'duplicates' => 0], $result);
        $this->assertDatabaseCount('kupvas', 21);
    }

    public function test_admin_can_edit_office_when_its_license_is_shared(): void
    {
        Queue::fake([ResolveKupvaLocation::class]);
        app(KupvaCsvImporter::class)->import($this->makeBiUpload());
        $office = Kupva::query()->where('name', 'PT Echa Creative Mandiri')->where('office_type', 'KC')->firstOrFail();
        $this->actingAs(User::factory()->create());

        Livewire::test(EditKupva::class, ['record' => $office->id])->fillForm(['license_number' => '25/06/KEP.GBI/Mtr/2023'])
            ->call('save')->assertHasNoFormErrors();

        $this->assertSame('25/06/KEP.GBI/Mtr/2023', $office->fresh()->license_number);
        $this->assertDatabaseCount('kupvas', 21);
    }

    public function test_rollback_refuses_to_restore_unique_licenses_when_offices_share_numbers(): void
    {
        app(KupvaCsvImporter::class)->import($this->makeBiUpload());
        $migration = require database_path('migrations/2026_10_08_145703_allow_shared_license_numbers_for_kupva_offices.php');

        try {
            $migration->down();
            $this->fail('Batas unik lama tidak boleh dipulihkan dengan mengubah data kantor.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('Nomor izin digunakan oleh beberapa kantor', $exception->getMessage());
        }

        $this->assertDatabaseCount('kupvas', 21);
    }

    /** @return array{headers: list<string>, rows: list<list<mixed>>} */
    private function biFixture(): array
    {
        return json_decode(file_get_contents(base_path('tests/Fixtures/kupva-bi-with-licenses.json')), true, flags: JSON_THROW_ON_ERROR);
    }

    /** @param array{headers: list<string>, rows: list<list<mixed>>}|null $fixture */
    private function makeBiUpload(?array $fixture = null): UploadedFile
    {
        $fixture ??= $this->biFixture();
        $path = tempnam(sys_get_temp_dir(), 'tambora-bi-license-');
        $this->assertNotFalse($path);
        $writer = new Writer;
        $writer->openToFile($path);
        $writer->addRow(Row::fromValues($fixture['headers']));

        foreach ($fixture['rows'] as $values) {
            $values = array_map(fn (mixed $value): mixed => is_array($value) ? new DateTimeImmutable($value['excel_date']) : $value, $values);
            $writer->addRow(Row::fromValuesWithStyles($values, columnStyles: [8 => (new Style)->setFormat('mm/dd/yyyy')]));
        }

        $writer->close();
        $this->beforeApplicationDestroyed(fn (): bool => unlink($path));

        return UploadedFile::fake()->createWithContent('Data KUPVA BB di NTB (1).xlsx', file_get_contents($path));
    }
}
