<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Enums\UserRole;
use App\Filament\Auth\EditProfile;
use App\Filament\Resources\Kupvas\KupvaResource;
use App\Filament\Resources\Reports\Pages\ViewReport;
use App\Filament\Resources\Reports\ReportResource;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Widgets\MonthlyReportTrend;
use App\Models\AnonymousMessage;
use App\Models\Kupva;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_panel_uses_spa_navigation_without_intercepting_file_responses(): void
    {
        $panel = Filament::getPanel('admin');
        $publicPortalItem = collect($panel->getNavigationItems())
            ->first(fn (NavigationItem $item): bool => $item->getLabel() === 'Portal Publik');

        $this->assertTrue($panel->hasSpaMode());
        $this->assertFalse($panel->hasSpaPrefetching());
        $this->assertSame(asset('images/brand/tambora.webp'), $panel->getFavicon());
        $this->assertSame([
            url('/admin/ekspor/*'),
            url('/admin/lampiran-laporan/*'),
        ], $panel->getSpaUrlExceptions());
        $this->assertNotNull($publicPortalItem);
        $this->assertSame(route('home'), $publicPortalItem->getUrl());
        $this->assertTrue($publicPortalItem->shouldOpenUrlInNewTab());
    }

    public function test_guest_sees_the_branded_admin_login_page(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('Masuk ke TAMBORA')
            ->assertSee('Gunakan akun admin Anda.')
            ->assertSee('Portal internal')
            ->assertSee('Kelola laporan dengan lebih terarah.')
            ->assertSee('Akses terlindungi')
            ->assertSee('Email admin')
            ->assertSee('Masuk')
            ->assertSee('Kembali ke beranda')
            ->assertSee(route('home'), false)
            ->assertDontSee('Ruang kerja');
    }

    public function test_mfa_setup_is_unavailable_while_admin_mfa_is_disabled(): void
    {
        $this->get('/admin/multi-factor-authentication/set-up')
            ->assertNotFound();
    }

    public function test_admin_profile_uses_the_admin_layout_and_a_local_avatar(): void
    {
        $admin = User::factory()->create([
            'name' => 'Sari Test',
            'email' => 'sari@example.test',
        ]);

        $this->actingAs($admin)
            ->get('/admin/profile')
            ->assertSee('Profil saya')
            ->assertSee('Informasi profil')
            ->assertSee('Dikompres otomatis')
            ->assertSee('hingga 10 MB')
            ->assertSee('tambora-profile-photo-panel', false)
            ->assertSee('Keamanan akun')
            ->assertSee('Akun admin TAMBORA')
            ->assertSee('data:image/svg+xml;base64,', false)
            ->assertSee('fi-sidebar', false);
    }

    public function test_admin_can_upload_and_replace_their_profile_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('admin-avatars/avatar-lama.jpg', 'old-avatar');
        $admin = User::factory()->create([
            'avatar_path' => 'admin-avatars/avatar-lama.jpg',
        ]);

        $this->actingAs($admin);

        Livewire::test(EditProfile::class)
            ->fillForm([
                'name' => $admin->name,
                'email' => $admin->email,
                'avatar_path' => [
                    UploadedFile::fake()->image('avatar-baru.jpg', 2400, 2400)->size(8192),
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertDispatched('refresh-topbar')
            ->assertNotified();

        $avatarPath = $admin->fresh()->avatar_path;

        $this->assertNotNull($avatarPath);
        $this->assertNotSame('admin-avatars/avatar-lama.jpg', $avatarPath);
        Storage::disk('public')->assertExists($avatarPath);
        Storage::disk('public')->assertMissing('admin-avatars/avatar-lama.jpg');
    }

    public function test_admin_profile_rejects_svg_avatar_uploads(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $this->actingAs($admin);

        Livewire::test(EditProfile::class)
            ->fillForm([
                'name' => $admin->name,
                'email' => $admin->email,
                'avatar_path' => [
                    UploadedFile::fake()->create('avatar.svg', 100, 'image/svg+xml'),
                ],
            ])
            ->call('save')
            ->assertHasFormErrors(['avatar_path']);

        $this->assertNull($admin->fresh()->avatar_path);
    }

    public function test_admin_can_open_dashboard_and_report_list(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $report = Report::factory()->create([
            'status' => ReportStatus::Received,
            'latitude' => -8.5830695,
            'longitude' => 116.1161800,
        ]);
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
        $kupva = Kupva::factory()->create();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Ringkasan Laporan')
            ->assertDontSee('Ringkasan laporan masyarakat dan aktivitas penanganan di Nusa Tenggara Barat.')
            ->assertDontSee('Pusat kendali TAMBORA')
            ->assertDontSee('Selamat datang, '.$admin->name)
            ->assertDontSee('Ringkasan pengawasan')
            ->assertSee('Tindakan lapangan')
            ->assertSee('Peta laporan')
            ->assertSee('Tren 6 bulan')
            ->assertSee('data-dashboard-chart="monthly-report-trend"', false)
            ->assertSee('data-dashboard-chart="regional-report-chart"', false)
            ->assertSee('aria-expanded="false"', false)
            ->assertSee('Status laporan')
            ->assertSee('Laporan per wilayah')
            ->assertSee('Laporan terbaru')
            ->assertSee('Lihat semua')
            ->assertDontSee('Pencarian global');

        $this->actingAs($admin)
            ->get(ReportResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Laporan masyarakat')
            ->assertSee('Temukan dan tindak lanjuti laporan masyarakat di seluruh NTB.')
            ->assertSee('Cari laporan…')
            ->assertSee('Saring')
            ->assertSee('Atur kolom')
            ->assertSee('Unduh CSV')
            ->assertSee('Buka')
            ->assertSee('Lanjutkan');

        $this->actingAs($admin)
            ->get(ReportResource::getUrl('view', ['record' => $report]))
            ->assertOk()
            ->assertSee($report->public_code)
            ->assertSee('Status penanganan')
            ->assertSee('Kembali ke daftar')
            ->assertSee(ReportResource::getUrl('index'), false)
            ->assertSee('Update progres')
            ->assertSee('Tambah dokumentasi')
            ->assertSee('Sudah dikerjakan')
            ->assertSee('Sedang dikerjakan')
            ->assertSee('Belum dikerjakan')
            ->assertDontSee("Detail laporan anonim · {$report->regency}")
            ->assertDontSee('Laporan sudah diterima dan mulai diproses.')
            ->assertSee('data-progress-state="completed"', false)
            ->assertSee('data-progress-state="current"', false)
            ->assertSee('aria-current="step"', false)
            ->assertSee('bukti-lokasi.jpg')
            ->assertSee('Lokasi berada dekat pasar.')
            ->assertSee('Buka di Google Maps')
            ->assertSee('https://www.google.com/maps/dir/?api=1&destination=-8.5830695%2C116.1161800&travelmode=driving');

        $this->actingAs($admin)
            ->get(KupvaResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Data KUPVA')
            ->assertSee('Kelola referensi penyelenggara KUPVA dan pantau status izin operasionalnya.');

        $this->actingAs($admin)
            ->get(KupvaResource::getUrl('view', ['record' => $kupva]))
            ->assertOk()
            ->assertSee('Kembali ke daftar KUPVA')
            ->assertSee(KupvaResource::getUrl('index'), false);
    }

    public function test_admin_can_filter_the_report_trend_by_relative_period_or_specific_month(): void
    {
        $this->travelTo('2026-09-26 12:00:00');
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin);

        Livewire::test(MonthlyReportTrend::class)
            ->assertSet('filter', 'last_6_months')
            ->assertSee('Tren 6 bulan')
            ->assertSee('Bulan ini')
            ->assertSee('Bulan tertentu · Agustus 2026')
            ->set('filter', 'this_month')
            ->assertSee('Tren bulan ini')
            ->assertSee('Jumlah laporan masuk per hari.')
            ->set('filter', 'month_2026-08')
            ->assertSee('Tren Agustus 2026')
            ->set('filter', 'month_2026-99')
            ->assertSee('Tren 6 bulan');
    }

    public function test_regular_admin_cannot_manage_other_admin_accounts(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertForbidden();
    }

    public function test_dashboard_shows_status_percentages_without_hovering(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        Report::factory()->create(['status' => ReportStatus::Submitted]);
        Report::factory()->count(3)->create(['status' => ReportStatus::Completed]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertSee('Status laporan')
            ->assertSee('75,0%')
            ->assertSee('data-status-percentage="75,0%"', false)
            ->assertDontSee('Kondisi laporan masyarakat yang diperbarui secara berkala.');
    }

    public function test_admin_can_send_an_anonymous_message_from_report_detail(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $report = Report::factory()->create();

        $this->actingAs($admin);

        Livewire::test('admin.report-conversation', ['record' => $report])
            ->set('body', 'Mohon tambahkan patokan lokasi yang lebih jelas.')
            ->call('send')
            ->assertHasNoErrors();

        $this->assertDatabaseHas(AnonymousMessage::class, [
            'report_id' => $report->getKey(),
            'user_id' => $admin->getKey(),
            'sender_type' => 'admin',
            'body' => 'Mohon tambahkan patokan lokasi yang lebih jelas.',
        ]);
    }

    public function test_admin_can_update_report_progress_from_report_detail(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $report = Report::factory()->create();

        $this->actingAs($admin);

        Livewire::test(ViewReport::class, ['record' => $report->getRouteKey()])
            ->callAction('advanceStatus', [
                'public_note' => 'Pemeriksaan awal telah selesai dilakukan.',
            ])
            ->assertNotified();

        $this->assertDatabaseHas(Report::class, [
            'id' => $report->getKey(),
            'status' => ReportStatus::Received->value,
            'public_update' => 'Pemeriksaan awal telah selesai dilakukan.',
        ]);
        $this->assertDatabaseHas('report_status_histories', [
            'report_id' => $report->getKey(),
            'user_id' => $admin->getKey(),
            'from_status' => ReportStatus::Submitted->value,
            'to_status' => ReportStatus::Received->value,
        ]);
    }

    public function test_admin_can_attach_private_activity_photos_when_updating_progress(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $report = Report::factory()->received()->create();

        $this->actingAs($admin);

        Livewire::test(ViewReport::class, ['record' => $report->getRouteKey()])
            ->callAction('advanceStatus', [
                'public_note' => 'Koordinasi dengan APH sedang dilakukan.',
                'internal_note' => 'Koordinasi dilakukan bersama tim pengawasan.',
                'activity_photos' => [
                    UploadedFile::fake()->image('koordinasi-aph.jpg', 1600, 1200)->size(900),
                ],
            ])
            ->assertNotified();

        $evidence = ReportEvidence::query()
            ->where('report_id', $report->getKey())
            ->where('source', 'admin_activity')
            ->firstOrFail();

        $this->assertSame($admin->getKey(), $evidence->uploaded_by_user_id);
        $this->assertNotNull($evidence->report_status_history_id);
        $this->assertSame('Koordinasi dilakukan bersama tim pengawasan.', $evidence->caption);
        Storage::disk('local')->assertExists($evidence->path);

        $this->get(ReportResource::getUrl('view', ['record' => $report]))
            ->assertOk()
            ->assertSee('Bukti kegiatan petugas')
            ->assertSee('Koordinasi dilakukan bersama tim pengawasan.')
            ->assertSee(route('admin.report-evidence.preview', $evidence), false)
            ->assertSee('previewActivityEvidence', false)
            ->assertDontSee(route('admin.report-evidence.download', $evidence), false);

        $this->assertDatabaseHas('report_status_histories', [
            'id' => $evidence->report_status_history_id,
            'report_id' => $report->getKey(),
            'to_status' => ReportStatus::Coordination->value,
            'internal_note' => 'Koordinasi dilakukan bersama tim pengawasan.',
        ]);
    }

    public function test_reporter_image_opens_in_an_in_page_preview_instead_of_downloading(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $report = Report::factory()->create();
        $evidence = ReportEvidence::factory()->create([
            'report_id' => $report,
            'original_name' => 'foto-lokasi.jpg',
            'mime_type' => 'image/jpeg',
        ]);

        $this->actingAs($admin)
            ->get(ReportResource::getUrl('view', ['record' => $report]))
            ->assertSee('previewSubmissionEvidence', false)
            ->assertSee(route('admin.report-evidence.preview', $evidence), false)
            ->assertSee('Klik foto untuk memperbesar')
            ->assertDontSee(route('admin.report-evidence.download', $evidence), false);
    }

    public function test_reporter_pdf_remains_a_download_only_attachment(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $report = Report::factory()->create();
        $evidence = ReportEvidence::factory()->create([
            'report_id' => $report,
            'original_name' => 'dokumen.pdf',
            'mime_type' => 'application/pdf',
        ]);

        $this->actingAs($admin)
            ->get(ReportResource::getUrl('view', ['record' => $report]))
            ->assertSee(route('admin.report-evidence.download', $evidence), false)
            ->assertDontSee(route('admin.report-evidence.preview', $evidence), false);
    }

    public function test_admin_can_add_activity_photos_without_changing_progress(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $report = Report::factory()->received()->create();
        $history = $report->statusHistories()->create([
            'user_id' => $admin->getKey(),
            'from_status' => ReportStatus::Submitted,
            'to_status' => ReportStatus::Received,
            'public_note' => 'Laporan diterima.',
        ]);

        $this->actingAs($admin);

        Livewire::test(ViewReport::class, ['record' => $report->getRouteKey()])
            ->callAction('addActivityEvidence', [
                'caption' => 'Dokumentasi pemeriksaan berkas tambahan.',
                'activity_photos' => [
                    UploadedFile::fake()->image('pemeriksaan-berkas.jpg', 1200, 900)->size(700),
                ],
            ])
            ->assertNotified();

        $report->refresh();
        $evidence = $report->activityEvidence()->firstOrFail();

        $this->assertSame(ReportStatus::Received, $report->status);
        $this->assertSame($history->getKey(), $evidence->report_status_history_id);
        $this->assertSame('Dokumentasi pemeriksaan berkas tambahan.', $evidence->caption);
        Storage::disk('local')->assertExists($evidence->path);
    }

    public function test_only_super_admin_can_correct_a_report_status_with_a_reason(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $report = Report::factory()->create([
            'status' => ReportStatus::FieldAction,
            'received_at' => now()->subDays(3),
            'coordinated_at' => now()->subDays(2),
            'field_action_at' => now()->subDay(),
        ]);

        $this->actingAs($superAdmin);

        Livewire::test(ViewReport::class, ['record' => $report->getRouteKey()])
            ->callAction('correctStatus', [
                'status' => ReportStatus::Received->value,
                'reason' => 'Tahap kunjungan dipilih sebelum koordinasi terkonfirmasi.',
                'public_note' => 'Status disesuaikan setelah pemeriksaan administrasi.',
            ])
            ->assertNotified();

        $report->refresh();

        $this->assertSame(ReportStatus::Received, $report->status);
        $this->assertNull($report->coordinated_at);
        $this->assertNull($report->field_action_at);
        $this->assertDatabaseHas('report_status_histories', [
            'report_id' => $report->getKey(),
            'user_id' => $superAdmin->getKey(),
            'from_status' => ReportStatus::FieldAction->value,
            'to_status' => ReportStatus::Received->value,
            'internal_note' => 'Koreksi status: Tahap kunjungan dipilih sebelum koordinasi terkonfirmasi.',
        ]);
    }

    public function test_regular_admin_cannot_see_the_status_correction_action(): void
    {
        $admin = User::factory()->create();
        $report = Report::factory()->received()->create();

        $this->actingAs($admin);

        Livewire::test(ViewReport::class, ['record' => $report->getRouteKey()])
            ->assertActionHidden('correctStatus');
    }

    public function test_super_admin_can_open_admin_account_management(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get(UserResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Manajemen admin')
            ->assertSee('Atur akun dan akses admin yang membantu proses pengawasan.');
    }

    public function test_super_admin_cannot_create_an_admin_with_a_weak_password(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Admin Baru',
                'email' => 'admin-baru@example.test',
                'password' => 'password123',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasFormErrors(['password']);

        $this->assertDatabaseMissing(User::class, [
            'email' => 'admin-baru@example.test',
        ]);
    }

    public function test_super_admin_sees_the_streamlined_create_admin_form(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get(UserResource::getUrl('create'))
            ->assertOk()
            ->assertSee('Tambah admin')
            ->assertSee('Informasi admin')
            ->assertSee('Simpan admin')
            ->assertDontSee('Buat &amp; buat lainnya', false);
    }

    public function test_super_admin_can_deactivate_an_admin_without_deleting_the_account(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $admin = User::factory()->create(['is_active' => true]);

        $this->actingAs($superAdmin);

        Livewire::test(EditUser::class, ['record' => $admin->getRouteKey()])
            ->fillForm(['is_active' => false])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas(User::class, [
            'id' => $admin->getKey(),
            'is_active' => false,
        ]);
    }

    public function test_inactive_admin_cannot_access_the_admin_panel(): void
    {
        $admin = User::factory()->create(['is_active' => false]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_mfa_secrets_are_encrypted_and_hidden_from_serialization(): void
    {
        $admin = User::factory()->create();

        $admin->saveAppAuthenticationSecret('totp-secret');
        $admin->saveAppAuthenticationRecoveryCodes(['recovery-code']);
        $serializedAdmin = $admin->fresh()->toArray();

        $this->assertArrayNotHasKey('app_authentication_secret', $serializedAdmin);
        $this->assertArrayNotHasKey('app_authentication_recovery_codes', $serializedAdmin);
        $this->assertDatabaseMissing(User::class, [
            'id' => $admin->getKey(),
            'app_authentication_secret' => 'totp-secret',
            'app_authentication_recovery_codes' => json_encode(['recovery-code']),
        ]);
    }
}
