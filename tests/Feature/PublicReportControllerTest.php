<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\User;
use App\Notifications\Admin\NewReportSubmitted;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicReportControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        config()->set('services.turnstile.site_key');
        config()->set('services.turnstile.secret_key');
    }

    public function test_anonymous_report_form_is_accessible(): void
    {
        $this->get(route('reports.create'))
            ->assertOk()
            ->assertSee('Laporkan dengan cepat dan aman')
            ->assertSee('Gunakan lokasi saya')
            ->assertSee('Bukti pendukung wajib')
            ->assertSee('1–5 berkas sekaligus')
            ->assertSee('Foto besar otomatis diperkecil di perangkat Anda')
            ->assertSee('Nomor HP Pelapor')
            ->assertSee('Hanya digunakan petugas bila perlu menghubungi Anda.')
            ->assertSee('>Lokasi Kejadian</h2>', false)
            ->assertDontSee('Tentukan lokasinya')
            ->assertSee('evidence-preview-modal', false)
            ->assertSee('Foto ditampilkan utuh sesuai orientasi aslinya')
            ->assertSeeInOrder([
                'Kabupaten Lombok Barat',
                'Kabupaten Lombok Tengah',
                'Kabupaten Lombok Timur',
                'Kabupaten Lombok Utara',
                'Kabupaten Sumbawa',
                'Kabupaten Sumbawa Barat',
                'Kabupaten Dompu',
                'Kabupaten Bima',
                'Kota Mataram',
                'Kota Bima',
            ])
            ->assertDontSee('name="nik"', false);
    }

    public function test_report_form_displays_turnstile_when_security_verification_is_configured(): void
    {
        config()->set('services.turnstile.site_key', 'test-site-key');
        config()->set('services.turnstile.secret_key', 'test-secret-key');

        $this->get(route('reports.create'))
            ->assertOk()
            ->assertSee('Verifikasi keamanan')
            ->assertSee('https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit', false)
            ->assertSee('data-sitekey="test-site-key"', false);
    }

    public function test_report_requires_turnstile_token_when_security_verification_is_configured(): void
    {
        $this->enableTurnstile();
        Http::preventStrayRequests();

        $this->from(route('reports.create'))
            ->post(route('reports.store'), $this->validPayload())
            ->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors('cf-turnstile-response');

        $this->assertDatabaseCount('reports', 0);
        Http::assertNothingSent();
    }

    public function test_report_rejects_an_invalid_turnstile_token(): void
    {
        $this->enableTurnstile();
        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => false]),
        ]);

        $this->from(route('reports.create'))
            ->post(route('reports.store'), $this->validPayload([
                'cf-turnstile-response' => 'invalid-token',
            ]))
            ->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors('cf-turnstile-response');

        $this->assertDatabaseCount('reports', 0);
    }

    public function test_report_rejects_a_turnstile_token_created_for_another_action(): void
    {
        $this->enableTurnstile();
        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
                'success' => true,
                'action' => 'admin_login',
            ]),
        ]);

        $this->from(route('reports.create'))
            ->post(route('reports.store'), $this->validPayload([
                'cf-turnstile-response' => 'valid-token-for-another-action',
            ]))
            ->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors('cf-turnstile-response');

        $this->assertDatabaseCount('reports', 0);
    }

    public function test_report_accepts_a_valid_turnstile_token(): void
    {
        $this->enableTurnstile();
        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
                'success' => true,
                'action' => 'submit_report',
            ]),
        ]);

        $this->post(route('reports.store'), $this->validPayload([
            'cf-turnstile-response' => 'valid-token',
        ]))->assertRedirect(route('reports.success'));

        $this->assertDatabaseCount('reports', 1);
        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://challenges.cloudflare.com/turnstile/v0/siteverify'
                && $request['secret'] === 'test-secret-key'
                && $request['response'] === 'valid-token'
                && ! isset($request['remoteip']);
        });
    }

    public function test_report_retries_turnstile_verification_after_a_temporary_connection_failure(): void
    {
        $this->enableTurnstile();
        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::sequence()
                ->pushFailedConnection()
                ->push([
                    'success' => true,
                    'action' => 'submit_report',
                ]),
        ]);

        $this->post(route('reports.store'), $this->validPayload([
            'cf-turnstile-response' => 'valid-token-after-retry',
        ]))->assertRedirect(route('reports.success'));

        $this->assertDatabaseCount('reports', 1);
        Http::assertSentCount(2);
    }

    public function test_valid_anonymous_report_is_stored_with_a_report_number_and_evidence(): void
    {
        $response = $this->post(route('reports.store'), $this->validPayload([
            'evidence' => [
                UploadedFile::fake()->create('bukti-pertama.pdf', 128, 'application/pdf'),
                UploadedFile::fake()->image('bukti-kedua.jpg'),
            ],
        ]));

        $response->assertRedirect(route('reports.success'))
            ->assertSessionHas('submitted_report');

        $access = $response->getSession()->get('submitted_report');
        $report = Report::query()->sole();

        $this->assertMatchesRegularExpression('/^LKP-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $report->public_code);
        $this->assertSame($report->public_code, $access['code']);
        $this->assertArrayHasKey('submitted_at', $access);
        $this->assertArrayNotHasKey('pin', $access);
        $this->assertNotEmpty($report->tracking_pin_hash);
        $this->assertSame(ReportStatus::Submitted, $report->status);
        $this->assertDatabaseHas('report_status_histories', [
            'report_id' => $report->getKey(),
            'from_status' => null,
            'to_status' => ReportStatus::Submitted->value,
        ]);

        $this->assertCount(2, $report->evidence);
        Storage::disk('local')->assertExists($report->evidence->pluck('path')->all());
    }

    public function test_optional_phone_number_is_normalized_and_encrypted_at_rest(): void
    {
        $this->post(route('reports.store'), $this->validPayload([
            'reporter_phone' => '0812 3456-7890',
        ]))->assertRedirect(route('reports.success'));

        $report = Report::query()->sole();
        $rawPhoneNumber = DB::table('reports')->where('id', $report->getKey())->value('reporter_phone');

        $this->assertSame('+6281234567890', $report->reporter_phone);
        $this->assertNotSame('+6281234567890', $rawPhoneNumber);
        $this->assertStringNotContainsString('081234567890', (string) $rawPhoneNumber);
    }

    public function test_invalid_optional_phone_number_is_rejected(): void
    {
        $this->from(route('reports.create'))
            ->post(route('reports.store'), $this->validPayload([
                'reporter_phone' => 'nomor-rahasia',
            ]))
            ->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors('reporter_phone');

        $this->assertDatabaseCount('reports', 0);
    }

    public function test_report_ignores_an_unexpected_initial_message_field(): void
    {
        $response = $this->post(route('reports.store'), $this->validPayload([
            'user_message' => 'Catatan khusus untuk petugas verifikator.',
        ]));

        $response->assertRedirect(route('reports.success'));

        $report = Report::query()->sole();
        $this->assertCount(0, $report->anonymousMessages);
    }

    public function test_new_report_notifies_each_active_admin(): void
    {
        $admin = User::factory()->create();
        $superAdmin = User::factory()->superAdmin()->create();
        $inactiveAdmin = User::factory()->create(['is_active' => false]);

        $this->post(route('reports.store'), $this->validPayload())
            ->assertRedirect(route('reports.success'));

        $report = Report::query()->sole();
        $adminNotification = $admin->notifications()->sole();

        $this->assertSame(NewReportSubmitted::class, $adminNotification->type);
        $this->assertSame('Laporan baru masuk', $adminNotification->data['title']);
        $this->assertSame($report->getKey(), $adminNotification->data['report_id']);
        $this->assertStringContainsString($report->public_code, $adminNotification->data['body']);
        $this->assertStringContainsString("/admin/laporan/{$report->getRouteKey()}", $adminNotification->data['actions'][0]['url']);
        $this->assertStringEndsWith('#komunikasi-anonim', $adminNotification->data['actions'][0]['url']);
        $this->assertNull($adminNotification->data['actions'][0]['alpineClickHandler']);
        $this->assertTrue($adminNotification->data['actions'][0]['shouldMarkAsRead']);
        $this->assertSame(1, $superAdmin->notifications()->count());
        $this->assertSame(0, $inactiveAdmin->notifications()->count());
    }

    public function test_at_least_one_evidence_file_is_required(): void
    {
        $payload = $this->validPayload();
        unset($payload['evidence']);

        $this->from(route('reports.create'))
            ->post(route('reports.store'), $payload)
            ->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors('evidence');

        $this->assertDatabaseCount('reports', 0);
    }

    public function test_report_accepts_up_to_five_evidence_files(): void
    {
        $files = collect(range(1, 5))
            ->map(fn (int $number): UploadedFile => UploadedFile::fake()->image("bukti-{$number}.jpg"))
            ->all();

        $this->post(route('reports.store'), $this->validPayload([
            'evidence' => $files,
        ]))->assertRedirect(route('reports.success'));

        $this->assertCount(5, Report::query()->sole()->evidence);
    }

    public function test_report_rejects_more_than_five_evidence_files(): void
    {
        $files = collect(range(1, 6))
            ->map(fn (int $number): UploadedFile => UploadedFile::fake()->image("bukti-{$number}.jpg"))
            ->all();

        $this->from(route('reports.create'))
            ->post(route('reports.store'), $this->validPayload([
                'evidence' => $files,
            ]))
            ->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors('evidence');

        $this->assertDatabaseCount('reports', 0);
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

    public function test_report_rejects_a_regency_outside_the_supported_ntb_regions(): void
    {
        $response = $this->from(route('reports.create'))->post(route('reports.store'), $this->validPayload([
            'regency' => 'Kabupaten Badung',
        ]));

        $response->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors('regency');
        $this->assertDatabaseCount('reports', 0);
    }

    public function test_unsupported_evidence_type_is_rejected(): void
    {
        $response = $this->from(route('reports.create'))->post(route('reports.store'), $this->validPayload([
            'evidence' => [UploadedFile::fake()->create('program.exe', 64, 'application/octet-stream')],
        ]));

        $response->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors(['evidence.0']);
        $this->assertDatabaseCount('reports', 0);
    }

    public function test_evidence_with_a_mismatched_extension_is_rejected(): void
    {
        $response = $this->from(route('reports.create'))->post(route('reports.store'), $this->validPayload([
            'evidence' => [UploadedFile::fake()->image('foto-valid.php')],
        ]));

        $response->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors(['evidence.0']);
        $this->assertDatabaseCount('reports', 0);
    }

    public function test_evidence_original_name_is_normalized_before_storage(): void
    {
        $this->post(route('reports.store'), $this->validPayload([
            'evidence' => [UploadedFile::fake()->image('<script>.jpg')],
        ]))->assertRedirect(route('reports.success'));

        $report = Report::query()->sole();

        $this->assertSame('script.jpg', $report->evidence()->sole()->original_name);
    }

    public function test_report_rejects_html_and_control_characters(): void
    {
        $response = $this->from(route('reports.create'))->post(route('reports.store'), $this->validPayload([
            'business_name' => '<img src=x onerror=alert(1)>',
            'description' => "Kronologi yang cukup panjang\0dengan karakter kontrol berbahaya.",
        ]));

        $response->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors(['business_name', 'description']);
        $this->assertDatabaseCount('reports', 0);
    }

    public function test_report_ignores_unexpected_privileged_attributes(): void
    {
        $this->post(route('reports.store'), $this->validPayload([
            'status' => ReportStatus::Completed->value,
            'public_code' => 'LKP-EVIL-0000',
            'tracking_pin_hash' => 'attacker-controlled',
            'internal_notes' => 'Jangan terlihat oleh admin.',
        ]))->assertRedirect(route('reports.success'));

        $report = Report::query()->sole();

        $this->assertSame(ReportStatus::Submitted, $report->status);
        $this->assertNotSame('LKP-EVIL-0000', $report->public_code);
        $this->assertNotSame('attacker-controlled', $report->tracking_pin_hash);
        $this->assertNull($report->internal_notes);
    }

    public function test_report_honeypot_rejects_automated_submission(): void
    {
        $response = $this->from(route('reports.create'))->post(route('reports.store'), $this->validPayload([
            'website' => 'https://spam.example',
        ]));

        $response->assertRedirect(route('reports.create'))
            ->assertSessionHasErrors('website');
        $this->assertDatabaseCount('reports', 0);
    }

    public function test_report_submission_is_rate_limited_against_bursts(): void
    {
        $this->post(route('reports.store'), $this->validPayload())
            ->assertRedirect(route('reports.success'));
        $this->post(route('reports.store'), $this->validPayload())
            ->assertRedirect(route('reports.success'));

        $this->post(route('reports.store'), $this->validPayload())
            ->assertTooManyRequests();

        $this->assertDatabaseCount('reports', 2);
    }

    public function test_evidence_larger_than_ten_megabytes_is_rejected(): void
    {
        $response = $this->from(route('reports.create'))->post(route('reports.store'), $this->validPayload([
            'evidence' => [UploadedFile::fake()->image('bukti-besar.jpg')->size(10241)],
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

    public function test_success_page_contains_a_safe_tracking_qr_and_image_download_action(): void
    {
        $submittedReport = [
            'code' => 'LKP-AB12-CD34',
            'submitted_at' => now()->toIso8601String(),
        ];

        $response = $this->withSession(['submitted_report' => $submittedReport])
            ->get(route('reports.success'));

        $response
            ->assertOk()
            ->assertSee('Pindai untuk membuka status langsung')
            ->assertSee('Unduh gambar akses')
            ->assertSee('Simpan nomor laporan Anda')
            ->assertDontSee('PIN pelacakan')
            ->assertSee('data:image/svg+xml;base64,', false)
            ->assertViewHas('trackingUrl', function (string $trackingUrl): bool {
                return str_starts_with($trackingUrl, route('reports.track').'#access=')
                    && ! str_contains($trackingUrl, 'LKP-AB12-CD34');
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
            'evidence' => [UploadedFile::fake()->create('bukti.pdf', 128, 'application/pdf')],
            'good_faith' => '1',
            ...$overrides,
        ];
    }

    private function enableTurnstile(): void
    {
        config()->set('services.turnstile.site_key', 'test-site-key');
        config()->set('services.turnstile.secret_key', 'test-secret-key');
    }
}
