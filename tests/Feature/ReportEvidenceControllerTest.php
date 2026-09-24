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
