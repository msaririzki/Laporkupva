<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_explains_the_anonymous_reporting_flow(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Berani melapor')
            ->assertSee('Tanpa nama &amp; NIK', false)
            ->assertSee(route('reports.create'))
            ->assertSee(route('reports.track'))
            ->assertSee(route('filament.admin.auth.login'))
            ->assertSee('aria-label="Masuk ke portal admin"', false);
    }
}
