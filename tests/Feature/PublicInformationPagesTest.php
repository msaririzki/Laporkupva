<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicInformationPagesTest extends TestCase
{
    public function test_guide_page_explains_the_anonymous_reporting_flow(): void
    {
        $this->get(route('guide'))
            ->assertOk()
            ->assertSee('Melapor dengan aman dan mudah')
            ->assertSee('Apakah saya harus membuat akun?')
            ->assertSee('Apakah bukti wajib dilampirkan?')
            ->assertSee('minimal satu foto atau PDF')
            ->assertDontSee('Cari pertanyaan atau topik panduan')
            ->assertDontSee('bersifat opsional')
            ->assertSee(route('reports.create'));
    }

    public function test_home_page_labels_the_example_flow_and_uses_current_application_urls(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Contoh alur penanganan')
            ->assertSee('Contoh: tahap 2 dari 6 selesai')
            ->assertSee('status tindak lanjut terbaru')
            ->assertDontSee('secara real-time')
            ->assertDontSee('https://laporkupva.ikydev.com/');
    }

    public function test_privacy_page_explains_which_information_is_and_is_not_collected(): void
    {
        $this->get(route('privacy'))
            ->assertOk()
            ->assertSee('Anonim sejak awal')
            ->assertSee('Data yang tidak diminta')
            ->assertSee('Alamat IP tidak disimpan sebagai bagian dari data laporan.');
    }
}
