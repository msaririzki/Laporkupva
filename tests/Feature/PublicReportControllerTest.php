<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Report;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicReportControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_anonymous_report_form_is_accessible(): void
    {
        $this->get(route('reports.create'))
            ->assertOk()
            ->assertSee('Sampaikan informasi yang Anda ketahui')
            ->assertSee('Lokasi kejadian')
            ->assertDontSee('NIK');
    }

    public function test_valid_anonymous_report_is_stored_with_private_tracking_pin_and_evidence(): void
    {
        Storage::fake('local');

        $response = $this->post(route('reports.store'), $this->validPayload([
            'evidence' => [UploadedFile::fake()->create('bukti.pdf', 128, 'application/pdf')],
        ]));

        $response->assertRedirect(route('reports.success'))
            ->assertSessionHas('submitted_report');

        $access = $response->getSession()->get('submitted_report');
        $report = Report::query()->sole();

        $this->assertMatchesRegularExpression('/^LKP-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $report->public_code);
        $this->assertSame($report->public_code, $access['code']);
        $this->assertArrayHasKey('submitted_at', $access);
        $this->assertTrue(Hash::check($access['pin'], $report->tracking_pin_hash));
        $this->assertNotSame($access['pin'], $report->tracking_pin_hash);
        $this->assertSame(ReportStatus::Submitted, $report->status);
        $this->assertDatabaseHas('report_status_histories', [
            'report_id' => $report->getKey(),
            'from_status' => null,
            'to_status' => ReportStatus::Submitted->value,
        ]);

        $evidence = $report->evidence()->sole();
        Storage::disk('local')->assertExists($evidence->path);
    }

    public function test_incomplete_or_outside_ntb_report_is_rejected(): void
    {
        $response = $this->from(route('reports.create'))->post(route('reports.store'), $this->validPayload([
            'description' => 'Terlalu singkat',
            'regency' => '',
            'latitude' => -6.2000000,
            'longitude' => 106.8166667,
        ]));

        $response->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors(['description', 'regency', 'latitude', 'longitude']);
        $this->assertDatabaseCount('reports', 0);
    }

    public function test_unsupported_evidence_type_is_rejected(): void
    {
        Storage::fake('local');

        $response = $this->from(route('reports.create'))->post(route('reports.store'), $this->validPayload([
            'evidence' => [UploadedFile::fake()->create('program.exe', 64, 'application/octet-stream')],
        ]));

        $response->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors(['evidence.0']);
        $this->assertDatabaseCount('reports', 0);
    }

    public function test_good_faith_confirmation_is_required(): void
    {
        $payload = $this->validPayload();
        unset($payload['good_faith']);

        $this->from(route('reports.create'))
            ->post(route('reports.store'), $payload)
            ->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors('good_faith');

        $this->assertDatabaseCount('reports', 0);
    }

    public function test_success_page_cannot_be_reopened_without_submission_session(): void
    {
        $this->get(route('reports.success'))
            ->assertRedirect(route('reports.create'));
    }

    public function test_success_page_contains_a_safe_tracking_qr_code_without_the_pin_in_its_url(): void
    {
        $submittedReport = [
            'code' => 'LKP-AB12-CD34',
            'pin' => '654321',
            'submitted_at' => now()->toIso8601String(),
        ];

        $response = $this->withSession(['submitted_report' => $submittedReport])
            ->get(route('reports.success'));

        $response
            ->assertOk()
            ->assertSee('Pindai untuk membuka pelacakan')
            ->assertSee('data:image/svg+xml;base64,', false)
            ->assertViewHas('trackingUrl', function (string $trackingUrl): bool {
                return $trackingUrl === route('reports.track', ['code' => 'LKP-AB12-CD34'])
                    && ! str_contains($trackingUrl, '654321');
            });
    }

    /** @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return [
            'incident_type' => 'kupva_tanpa_izin',
            'business_name' => 'Money Changer Contoh',
            'incident_date' => now()->subDay()->toDateString(),
            'incident_time' => '14:30',
            'description' => 'Terlihat aktivitas penukaran valuta asing tanpa papan izin yang jelas di lokasi tersebut.',
            'is_ongoing' => '1',
            'regency' => 'Kota Mataram',
            'district' => 'Cakranegara',
            'village' => 'Cilinaya',
            'address' => 'Jalan contoh dekat pasar',
            'latitude' => -8.5830695,
            'longitude' => 116.1161800,
            'location_accuracy' => 12.5,
            'good_faith' => '1',
            ...$overrides,
        ];
    }
}
