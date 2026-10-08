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
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Tests\TestCase;

class KupvaBiImportTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_import_all_21_bi_offices_without_phone_numbers_or_license_numbers(): void
    {
        Queue::fake([ResolveKupvaLocation::class]);
        $this->actingAs(User::factory()->create());
        $file = $this->makeBiUpload();

        Livewire::test(ListKupvas::class)
            ->callAction('importSpreadsheet', ['file' => $file])
            ->assertNotified('Data KUPVA berhasil diimpor');

        $this->assertDatabaseCount('kupvas', 21);
        $this->assertDatabaseHas('kupvas', [
            'name' => 'PT Maulana Gili Air',
            'regency' => 'Kabupaten Lombok Utara',
            'office_type' => 'KP',
            'license_number' => null,
        ]);
        Queue::assertPushed(ResolveKupvaLocation::class, 21);
        $this->assertSame(2, Kupva::query()->where('name', 'PT Echa Creative Mandiri')->count());
        $this->assertSame(3, Kupva::query()->where('name', 'PT Multigraha Kelola Valas')->count());
        $this->assertSame(0, Kupva::query()->whereNotNull('license_number')->count());
        $this->assertStringNotContainsString('000000000000', Kupva::all()->toJson());

        foreach ([
            'PT Maulana Gili Air' => '2030-08-06',
            'PT Duta Rinjani Valasindo' => '2028-01-09',
            'PT Echa Creative Mandiri' => '2028-04-16',
            'PT Makdwi Szwar Jalall Palasindo' => '2029-06-14',
            'PT Shaka Mulia Valasindo' => '2029-02-10',
        ] as $name => $date) {
            $this->assertSame($date, Kupva::query()->where('name', $name)->firstOrFail()->license_expires_at->format('Y-m-d'));
        }
    }

    public function test_reimport_keeps_offices_separate_and_preserves_admin_license_and_status(): void
    {
        $importer = app(KupvaCsvImporter::class);
        $file = $this->makeBiUpload();
        $importer->import($file);
        $office = Kupva::query()->where('name', 'PT Echa Creative Mandiri')->where('office_type', 'KC')->firstOrFail();
        $office->update(['license_number' => 'BI-ADMIN-123', 'license_status' => 'suspended', 'is_active' => false]);

        $result = $importer->import($file);

        $this->assertSame(['created' => 0, 'updated' => 0, 'unchanged' => 21, 'duplicates' => 0], $result);
        $this->assertDatabaseCount('kupvas', 21);
        $this->assertDatabaseHas('kupvas', ['id' => $office->id, 'license_number' => 'BI-ADMIN-123', 'license_status' => 'suspended', 'is_active' => false]);
    }

    public function test_bi_import_updates_expiry_and_skips_identical_duplicates(): void
    {
        $importer = app(KupvaCsvImporter::class);
        $importer->import($this->makeBiUpload());
        $office = Kupva::query()->where('name', 'PT Dikky Valuta Asing')->firstOrFail();
        $office->update(['license_expires_at' => '2029-01-01']);
        $fixture = $this->biFixture();
        $fixture['rows'][] = $fixture['rows'][0];

        $result = $importer->import($this->makeBiUpload($fixture));

        $this->assertSame(['created' => 0, 'updated' => 1, 'unchanged' => 20, 'duplicates' => 1], $result);
        $this->assertSame('2030-03-03', $office->refresh()->license_expires_at->format('Y-m-d'));
        $this->assertDatabaseCount('kupvas', 21);
    }

    public function test_invalid_bi_date_blocks_the_whole_import(): void
    {
        $fixture = $this->biFixture();
        $fixture['rows'][0][8] = '02/30/2030';
        $importer = app(KupvaCsvImporter::class);
        $file = $this->makeBiUpload($fixture);

        $analysis = $importer->analyze($file);

        $this->assertFalse($analysis['can_import']);
        $this->assertStringContainsString('Baris 2', $analysis['errors'][0]);
        try {
            $importer->import($file);
            $this->fail('Tanggal BI yang tidak valid harus ditolak.');
        } catch (ValidationException) {
            $this->assertDatabaseCount('kupvas', 0);
        }
    }

    public function test_bi_addresses_outside_supported_regions_block_import(): void
    {
        $fixture = $this->biFixture();
        $fixture['rows'][0][3] = 'Jalan Raya Denpasar, Bali';

        $analysis = app(KupvaCsvImporter::class)->analyze($this->makeBiUpload($fixture));

        $this->assertFalse($analysis['can_import']);
        $this->assertSame(1, $analysis['summary']['invalid']);
        $this->assertStringContainsString('kabupaten/kota', $analysis['errors'][0]);
        $this->assertDatabaseCount('kupvas', 0);
    }

    public function test_bi_duplicates_with_conflicting_dates_block_import(): void
    {
        $fixture = $this->biFixture();
        $duplicate = $fixture['rows'][0];
        $duplicate[8] = '03/03/2031';
        $fixture['rows'][] = $duplicate;

        $analysis = app(KupvaCsvImporter::class)->analyze($this->makeBiUpload($fixture));

        $this->assertFalse($analysis['can_import']);
        $this->assertSame(1, $analysis['summary']['duplicates']);
        $this->assertStringContainsString('isi berbeda', $analysis['errors'][0]);
        $this->assertDatabaseCount('kupvas', 0);
    }

    public function test_public_lists_bi_offices_with_only_name_and_address_and_filters_by_region(): void
    {
        $this->travelTo('2026-10-08');
        app(KupvaCsvImporter::class)->import($this->makeBiUpload());

        $this->get(route('kupvas.index', ['q' => 'Maulana', 'regency' => 'Kabupaten Lombok Utara']))
            ->assertSee('PT Maulana Gili Air')
            ->assertSee('Dusun Gili Air RT. 03 Desa Gili Indah Kecamatan Pemenang')
            ->assertDontSee('Nomor izin')
            ->assertDontSee('Berlaku sampai')
            ->assertDontSee('2030-08-06')
            ->assertDontSee('000000000000')
            ->assertDontSee('PT Dikky Valuta Asing');
    }

    public function test_admin_still_sees_office_type_license_and_expiry(): void
    {
        $this->actingAs(User::factory()->create());
        $office = Kupva::factory()->create(['office_type' => 'KC', 'license_number' => 'BI-ADMIN-456', 'license_expires_at' => '2030-03-03']);

        $this->get(route('filament.admin.resources.kupva.view', $office))
            ->assertSee('BI-ADMIN-456')
            ->assertSee('Kantor cabang (KC)')
            ->assertSee('Berlaku sampai');
    }

    public function test_exported_bi_data_can_be_reimported_without_merging_branches(): void
    {
        $this->actingAs(User::factory()->create());
        app(KupvaCsvImporter::class)->import($this->makeBiUpload());
        $response = $this->get(route('admin.kupvas.export'));
        $file = UploadedFile::fake()->createWithContent('kupva.xlsx', $response->streamedContent());

        $result = app(KupvaCsvImporter::class)->import($file);

        $this->assertSame(['created' => 0, 'updated' => 0, 'unchanged' => 21, 'duplicates' => 0], $result);
        $this->assertDatabaseCount('kupvas', 21);
    }

    public function test_admin_can_edit_imported_bi_data_while_license_number_is_empty(): void
    {
        $this->actingAs(User::factory()->create());
        app(KupvaCsvImporter::class)->import($this->makeBiUpload());
        $office = Kupva::query()->where('name', 'PT Dikky Valuta Asing')->firstOrFail();

        Livewire::test(EditKupva::class, ['record' => $office->id])
            ->fillForm(['address' => 'Alamat baru, Kabupaten Lombok Utara', 'license_number' => null])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('kupvas', ['id' => $office->id, 'license_number' => null, 'address' => 'Alamat baru, Kabupaten Lombok Utara']);
    }

    /** @return array{headers: list<string>, rows: list<list<mixed>>} */
    private function biFixture(): array
    {
        return json_decode(file_get_contents(base_path('tests/Fixtures/kupva-bi.json')), true, flags: JSON_THROW_ON_ERROR);
    }

    /** @param array{headers: list<string>, rows: list<list<mixed>>}|null $fixture */
    private function makeBiUpload(?array $fixture = null): UploadedFile
    {
        $fixture ??= $this->biFixture();
        $path = tempnam(sys_get_temp_dir(), 'tambora-bi-');
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

        return UploadedFile::fake()->createWithContent('Data KUPVA BB di NTB.xlsx', file_get_contents($path));
    }
}
