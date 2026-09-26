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
            ->assertSee('Login admin')
            ->assertSee('aria-label="Masuk ke portal admin"', false)
            ->assertSee('property="og:site_name" content="TAMBORA"', false)
            ->assertSee('property="og:image" content="'.asset('images/brand/tambora.webp').'"', false)
            ->assertSee('name="twitter:card" content="summary_large_image"', false)
            ->assertSee('rel="icon" type="image/webp" href="'.asset('images/brand/tambora.webp').'"', false)
            ->assertDontSee('images/brand/bank-indonesia-full.webp', false)
            ->assertDontSee('images/brand/bank-indonesia-mark.webp', false);
    }
}
