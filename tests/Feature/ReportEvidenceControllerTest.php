<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\ReportEvidence;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportEvidenceControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_download_private_report_evidence(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $evidence = ReportEvidence::factory()->create([
            'path' => 'report-evidence/photo.jpg',
            'original_name' => 'bukti-lokasi.jpg',
            'mime_type' => 'image/jpeg',
        ]);
        Storage::disk('local')->put($evidence->path, 'private image contents');

        $this->actingAs($admin)
            ->get(route('admin.report-evidence.download', $evidence))
            ->assertOk()
            ->assertDownload('bukti-lokasi.jpg');
    }

    public function test_admin_can_preview_private_report_evidence_inline(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $evidence = ReportEvidence::factory()->create([
            'path' => 'report-activity/photo.jpg',
            'original_name' => 'kunjungan-lapangan.jpg',
            'mime_type' => 'image/jpeg',
        ]);
        Storage::disk('local')->put($evidence->path, 'private image contents');

        $this->actingAs($admin)
            ->get(route('admin.report-evidence.preview', $evidence))
            ->assertOk()
            ->assertHeader('content-type', 'image/jpeg')
            ->assertHeader('cache-control', 'no-store, private')
            ->assertHeader('content-security-policy', "sandbox; default-src 'none'")
            ->assertHeader('x-content-type-options', 'nosniff');
    }

    public function test_pdf_evidence_cannot_be_rendered_inline(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $evidence = ReportEvidence::factory()->create([
            'path' => 'report-evidence/document.pdf',
            'original_name' => 'dokumen.pdf',
            'mime_type' => 'application/pdf',
        ]);
        Storage::disk('local')->put($evidence->path, '%PDF private contents');

        $this->actingAs($admin)
            ->get(route('admin.report-evidence.preview', $evidence))
            ->assertNotFound();
    }

    public function test_inactive_admin_cannot_access_private_evidence(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'is_active' => false,
        ]);
        $evidence = ReportEvidence::factory()->create();
        Storage::disk('local')->put($evidence->path, 'private image contents');

        $this->actingAs($admin)
            ->get(route('admin.report-evidence.download', $evidence))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.report-evidence.preview', $evidence))
            ->assertForbidden();
    }

    public function test_guest_cannot_preview_private_report_evidence(): void
    {
        Storage::fake('local');
        $evidence = ReportEvidence::factory()->create();
        Storage::disk('local')->put($evidence->path, 'private image contents');

        $this->get(route('admin.report-evidence.preview', $evidence))
            ->assertRedirect();
    }

    public function test_guest_cannot_download_report_evidence(): void
    {
        Storage::fake('local');
        $evidence = ReportEvidence::factory()->create();
        Storage::disk('local')->put($evidence->path, 'private image contents');

        $this->get(route('admin.report-evidence.download', $evidence))
            ->assertRedirect();
    }

    public function test_missing_private_evidence_file_returns_not_found(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $evidence = ReportEvidence::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.report-evidence.download', $evidence))
            ->assertNotFound();
    }
}
