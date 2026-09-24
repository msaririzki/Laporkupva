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
            'name' => 'Demo KUPVA Berizin 01',
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
        $this->seed(DemoDataSeeder::class);

        $this->assertDatabaseCount('kupvas', 12);
        $this->assertDatabaseCount('reports', 28);
        $this->assertDatabaseCount('report_status_histories', 94);
    }
}
