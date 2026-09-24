<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Filament\Resources\Kupvas\KupvaResource;
use App\Filament\Resources\Reports\ReportResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Kupva;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_open_dashboard_and_report_list(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $report = Report::factory()->create();
        Report::factory()->count(2)->create();
        Kupva::factory()->create();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Ringkasan pengawasan')
            ->assertSee('Peta sebaran lokasi terlapor');

        $this->actingAs($admin)
            ->get(ReportResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Laporan masyarakat');

        $this->actingAs($admin)
            ->get(ReportResource::getUrl('view', ['record' => $report]))
            ->assertOk()
            ->assertSee($report->public_code);

        $this->actingAs($admin)
            ->get(KupvaResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Data KUPVA');
    }

    public function test_regular_admin_cannot_manage_other_admin_accounts(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertForbidden();
    }

    public function test_super_admin_can_open_admin_account_management(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get(UserResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Manajemen admin');
    }
}
