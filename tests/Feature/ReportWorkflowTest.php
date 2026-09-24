<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Users\UserResource;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ReportWorkflowTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_advance_a_report_only_to_the_next_status(): void
    {
        $admin = User::factory()->create();
        $report = Report::factory()->create();

        $this->assertTrue($report->advanceStatus($admin, 'Pemeriksaan awal telah dilakukan.'));
        $this->assertSame(ReportStatus::Received, $report->fresh()->status);
        $this->assertNotNull($report->fresh()->received_at);
        $this->assertDatabaseHas('report_status_histories', [
            'report_id' => $report->getKey(),
            'user_id' => $admin->getKey(),
            'from_status' => ReportStatus::Submitted->value,
            'to_status' => ReportStatus::Received->value,
            'public_note' => 'Pemeriksaan awal telah dilakukan.',
        ]);
    }

    public function test_completed_report_cannot_advance_further(): void
    {
        $report = Report::factory()->completed()->create();

        $this->assertFalse($report->advanceStatus(User::factory()->create()));
        $this->assertDatabaseCount('report_status_histories', 0);
    }

    public function test_only_super_admin_can_manage_admin_accounts(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->actingAs($admin);
        $this->assertFalse(UserResource::canViewAny());
        $this->assertFalse(UserResource::canCreate());

        $superAdmin = User::factory()->superAdmin()->create();
        $this->actingAs($superAdmin);
        $this->assertTrue(UserResource::canViewAny());
        $this->assertTrue(UserResource::canCreate());
    }
}
