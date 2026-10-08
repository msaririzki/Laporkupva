<?php

namespace Tests\Feature;

use App\Models\Report;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class PublicReportFourStepTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('services.turnstile.site_key', 'test-site-key');
        config()->set('services.turnstile.secret_key', 'test-secret-key');
    }

    public function test_form_has_four_steps_and_security_only_in_the_first_step(): void
    {
        $response = $this->get(route('reports.create'));

        $response->assertSeeInOrder(['data-step="1"', 'Nama pelapor', 'Email pelapor', 'Nomor HP Pelapor', 'Verifikasi keamanan', 'data-step="2"', 'Ceritakan kejadian', 'data-step="3"', 'Lokasi Kejadian', 'data-step="4"', 'Tambahkan foto &amp; bukti'], false)
            ->assertSee('Data Anda dilindungi Bank Indonesia')
            ->assertSee('Nama, email, dan nomor HP Anda terlindungi.')
            ->assertSee('Hanya petugas Bank Indonesia yang menggunakannya')
            ->assertSee('Lengkapi nama dan email agar petugas Bank Indonesia dapat membantu menindaklanjuti laporan Anda.')
            ->assertDontSee('Nomor HP boleh dikosongkan.')
            ->assertDontSee('Nomor HP Pelapor <span', false)
            ->assertSee('placeholder="Nama Anda" required', false)
            ->assertSee('Data pelapor dirahasiakan')->assertDontSee('Kegiatan masih berlangsung');
        $this->assertSame(1, substr_count($response->getContent(), 'id="reporter_phone"'));
        $this->assertSame(1, substr_count($response->getContent(), 'data-turnstile-widget'));
    }

    #[TestWith([''])]
    #[TestWith(['bukan-email'])]
    #[TestWith([['invalid']])]
    public function test_first_step_rejects_missing_or_invalid_email(mixed $email): void
    {
        Http::preventStrayRequests();

        $this->postJson(route('reports.verify'), ['reporter_name' => 'Pelapor Uji', 'reporter_email' => $email])
            ->assertUnprocessable()->assertJsonValidationErrors('reporter_email');

        $this->assertDatabaseCount('reports', 0);
        Http::assertNothingSent();
    }

    public function test_first_step_requires_a_fresh_captcha(): void
    {
        Http::preventStrayRequests();

        $this->postJson(route('reports.verify'), ['reporter_name' => 'Pelapor Uji', 'reporter_email' => 'pelapor@example.com'])
            ->assertUnprocessable()->assertJsonValidationErrors('cf-turnstile-response');

        $this->assertDatabaseCount('reports', 0);
        Http::assertNothingSent();
    }

    public function test_missing_captcha_configuration_cannot_bypass_production_verification(): void
    {
        $this->app->detectEnvironment(fn (): string => 'production');
        config()->set('services.turnstile.site_key');
        config()->set('services.turnstile.secret_key');
        Http::preventStrayRequests();

        $this->withSession(['_token' => 'test-csrf-token'])
            ->postJson(route('reports.verify'), ['_token' => 'test-csrf-token', 'reporter_name' => 'Pelapor Uji', 'reporter_email' => 'pelapor@example.com'])
            ->assertUnprocessable()->assertJsonValidationErrors('cf-turnstile-response');

        $this->assertDatabaseCount('reports', 0);
        Http::assertNothingSent();
    }

    #[TestWith([null])]
    #[TestWith([''])]
    #[TestWith(['   '])]
    public function test_first_step_requires_a_non_blank_reporter_name(mixed $name): void
    {
        config()->set('services.turnstile.site_key');
        config()->set('services.turnstile.secret_key');
        Http::preventStrayRequests();

        $this->postJson(route('reports.verify'), ['reporter_name' => $name, 'reporter_email' => 'pelapor@example.com'])
            ->assertUnprocessable()->assertJsonValidationErrors('reporter_name')
            ->assertJsonPath('errors.reporter_name.0', 'Isi nama pelapor terlebih dahulu.');

        $this->assertDatabaseCount('reports', 0);
        $this->assertEmpty(session('report_verifications', []));
        Http::assertNothingSent();
    }

    public function test_reporter_identity_is_encrypted_and_not_in_public_tracking(): void
    {
        Storage::fake('local');
        $payload = $this->verifiedPayload(['reporter_name' => ' Nama Rahasia ', 'reporter_email' => ' Pelapor@Example.com ', 'reporter_phone' => '0812 3456 7890']);

        $this->post(route('reports.store'), $payload)->assertRedirect(route('reports.success'));

        $report = Report::query()->sole();
        $this->assertSame('Nama Rahasia', $report->reporter_name);
        $this->assertSame('pelapor@example.com', $report->reporter_email);
        $this->assertSame('+6281234567890', $report->reporter_phone);
        $raw = DB::table('reports')->first();
        $this->assertStringNotContainsString('Nama Rahasia', $raw->reporter_name);
        $this->assertStringNotContainsString('pelapor@example.com', $raw->reporter_email);
        $this->assertArrayNotHasKey('reporter_email', $report->toArray());
        $this->assertArrayNotHasKey('reporter_name', $report->toArray());
        $this->assertArrayNotHasKey('reporter_phone', $report->toArray());
        $this->assertFalse($report->is_ongoing);
        $this->withSession(['tracked_reports' => [$report->id => now()->addHour()->getTimestamp()]])
            ->get(route('reports.status', $report))->assertOk()->assertDontSee('Nama Rahasia')->assertDontSee('pelapor@example.com')->assertDontSee('+6281234567890');
        $this->getJson(route('reports.status.updates', $report))->assertOk()->assertDontSee('pelapor@example.com')->assertDontSee('Nama Rahasia');
    }

    public function test_verified_form_can_be_completed_after_the_turnstile_token_expires(): void
    {
        Storage::fake('local');
        $this->freezeTime();
        $payload = $this->verifiedPayload();
        $this->travel(6)->minutes();

        $this->post(route('reports.store'), $payload)->assertRedirect(route('reports.success'));

        $report = Report::query()->sole();
        $this->assertSame('Pelapor Uji', $report->reporter_name);
        $this->assertNull($report->reporter_phone);
        Http::assertSentCount(1);
    }

    public function test_verification_cannot_be_reused_to_create_another_report(): void
    {
        Storage::fake('local');
        $payload = $this->verifiedPayload();
        $this->post(route('reports.store'), $payload)->assertRedirect(route('reports.success'));

        $this->post(route('reports.store'), $payload)->assertSessionHasErrors('verification_id');

        $this->assertDatabaseCount('reports', 1);
    }

    public function test_verification_from_another_browser_session_is_rejected(): void
    {
        $payload = $this->verifiedPayload();

        $this->withSession(['report_verifications' => []])->post(route('reports.store'), $payload)->assertSessionHasErrors('verification_id');

        $this->assertDatabaseCount('reports', 0);
    }

    public function test_expired_form_verification_is_rejected(): void
    {
        $this->freezeTime();
        $payload = $this->verifiedPayload();
        $this->travel(46)->minutes();

        $this->post(route('reports.store'), $payload)->assertSessionHasErrors('verification_id');

        $this->assertDatabaseCount('reports', 0);
    }

    public function test_identity_changes_require_new_verification(): void
    {
        $payload = $this->verifiedPayload();
        $payload['reporter_email'] = 'orang-lain@example.com';

        $this->post(route('reports.store'), $payload)->assertSessionHasErrors('verification_id');

        $this->assertDatabaseCount('reports', 0);
    }

    #[TestWith(['business_name'])]
    #[TestWith(['incident_time'])]
    #[TestWith(['location_confirmed'])]
    #[TestWith(['reporter_email'])]
    #[TestWith(['reporter_name'])]
    public function test_new_required_fields_cannot_be_bypassed(string $field): void
    {
        $payload = $this->verifiedPayload();
        unset($payload[$field]);

        $this->post(route('reports.store'), $payload)->assertSessionHasErrors($field);

        $this->assertDatabaseCount('reports', 0);
    }

    /** @param array<string, mixed> $identity
     * @return array<string, mixed>
     */
    private function verifiedPayload(array $identity = []): array
    {
        Http::preventStrayRequests();
        Http::fake(['https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => true, 'action' => 'submit_report'])]);
        $identity = ['reporter_name' => 'Pelapor Uji', 'reporter_email' => 'pelapor@example.com', ...$identity];
        $response = $this->postJson(route('reports.verify'), [...$identity, 'cf-turnstile-response' => 'fresh-token']);
        $response->assertOk()->assertJsonStructure(['verification_id', 'expires_at']);
        $this->assertDatabaseCount('reports', 0);

        return [
            ...$identity,
            'verification_id' => $response->json('verification_id'),
            'incident_type' => 'kupva_tanpa_izin',
            'business_name' => 'Ruko dekat pasar Cakranegara',
            'incident_date' => now()->subDay()->toDateString(),
            'incident_time' => '14:30',
            'description' => 'Terlihat aktivitas penukaran uang asing tanpa papan izin di ruko dekat pasar.',
            'regency' => 'Kota Mataram',
            'latitude' => -8.5830695,
            'longitude' => 116.1161800,
            'location_confirmed' => '1',
            'evidence' => [UploadedFile::fake()->image('bukti.jpg')],
            'good_faith' => '1',
            'is_ongoing' => '1',
        ];
    }
}
