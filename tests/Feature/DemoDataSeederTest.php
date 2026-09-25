<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\User;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DemoDataSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_it_seeds_complete_demo_data_without_faker(): void
    {
        $this->travelTo('2026-09-24 12:00:00');
        User::factory()->superAdmin()->create();

        $this->seed(DemoDataSeeder::class);

        $this->assertDatabaseCount('kupvas', 12);
        $this->assertDatabaseCount('reports', 28);
        $this->assertDatabaseCount('report_status_histories', 94);
        $this->assertDatabaseHas('kupvas', [
            'license_number' => 'DEMO-NTB-0001',
            'name' => 'KUPVA Demo Cakranegara 01',
            'regency' => 'Kota Mataram',
            'district' => 'Cakranegara',
            'village' => 'Cilinaya',
            'latitude' => -8.5901,
            'longitude' => 116.1322,
        ]);
        $this->assertDatabaseHas('reports', [
            'public_code' => 'LKP-DEMO-0001',
            'business_name' => 'Demo Valas Cakranegara 01',
            'incident_type' => 'kupva_tanpa_izin',
            'description' => 'Terlihat aktivitas penukaran valuta asing pada tempat usaha yang tidak menampilkan papan izin secara jelas.',
            'regency' => 'Kota Mataram',
            'district' => 'Cakranegara',
            'village' => 'Cilinaya',
            'latitude' => -8.5901,
            'longitude' => 116.1322,
        ]);

        $completedReport = Report::query()
            ->where('public_code', 'LKP-DEMO-0006')
            ->firstOrFail();

        $this->assertSame(ReportStatus::Completed, $completedReport->status);
        $this->assertCount(6, $completedReport->statusHistories);
        $this->assertTrue(Hash::check(DemoDataSeeder::TRACKING_PIN, $completedReport->tracking_pin_hash));
    }

    public function test_it_is_idempotent_when_run_more_than_once(): void
    {
        User::factory()->superAdmin()->create();

        $this->seed(DemoDataSeeder::class);
        Report::query()->where('public_code', 'LKP-DEMO-0001')->update([
            'regency' => 'Kota Bima',
            'district' => 'Lokasi tidak valid',
            'latitude' => -8.0000,
            'longitude' => 118.0000,
        ]);
        $this->seed(DemoDataSeeder::class);

        $this->assertDatabaseCount('kupvas', 12);
        $this->assertDatabaseCount('reports', 28);
        $this->assertDatabaseCount('report_status_histories', 94);
        $this->assertDatabaseHas('reports', [
            'public_code' => 'LKP-DEMO-0001',
            'regency' => 'Kota Mataram',
            'district' => 'Cakranegara',
            'latitude' => -8.5901,
            'longitude' => 116.1322,
        ]);
    }
}
