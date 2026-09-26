<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\AnonymousMessage;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\ReportStatusHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class ReportTrackingControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_tracking_form_is_accessible(): void
    {
        $this->get(route('reports.track'))
            ->assertOk()
            ->assertSee('Lihat perkembangan laporan')
            ->assertSee('Gunakan gambar QR')
            ->assertSee('Pilih gambar dari perangkat')
            ->assertSee('Gambar hanya dibaca di perangkat ini dan tidak diunggah.')
            ->assertSee('data-qr-upload', false)
            ->assertSee('Masukkan nomor laporan')
            ->assertDontSee('PIN 6 digit')
            ->assertDontSee('Browser ini belum mendukung pembacaan QR');
    }

    public function test_tracking_form_prefills_the_report_code_from_a_qr_link(): void
    {
        $this->get(route('reports.track', ['code' => 'LKP-AB12-CD34']))
            ->assertOk()
            ->assertSee('value="LKP-AB12-CD34"', false);
    }

    public function test_report_can_be_tracked_with_its_report_number(): void
    {
        $report = Report::factory()->received()->create([
            'public_code' => 'LKP-AB12-CD34',
        ]);
        $report->statusHistories()->create([
            'from_status' => null,
            'to_status' => ReportStatus::Submitted,
            'public_note' => ReportStatus::Submitted->description(),
        ]);
        $report->statusHistories()->create([
            'from_status' => ReportStatus::Submitted,
            'to_status' => ReportStatus::Received,
            'public_note' => 'Laporan telah diterima petugas.',
        ]);

        $this->post(route('reports.track.show'), [
            'tracking_code' => 'lkp-ab12-cd34',
        ])->assertRedirect(route('reports.status', ['report' => $report->public_code]));

        $this->get(route('reports.status', ['report' => $report->public_code]))
            ->assertOk()
            ->assertHeaderContains('Cache-Control', 'no-store')
            ->assertHeader('Referrer-Policy', 'no-referrer')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive')
            ->assertSee('LKP-AB12-CD34')
            ->assertSee('Laporan telah diterima petugas.')
            ->assertSee('data-report-live-refresh', false)
            ->assertSee('Pembaruan otomatis aktif')
            ->assertSee('Tahap sekarang');
    }

    public function test_report_can_be_tracked_with_an_encrypted_qr_access_token(): void
    {
        $report = Report::factory()->received()->create([
            'public_code' => 'LKP-AB12-CD34',
        ]);
        $accessToken = Crypt::encryptString(json_encode([
            'version' => 2,
            'code' => $report->public_code,
        ], JSON_THROW_ON_ERROR));

        $this->post(route('reports.track.show'), [
            'access_token' => $accessToken,
        ])->assertRedirect(route('reports.status', ['report' => $report->public_code]));

        $this->get(route('reports.status', ['report' => $report->public_code]))
            ->assertOk()
            ->assertSee($report->public_code);
    }

    public function test_legacy_qr_access_token_remains_usable(): void
    {
        $report = Report::factory()->create(['public_code' => 'LKP-AB12-CD34']);
        $accessToken = Crypt::encryptString(json_encode([
            'version' => 1,
            'code' => $report->public_code,
            'pin' => '654321',
        ], JSON_THROW_ON_ERROR));

        $this->post(route('reports.track.show'), [
            'access_token' => $accessToken,
        ])->assertRedirect(route('reports.status', ['report' => $report->public_code]));
    }

    public function test_invalid_qr_access_token_does_not_open_a_report(): void
    {
        $this->from(route('reports.track'))
            ->post(route('reports.track.show'), ['access_token' => 'invalid-token'])
            ->assertRedirect(route('reports.track'))
            ->assertSessionHasErrors(['tracking_code']);
    }

    public function test_unknown_report_number_returns_a_clear_error(): void
    {
        $this->from(route('reports.track'))
            ->post(route('reports.track.show'), ['tracking_code' => 'LKP-ZZ99-ZZ99'])
            ->assertRedirect(route('reports.track'))
            ->assertSessionHasErrors([
                'tracking_code' => 'Nomor laporan tidak ditemukan. Periksa kembali nomor yang Anda masukkan.',
            ]);
    }

    public function test_public_status_note_is_escaped_on_the_tracking_page(): void
    {
        $report = Report::factory()->create([
            'public_code' => 'LKP-AB12-CD34',
        ]);
        $report->statusHistories()->create([
            'from_status' => null,
            'to_status' => ReportStatus::Submitted,
            'public_note' => '<script>alert("xss")</script>',
        ]);

        $this->post(route('reports.track.show'), [
            'tracking_code' => 'LKP-AB12-CD34',
        ])->assertRedirect(route('reports.status', ['report' => $report->public_code]));

        $this->get(route('reports.status', ['report' => $report->public_code]))
            ->assertOk()
            ->assertDontSee('<script>alert("xss")</script>', false)
            ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false);
    }

    public function test_report_status_cannot_be_opened_without_a_verified_tracking_session(): void
    {
        $report = Report::factory()->create();

        $this->get(route('reports.status', ['report' => $report->public_code]))
            ->assertNotFound();
    }

    public function test_verified_session_for_one_report_cannot_open_another_report_by_changing_the_url(): void
    {
        $verifiedReport = Report::factory()->create();
        $otherReport = Report::factory()->create();
        $session = [
            "tracked_reports.{$verifiedReport->getKey()}" => now()->addMinutes(10)->getTimestamp(),
        ];

        $this->withSession($session)
            ->get(route('reports.status', ['report' => $otherReport->public_code]))
            ->assertNotFound();

        $this->withSession($session)
            ->getJson(route('reports.status.updates', ['report' => $otherReport->public_code]))
            ->assertNotFound();
    }

    public function test_report_status_updates_cannot_be_checked_without_a_verified_tracking_session(): void
    {
        $report = Report::factory()->create();

        $this->getJson(route('reports.status.updates', ['report' => $report->public_code]))
            ->assertNotFound();
    }

    public function test_report_status_updates_return_a_new_version_after_public_data_changes(): void
    {
        $report = Report::factory()->received()->create();
        $session = [
            "tracked_reports.{$report->getKey()}" => now()->addMinutes(10)->getTimestamp(),
        ];

        $initialResponse = $this->withSession($session)
            ->getJson(route('reports.status.updates', ['report' => $report->public_code]))
            ->assertOk()
            ->assertHeaderContains('Cache-Control', 'no-store')
            ->assertExactJsonStructure(['version']);

        $report->update(['status' => ReportStatus::Coordination]);

        $statusUpdatedResponse = $this->withSession($session)
            ->getJson(route('reports.status.updates', ['report' => $report->public_code]))
            ->assertOk()
            ->assertExactJsonStructure(['version']);

        $this->assertNotSame(
            $initialResponse->json('version'),
            $statusUpdatedResponse->json('version'),
        );

        AnonymousMessage::factory()->create([
            'report_id' => $report->getKey(),
            'sender_type' => 'admin',
        ]);

        $updatedResponse = $this->withSession($session)
            ->getJson(route('reports.status.updates', ['report' => $report->public_code]))
            ->assertOk()
            ->assertExactJsonStructure(['version']);

        $this->assertNotSame(
            $statusUpdatedResponse->json('version'),
            $updatedResponse->json('version'),
        );
    }

    public function test_activity_photos_and_internal_notes_are_not_exposed_on_public_tracking(): void
    {
        $report = Report::factory()->received()->create();
        $history = ReportStatusHistory::factory()->create([
            'report_id' => $report->getKey(),
            'to_status' => ReportStatus::Received,
            'public_note' => 'Laporan sedang ditangani petugas.',
            'internal_note' => 'Identitas tim lapangan dan strategi pemeriksaan.',
        ]);
        ReportEvidence::factory()->create([
            'report_id' => $report->getKey(),
            'report_status_history_id' => $history->getKey(),
            'uploaded_by_user_id' => User::factory(),
            'source' => 'admin_activity',
            'original_name' => 'foto-kegiatan-internal.jpg',
            'caption' => 'Dokumentasi internal kunjungan lapangan.',
        ]);

        $this->withSession([
            "tracked_reports.{$report->getKey()}" => now()->addMinutes(10)->getTimestamp(),
        ])->get(route('reports.status', $report))
            ->assertOk()
            ->assertSee('Laporan sedang ditangani petugas.')
            ->assertDontSee('Identitas tim lapangan dan strategi pemeriksaan.')
            ->assertDontSee('foto-kegiatan-internal.jpg')
            ->assertDontSee('Dokumentasi internal kunjungan lapangan.');
    }
}
