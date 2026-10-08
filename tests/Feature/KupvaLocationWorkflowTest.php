<?php

namespace Tests\Feature;

use App\Filament\Resources\Kupvas\KupvaGeocoder;
use App\Filament\Resources\Kupvas\Pages\ViewKupva;
use App\Jobs\ResolveKupvaLocation;
use App\Models\Kupva;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Sleep;
use Livewire\Livewire;
use Tests\TestCase;

class KupvaLocationWorkflowTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_an_island_in_the_bi_address_can_be_mapped_as_an_explicit_area_estimate(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => fn (Request $request) => Http::response(
            str_starts_with($request['q'], 'Gili Trawangan,') ? [$this->areaResult()] : [],
        )]);

        (new ResolveKupvaLocation($kupva->id))->handle(app(KupvaGeocoder::class));

        $this->assertDatabaseHas('kupvas', ['id' => $kupva->id, 'latitude' => -8.3503, 'longitude' => 116.0362, 'location_source' => 'nominatim_area']);
        $this->get(route('kupvas.index'))
            ->assertOk()->assertDontSee('Perkiraan area alamat')->assertDontSee('Lokasi usaha perlu diperiksa');
        $this->actingAs(User::factory()->superAdmin()->create());
        Livewire::test(ViewKupva::class, ['record' => $kupva->id])
            ->assertSee('Perkiraan area alamat')->assertSee('lokasi usaha perlu diperiksa');
        Http::assertSent(fn (Request $request): bool => $request['q'] === 'Gili Trawangan, Kabupaten Lombok Utara, Nusa Tenggara Barat, Indonesia');
        Sleep::assertSleptTimes(Http::recorded()->count());
    }

    public function test_a_different_island_is_not_substituted_for_the_area_named_in_the_address(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::response([
            array_replace($this->areaResult(), ['name' => 'Gili Air']),
        ])]);

        (new ResolveKupvaLocation($kupva->id))->handle(app(KupvaGeocoder::class));

        $this->assertNull($kupva->fresh()->latitude);
        Http::assertSent(fn (Request $request): bool => $request['countrycodes'] === 'id');
        Sleep::assertSleptTimes(Http::recorded()->count());
    }

    public function test_an_address_in_a_shopping_complex_can_resolve_the_area_of_the_street_it_mentions(): void
    {
        $kupva = Kupva::factory()->create([
            'address' => 'Komp. Pertokoan Terara Blok B No. 1, Jl. Raya Terara, Lombok Timur',
            'regency' => 'Kabupaten Lombok Timur', 'district' => null, 'village' => null, 'latitude' => null, 'longitude' => null,
        ]);
        Sleep::fake();
        Http::preventStrayRequests();
        $area = array_replace($this->areaResult(), [
            'name' => 'Terara', 'addresstype' => 'village', 'type' => 'village',
            'address' => ['county' => 'Lombok Timur', 'state' => 'Nusa Tenggara Barat', 'country_code' => 'id'],
        ]);
        Http::fake(['nominatim.openstreetmap.org/search*' => fn (Request $request) => Http::response(str_starts_with($request['q'], 'Terara,') ? [$area] : [])]);

        (new ResolveKupvaLocation($kupva->id))->handle(app(KupvaGeocoder::class));

        $this->assertSame('nominatim_area', $kupva->fresh()->location_source);
        Http::assertSent(fn (Request $request): bool => $request['q'] === 'Terara, Kabupaten Lombok Timur, Nusa Tenggara Barat, Indonesia');
        Sleep::assertSleptTimes(Http::recorded()->count());
    }

    public function test_an_area_with_a_province_sized_extent_is_rejected_even_if_its_name_matches(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::response([
            array_replace($this->areaResult(), ['boundingbox' => ['-9.5', '-8', '115', '120']]),
        ])]);

        (new ResolveKupvaLocation($kupva->id))->handle(app(KupvaGeocoder::class));

        $this->assertNull($kupva->fresh()->latitude);
        Http::assertSent(fn (Request $request): bool => $request['bounded'] === 1);
        Sleep::assertSleptTimes(Http::recorded()->count());
    }

    public function test_the_province_center_is_not_used_when_a_small_area_cannot_be_resolved(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => Http::response([
            array_replace($this->areaResult(), ['name' => 'Nusa Tenggara Barat', 'addresstype' => 'state']),
        ])]);

        (new ResolveKupvaLocation($kupva->id))->handle(app(KupvaGeocoder::class));

        $this->assertNull($kupva->fresh()->latitude);
        Http::assertSent(fn (Request $request): bool => $request['bounded'] === 1);
        Sleep::assertSleptTimes(Http::recorded()->count());
    }

    public function test_the_job_preserves_a_coordinate_entered_while_the_address_lookup_is_running(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => function () use ($kupva) {
            $kupva->update(['latitude' => -8.35, 'longitude' => 116.03, 'location_source' => 'manual']);

            return Http::response([$this->areaResult()]);
        }]);

        (new ResolveKupvaLocation($kupva->id))->handle(app(KupvaGeocoder::class));

        $this->assertSame('manual', $kupva->fresh()->location_source);
        $this->assertSame('-8.3500000', $kupva->fresh()->latitude);
        Http::assertSent(fn (Request $request): bool => $request['countrycodes'] === 'id');
        Sleep::assertSleptTimes(Http::recorded()->count());
    }

    public function test_an_address_changed_during_lookup_does_not_receive_coordinates_for_the_old_address(): void
    {
        $kupva = $this->unlocatedKupva();
        Sleep::fake();
        Http::preventStrayRequests();
        Http::fake(['nominatim.openstreetmap.org/search*' => function () use ($kupva) {
            $kupva->update(['address' => 'Gili Air, Lombok Utara']);

            return Http::response([$this->areaResult()]);
        }]);

        (new ResolveKupvaLocation($kupva->id))->handle(app(KupvaGeocoder::class));

        $this->assertNull($kupva->fresh()->latitude);
        Http::assertSent(fn (Request $request): bool => $request['countrycodes'] === 'id');
        Sleep::assertSleptTimes(Http::recorded()->count());
    }

    public function test_an_admin_can_queue_an_address_lookup_from_the_office_page(): void
    {
        $kupva = $this->unlocatedKupva();
        $this->actingAs(User::factory()->superAdmin()->create());
        Queue::fake([ResolveKupvaLocation::class]);

        Livewire::test(ViewKupva::class, ['record' => $kupva->id])
            ->callAction('findLocation')->assertHasNoActionErrors();

        Queue::assertPushed(ResolveKupvaLocation::class, fn (ResolveKupvaLocation $job): bool => $job->kupvaId === $kupva->id);
    }

    public function test_police_cannot_change_office_coordinates(): void
    {
        $kupva = $this->unlocatedKupva();
        $this->actingAs(User::factory()->police()->create());
        Queue::fake([ResolveKupvaLocation::class]);

        Livewire::test(ViewKupva::class, ['record' => $kupva->id])
            ->assertActionHidden('findLocation');

        Queue::assertNothingPushed();
    }

    private function unlocatedKupva(): Kupva
    {
        return Kupva::factory()->create([
            'name' => 'PT Echa Creative Mandiri', 'address' => 'Gili Trawangan, Lombok Utara',
            'regency' => 'Kabupaten Lombok Utara', 'village' => null, 'district' => null,
            'latitude' => null, 'longitude' => null,
        ]);
    }

    /** @return array<string, mixed> */
    private function areaResult(): array
    {
        return [
            'lat' => '-8.3503', 'lon' => '116.0362', 'name' => 'Gili Trawangan', 'display_name' => 'Gili Trawangan, Lombok Utara, Nusa Tenggara Barat',
            'category' => 'place', 'type' => 'island', 'addresstype' => 'island',
            'boundingbox' => ['-8.37', '-8.32', '116.01', '116.05'],
            'address' => ['county' => 'Lombok Utara', 'state' => 'Nusa Tenggara Barat', 'country_code' => 'id'],
        ];
    }
}
