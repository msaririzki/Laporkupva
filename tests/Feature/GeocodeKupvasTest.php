<?php

namespace Tests\Feature;

use App\Models\Kupva;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;
use Tests\TestCase;

class GeocodeKupvasTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_preview_does_not_save_coordinates_and_reuses_cached_results_when_saving(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::response([$this->searchResult()])]);

        $this->artisan('kupvas:geocode')->assertSuccessful();
        $this->assertNull($kupva->fresh()->latitude);
        $this->artisan('kupvas:geocode', ['--save' => true])->assertSuccessful();

        $this->assertDatabaseHas('kupvas', [
            'id' => $kupva->id, 'latitude' => -8.58, 'longitude' => 116.12,
            'location_source' => 'nominatim', 'location_match_address' => 'Jalan Pejanggik, Cakranegara, Mataram',
        ]);
        Http::assertSentCount(1);
        Sleep::assertSleptTimes(1);
    }

    public function test_existing_coordinates_and_partial_manual_coordinates_are_not_overwritten(): void
    {
        $kupva = Kupva::factory()->create(['latitude' => -8.58, 'longitude' => 116.12, 'location_source' => 'manual']);
        $partial = Kupva::factory()->create(['latitude' => -8.6, 'longitude' => null]);
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::response([$this->searchResult()])]);

        $this->artisan('kupvas:geocode', ['--save' => true])->assertSuccessful();

        Http::assertNothingSent();
        $this->assertSame('-8.5800000', $kupva->fresh()->latitude);
        $this->assertSame('manual', $kupva->fresh()->location_source);
        $this->assertNull($partial->fresh()->longitude);
    }

    public function test_a_town_or_island_center_is_not_saved_as_an_office_location(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::response([
            array_replace($this->searchResult(), ['category' => 'place', 'type' => 'town']),
        ])]);

        $this->artisan('kupvas:geocode', ['--save' => true])->assertSuccessful();

        $this->assertNull($kupva->fresh()->latitude);
        $this->assertNull($kupva->fresh()->location_source);
        Http::assertSent(fn (Request $request): bool => $request['countrycodes'] === 'id' && $request['bounded'] === 1);
        Sleep::assertSleptTimes(Http::recorded()->count());
    }

    public function test_same_named_street_in_another_locality_is_rejected(): void
    {
        $kupva = $this->unlocatedKupva(['address' => 'Jalan Pariwisata, Kuta, Kec. Pujut', 'regency' => 'Kabupaten Lombok Tengah']);
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::response([
            array_replace($this->searchResult(), ['address' => [
                'county' => 'Lombok Tengah', 'village' => 'Aik Bukak', 'state' => 'Nusa Tenggara Barat', 'country_code' => 'id',
            ]]),
        ])]);

        $this->artisan('kupvas:geocode', ['--save' => true])->assertSuccessful();

        $this->assertNull($kupva->fresh()->latitude);
        Http::assertSent(fn (Request $request): bool => $request['countrycodes'] === 'id' && $request['bounded'] === 1);
        Sleep::assertSleptTimes(Http::recorded()->count());
    }

    public function test_coordinates_outside_ntb_or_in_the_wrong_regency_are_rejected(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::response([
            array_replace($this->searchResult(), ['lat' => '-6.2', 'lon' => '106.8']),
            array_replace($this->searchResult(), ['address' => [
                'county' => 'Sumbawa', 'state' => 'Nusa Tenggara Barat', 'country_code' => 'id',
            ]]),
        ])]);

        $this->artisan('kupvas:geocode', ['--save' => true])->assertSuccessful();

        $this->assertNull($kupva->fresh()->latitude);
        Http::assertSent(fn (Request $request): bool => $request['countrycodes'] === 'id' && $request['bounded'] === 1);
        Sleep::assertSleptTimes(Http::recorded()->count());
    }

    public function test_a_long_road_is_rejected_because_its_center_is_not_specific_enough(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::response([
            array_replace($this->searchResult(), ['category' => 'highway', 'type' => 'primary', 'boundingbox' => ['-8.7', '-8.5', '116', '116.2']]),
        ])]);

        $this->artisan('kupvas:geocode', ['--save' => true])->assertSuccessful();

        $this->assertNull($kupva->fresh()->latitude);
        Http::assertSent(fn (Request $request): bool => $request['countrycodes'] === 'id' && $request['bounded'] === 1);
        Sleep::assertSleptTimes(Http::recorded()->count());
    }

    public function test_provider_failure_keeps_the_record_unlocated(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::response([], 429)]);

        $this->artisan('kupvas:geocode', ['--save' => true])->assertFailed();

        $this->assertNull($kupva->fresh()->latitude);
        Http::assertSentCount(1);
        Sleep::assertSleptTimes(1);
    }

    public function test_connection_failure_keeps_the_record_unlocated(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::failedConnection()]);

        $this->artisan('kupvas:geocode', ['--save' => true])->assertFailed();

        $this->assertNull($kupva->fresh()->latitude);
        Http::assertSentCount(1);
        Sleep::assertSleptTimes(1);
    }

    /** @param array<string, mixed> $attributes */
    private function unlocatedKupva(array $attributes = []): Kupva
    {
        return Kupva::factory()->create(array_replace([
            'name' => 'PT Valas BI', 'address' => 'Jalan Pejanggik No. 32', 'regency' => 'Kota Mataram',
            'district' => null, 'village' => null, 'latitude' => null, 'longitude' => null,
        ], $attributes));
    }

    /** @return array<string, mixed> */
    private function searchResult(): array
    {
        return [
            'lat' => '-8.58', 'lon' => '116.12', 'category' => 'shop', 'type' => 'money_changer',
            'display_name' => 'Jalan Pejanggik, Cakranegara, Mataram',
            'address' => ['city' => 'Mataram', 'suburb' => 'Cakranegara', 'state' => 'Nusa Tenggara Barat', 'country_code' => 'id'],
        ];
    }
}
