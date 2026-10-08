<?php

namespace Tests\Feature;

use App\Models\Kupva;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class PublicKupvaMapTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_map_is_the_default_and_contains_results_beyond_the_card_page(): void
    {
        Kupva::factory()->count(13)->create();

        $this->get(route('kupvas.index'))
            ->assertOk()
            ->assertViewHas('displayMode', 'map')
            ->assertViewHas('mapKupvas', fn (Collection $kupvas): bool => $kupvas->count() === 13)
            ->assertViewHas('kupvas', fn ($kupvas): bool => $kupvas->count() === 12 && $kupvas->total() === 13)
            ->assertSee('data-public-kupva-map', false)
            ->assertSee('Gunakan lokasi saya');
    }

    public function test_map_exposes_only_public_office_information_and_keeps_missing_coordinates_null(): void
    {
        $kupva = Kupva::factory()->create([
            'name' => 'PT Valas BI', 'license_number' => 'IZIN-RAHASIA-123',
            'address' => 'Jalan Pejanggik 32', 'regency' => 'Kota Mataram',
            'latitude' => null, 'longitude' => null,
        ]);

        $this->get(route('kupvas.index'))
            ->assertOk()
            ->assertViewHas('mapKupvas', fn (Collection $kupvas): bool => $kupvas->all() === [[
                'id' => $kupva->id, 'name' => 'PT Valas BI', 'address' => 'Jalan Pejanggik 32',
                'latitude' => null, 'longitude' => null,
                'approximate' => false,
                'area' => false,
            ]])
            ->assertSee('PT Valas BI')
            ->assertSee('Jalan Pejanggik 32')
            ->assertDontSee('IZIN-RAHASIA-123')
            ->assertDontSee('license_expires_at')
            ->assertDontSee('license_status');
    }

    public function test_search_matches_regency_village_and_district_without_an_area_in_the_address(): void
    {
        $mataram = Kupva::factory()->create([
            'name' => 'PT Valas Kota', 'address' => 'Jalan Utama 10',
            'regency' => 'Kota Mataram', 'village' => 'Kelurahan Cilinaya', 'district' => 'Cakranegara',
        ]);
        $other = Kupva::factory()->create(['name' => 'PT Valas Samawa', 'address' => 'Jalan Utama Sumbawa', 'regency' => 'Kabupaten Sumbawa', 'district' => 'Sumbawa', 'village' => 'Brang Biji']);

        foreach (['Mataram', 'Cilinaya', 'Cakranegara'] as $search) {
            $this->get(route('kupvas.index', ['q' => $search]))
                ->assertOk()
                ->assertViewHas('mapKupvas', fn (Collection $kupvas): bool => $kupvas->pluck('id')->all() === [$mataram->id])
                ->assertDontSee($other->name);
        }
    }

    public function test_map_and_cards_apply_the_same_active_license_filters(): void
    {
        $active = Kupva::factory()->create(['license_number' => null, 'license_expires_at' => null]);
        Kupva::factory()->create(['name' => 'Usaha berhenti', 'is_active' => false]);
        Kupva::factory()->create(['name' => 'Izin dibekukan', 'license_status' => 'suspended']);
        Kupva::factory()->create(['name' => 'Tanggal lewat', 'license_expires_at' => today()->subDay()]);

        foreach (['map', 'cards'] as $mode) {
            $this->get(route('kupvas.index', ['view' => $mode]))
                ->assertOk()
                ->assertSee($active->name)
                ->assertDontSee('Usaha berhenti')
                ->assertDontSee('Izin dibekukan')
                ->assertDontSee('Tanggal lewat');
        }
    }

    public function test_cards_are_available_as_a_second_view_and_keep_search_filters(): void
    {
        Kupva::factory()->create(['regency' => 'Kota Mataram']);

        $this->get(route('kupvas.index', ['view' => 'cards', 'q' => 'Mataram', 'regency' => 'Kota Mataram']))
            ->assertOk()
            ->assertViewHas('displayMode', 'cards')
            ->assertViewHas('mapKupvas', fn (Collection $kupvas): bool => $kupvas->isEmpty())
            ->assertSee('value="cards"', false)
            ->assertSee(route('kupvas.index', ['q' => 'Mataram', 'regency' => 'Kota Mataram', 'view' => 'map']))
            ->assertDontSee('data-public-kupva-map', false);
    }

    public function test_unknown_view_defaults_to_map_and_an_empty_search_shows_a_helpful_message(): void
    {
        $this->get(route('kupvas.index', ['view' => 'unknown', 'q' => 'Tidak ada']))
            ->assertOk()
            ->assertViewHas('displayMode', 'map')
            ->assertSee('Data tidak ditemukan')
            ->assertSee('Coba gunakan nama atau wilayah lain.');
    }

    public function test_map_json_cannot_turn_an_office_name_into_an_executable_script(): void
    {
        Kupva::factory()->create(['name' => '</script><script>alert("test")</script>']);

        $this->get(route('kupvas.index'))
            ->assertOk()
            ->assertDontSee('</script><script>alert("test")</script>', false)
            ->assertSee('\\u003C', false);
    }

    public function test_public_map_keeps_location_accuracy_details_for_staff(): void
    {
        $kupva = Kupva::factory()->create(['location_source' => 'nominatim', 'location_match_address' => 'Detail internal pencarian alamat']);

        $this->get(route('kupvas.index'))
            ->assertOk()
            ->assertDontSee('Perkiraan lokasi dari alamat')
            ->assertDontSee('Penanda oranye')
            ->assertDontSee('Detail internal pencarian alamat')
            ->assertViewHas('mapKupvas', fn (Collection $kupvas): bool => $kupvas->first()['approximate'] === true);

        $kupva->update(['location_source' => 'manual']);

        $this->get(route('kupvas.index'))
            ->assertOk()
            ->assertDontSee('Perkiraan lokasi dari alamat')
            ->assertViewHas('mapKupvas', fn (Collection $kupvas): bool => $kupvas->first()['approximate'] === false);
    }

    #[TestWith(['map'])]
    #[TestWith(['cards'])]
    public function test_google_maps_links_open_the_verified_coordinates_in_both_views(string $mode): void
    {
        Kupva::factory()->create([
            'name' => 'PT Valas Kota', 'address' => 'Jalan Pejanggik 32',
            'latitude' => -8.5830695, 'longitude' => 116.11618, 'location_source' => 'manual',
        ]);

        $this->get(route('kupvas.index', ['view' => $mode]))
            ->assertSee('href="https://www.google.com/maps/search/?api=1&amp;query=-8.5830695%2C116.11618"', false)
            ->assertSee('aria-label="Buka lokasi PT Valas Kota di Google Maps (tab baru)"', false)
            ->assertSee('target="_blank" rel="noopener noreferrer" data-kupva-google-maps', false);
    }

    #[TestWith(['map', 'nominatim'])]
    #[TestWith(['map', 'nominatim_area'])]
    #[TestWith(['cards', 'nominatim'])]
    #[TestWith(['cards', 'nominatim_area'])]
    public function test_google_maps_searches_the_business_address_instead_of_an_unverified_point(string $mode, string $source): void
    {
        Kupva::factory()->create([
            'name' => 'PT Valas Kota', 'address' => 'Jalan Pejanggik 32',
            'latitude' => -8.4, 'longitude' => 116.4, 'location_source' => $source,
        ]);

        $this->get(route('kupvas.index', ['view' => $mode]))
            ->assertSee('href="https://www.google.com/maps/search/?api=1&amp;query=PT%20Valas%20Kota%2C%20Jalan%20Pejanggik%2032"', false)
            ->assertDontSee('query=-8.4%2C116.4', false);
    }

    #[TestWith(['map'])]
    #[TestWith(['cards'])]
    public function test_google_maps_remains_available_without_coordinates_or_a_street_address(string $mode): void
    {
        Kupva::factory()->create([
            'name' => 'PT Valas Kota', 'address' => null, 'district' => 'Cakranegara', 'village' => 'Cilinaya', 'regency' => 'Kota Mataram',
            'latitude' => null, 'longitude' => null,
        ]);

        $this->get(route('kupvas.index', ['view' => $mode]))
            ->assertSee('href="https://www.google.com/maps/search/?api=1&amp;query=PT%20Valas%20Kota%2C%20Cilinaya%2C%20Cakranegara%2C%20Kota%20Mataram"', false);
    }

    #[TestWith(['map'])]
    #[TestWith(['cards'])]
    public function test_google_maps_link_encodes_office_text_without_turning_it_into_url_parameters_or_html(string $mode): void
    {
        Kupva::factory()->create([
            'name' => 'PT "Valas" & Mitra', 'address' => 'Jalan Utama &query=evil',
            'latitude' => null, 'longitude' => null,
        ]);

        $this->get(route('kupvas.index', ['view' => $mode]))
            ->assertSee('href="https://www.google.com/maps/search/?api=1&amp;query=PT%20%22Valas%22%20%26%20Mitra%2C%20Jalan%20Utama%20%26query%3Devil"', false)
            ->assertSee('aria-label="Buka lokasi PT &quot;Valas&quot; &amp; Mitra di Google Maps (tab baru)"', false);
    }
}
