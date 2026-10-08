<?php

namespace Tests\Feature;

use App\Models\Kupva;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PublicInformationPagesTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guide_page_explains_the_anonymous_reporting_flow(): void
    {
        $this->get(route('guide'))
            ->assertOk()
            ->assertSee('Melapor dengan aman dan mudah')
            ->assertSee('Apakah saya harus membuat akun?')
            ->assertSee('Nama dan email pelapor wajib diisi')
            ->assertDontSee('Nama dan nomor HP boleh dikosongkan')
            ->assertSee('Apakah bukti wajib dilampirkan?')
            ->assertSee('minimal satu foto atau PDF')
            ->assertSee('Sudah pernah melapor?')
            ->assertSee(route('reports.track'))
            ->assertDontSee('Cari pertanyaan atau topik panduan')
            ->assertDontSee('bersifat opsional')
            ->assertDontSee('kode tiket dan PIN')
            ->assertSee(route('reports.create'));
    }

    public function test_home_page_labels_the_example_flow_and_uses_current_application_urls(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Contoh alur penanganan')
            ->assertSee('Contoh: tahap 2 dari 6 selesai')
            ->assertSee('Laporan Anda terus bergerak')
            ->assertSee('Simpan nomor laporan atau QR')
            ->assertDontSee('secara real-time')
            ->assertDontSee('Laporan dapat dikirim tanpa nama')
            ->assertDontSee('Kantor Perwakilan Bank Indonesia Provinsi NTB ·')
            ->assertDontSee('https://laporkupva.ikydev.com/');
    }

    public function test_public_can_browse_and_filter_active_licensed_kupvas(): void
    {
        $mataram = Kupva::factory()->create([
            'name' => 'PT Nusa Valas Mataram',
            'license_number' => 'KUPVA-NTB-1001',
            'regency' => 'Kota Mataram',
            'license_expires_at' => now()->addYear(),
        ]);
        Kupva::factory()->create([
            'name' => 'PT Samawa Valas',
            'license_number' => 'KUPVA-NTB-1002',
            'regency' => 'Kabupaten Sumbawa',
            'license_expires_at' => now()->addYear(),
        ]);
        Kupva::factory()->create([
            'name' => 'KUPVA Izin Kedaluwarsa',
            'license_status' => 'expired',
        ]);
        Kupva::factory()->create([
            'name' => 'KUPVA Tidak Beroperasi',
            'is_active' => false,
        ]);

        $this->get(route('kupvas.index', [
            'q' => 'Nusa Valas',
            'regency' => 'Kota Mataram',
        ]))
            ->assertOk()
            ->assertSee('Daftar Money Changer Berizin')
            ->assertSee('KUPVA BB adalah istilah resmi')
            ->assertSee('Kenali Money Changer Resmi')
            ->assertSee('Logo resmi')
            ->assertSee('Izin terlihat')
            ->assertSee('Authorized Money Changer')
            ->assertSee('Kurs transparan')
            ->assertSee('id="daftar-kupva"', false)
            ->assertSee('https://www.bi.go.id/id/edukasi/Pages/Penjualan-Valuta-Asing.aspx', false)
            ->assertSee('data-kupva-filters', false)
            ->assertSee('data-custom-select', false)
            ->assertSee('data-auto-submit', false)
            ->assertSee('data-kupva-filter-actions', false)
            ->assertSee($mataram->name)
            ->assertSee($mataram->address)
            ->assertDontSee($mataram->license_number)
            ->assertDontSee('Berlaku sampai')
            ->assertDontSee('Masa berlaku')
            ->assertDontSee('PT Samawa Valas')
            ->assertDontSee('KUPVA Izin Kedaluwarsa')
            ->assertDontSee('KUPVA Tidak Beroperasi');
    }

    public function test_privacy_page_explains_which_information_is_and_is_not_collected(): void
    {
        $this->get(route('privacy'))
            ->assertOk()
            ->assertSee('Identitas pelapor dirahasiakan')
            ->assertSee('Data yang tidak diminta')
            ->assertSee('Penyimpanan bukti terlindungi')
            ->assertSee('Alamat IP tidak disimpan sebagai bagian dari data laporan.')
            ->assertSee('layanan verifikasi anti-bot dapat memproses data teknis akses secara terbatas')
            ->assertSee('Nomor HP bersifat opsional')
            ->assertSee('Nama dan email pelapor wajib diisi')
            ->assertDontSee('Penyimpanan bukti terenkripsi')
            ->assertDontSee('Tanpa jejak pelapor');
    }
}
