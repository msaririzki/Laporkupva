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
            ->assertSee('Sudah pernah melapor?')
            ->assertSee(route('reports.track'))
            ->assertSee(route('reports.create'));
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
