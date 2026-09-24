<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportExportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_reports_as_excel_compatible_csv(): void
    {
        $this->travelTo('2026-09-24 10:30:00');
        $admin = User::factory()->create();
        $report = Report::factory()->create([
            'public_code' => 'LKP-ABCD-1234',
            'business_name' => '=HYPERLINK("https://example.test")',
            'status' => ReportStatus::FieldAction,
            'internal_notes' => 'Koordinasi internal',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.export'));

        $response
            ->assertDownload('laporan-tambora-2026-09-24.csv')
            ->assertStreamed();

        $content = $response->streamedContent();

        $this->assertStringContainsString('"Kode Laporan","Tanggal Laporan","Jenis Laporan"', $content);
        $this->assertStringContainsString($report->public_code, $content);
        $this->assertStringContainsString("'=HYPERLINK", $content);
        $this->assertStringContainsString(ReportStatus::FieldAction->label(), $content);
        $this->assertStringContainsString('Koordinasi internal', $content);
        $this->assertStringNotContainsString($report->tracking_pin_hash, $content);
    }

    public function test_guest_is_redirected_to_login_before_exporting_reports(): void
    {
        $this->get(route('admin.reports.export'))
            ->assertRedirect('/admin/login');
    }

    public function test_inactive_admin_cannot_export_reports(): void
    {
        $admin = User::factory()->create(['is_active' => false]);

        $this->actingAs($admin)
            ->get(route('admin.reports.export'))
            ->assertForbidden();
    }
}
