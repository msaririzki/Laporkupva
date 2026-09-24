<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Filament\Resources\Kupvas\KupvaResource;
use App\Filament\Resources\Reports\Pages\ViewReport;
use App\Filament\Resources\Reports\ReportResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\AnonymousMessage;
use App\Models\Kupva;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_open_dashboard_and_report_list(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $report = Report::factory()->create();
        ReportEvidence::factory()->create([
            'report_id' => $report->getKey(),
            'original_name' => 'bukti-lokasi.jpg',
        ]);
        AnonymousMessage::factory()->create([
            'report_id' => $report->getKey(),
            'sender_type' => 'reporter',
            'body' => 'Lokasi berada dekat pasar.',
        ]);
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
            ->assertSee($report->public_code)
            ->assertSee('bukti-lokasi.jpg')
            ->assertSee('Lokasi berada dekat pasar.');

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

    public function test_admin_can_send_an_anonymous_message_from_report_detail(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $report = Report::factory()->create();

        $this->actingAs($admin);

        Livewire::test(ViewReport::class, ['record' => $report->getRouteKey()])
            ->callAction('sendMessage', [
                'body' => 'Mohon tambahkan patokan lokasi yang lebih jelas.',
            ])
            ->assertNotified();

        $this->assertDatabaseHas(AnonymousMessage::class, [
            'report_id' => $report->getKey(),
            'user_id' => $admin->getKey(),
            'sender_type' => 'admin',
            'body' => 'Mohon tambahkan patokan lokasi yang lebih jelas.',
        ]);
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
