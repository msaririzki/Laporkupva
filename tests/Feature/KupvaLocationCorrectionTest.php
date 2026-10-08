<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Filament\Resources\Kupvas\KupvaLocationInput;
use App\Filament\Resources\Kupvas\Pages\EditKupva;
use App\Filament\Resources\Kupvas\Pages\ListKupvas;
use App\Filament\Resources\Kupvas\Pages\ViewKupva;
use App\Models\Kupva;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class KupvaLocationCorrectionTest extends TestCase
{
    use LazilyRefreshDatabase;

    #[TestWith(['super_admin'])]
    #[TestWith(['admin'])]
    public function test_staff_can_correct_coordinates_and_the_public_map_uses_the_verified_point(string $role): void
    {
        $kupva = Kupva::factory()->create(['location_source' => 'nominatim_area', 'location_match_address' => 'Alamat hasil perkiraan']);
        $this->actingAs(User::factory()->create(['role' => UserRole::from($role)]));

        Livewire::test(ViewKupva::class, ['record' => $kupva->id])
            ->callAction('correctLocation', ['latitude' => -8.5830695, 'longitude' => 116.11618])
            ->assertHasNoActionErrors()->assertNotified('Titik lokasi berhasil diperbarui');

        $this->assertDatabaseHas('kupvas', ['id' => $kupva->id, 'latitude' => -8.5830695, 'longitude' => 116.11618, 'location_source' => 'manual', 'location_match_address' => null]);
        $this->get(route('kupvas.index'))->assertViewHas('mapKupvas', fn ($offices): bool => $offices->first()['latitude'] === -8.5830695 && $offices->first()['longitude'] === 116.11618 && $offices->first()['approximate'] === false);
    }

    public function test_location_can_be_corrected_from_the_table_using_a_google_maps_link(): void
    {
        $kupva = Kupva::factory()->create();
        $other = Kupva::factory()->create(['latitude' => -8.4, 'longitude' => 116.4]);
        $this->actingAs(User::factory()->create());

        Livewire::test(ListKupvas::class)->callAction(TestAction::make('correctLocation')->table($kupva), [
            'location_input' => 'https://www.google.com/maps/search/?api=1&query=-8.5830695%2C116.11618',
        ])->assertHasNoActionErrors();

        $this->assertDatabaseHas('kupvas', ['id' => $kupva->id, 'latitude' => -8.5830695, 'longitude' => 116.11618, 'location_source' => 'manual']);
        $this->assertDatabaseHas('kupvas', ['id' => $other->id, 'latitude' => -8.4, 'longitude' => 116.4]);
    }

    public function test_reading_a_point_updates_the_modal_without_saving_and_the_edit_form_refreshes_after_save(): void
    {
        $kupva = Kupva::factory()->create(['latitude' => -8.4, 'longitude' => 116.4, 'location_source' => 'nominatim_area']);
        $this->actingAs(User::factory()->superAdmin()->create());

        $page = Livewire::test(EditKupva::class, ['record' => $kupva->id])->mountAction('correctLocation')
            ->fillForm(['location_input' => '-8.5830695, 116.11618'], 'mountedActionSchema0')
            ->callAction(TestAction::make('readLocation')->schemaComponent('location_input', 'mountedActionSchema0'))
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('kupvas', ['id' => $kupva->id, 'latitude' => -8.4, 'longitude' => 116.4]);
        $page->callMountedAction()->assertHasNoActionErrors()
            ->assertSet('data.location_source', 'manual')->assertSet('data.latitude', '-8.5830695');
        $this->assertSame('116.1161800', $kupva->fresh()->longitude);
    }

    public function test_police_cannot_invoke_coordinate_correction(): void
    {
        $kupva = Kupva::factory()->create(['latitude' => -8.4, 'longitude' => 116.4]);
        $this->actingAs(User::factory()->police()->create());

        Livewire::test(ViewKupva::class, ['record' => $kupva->id])->assertActionHidden('correctLocation')
            ->call('mountAction', 'correctLocation')
            ->call('callMountedAction');

        $this->assertDatabaseHas('kupvas', ['id' => $kupva->id, 'latitude' => -8.4, 'longitude' => 116.4]);
    }

    public function test_an_invalid_location_link_shows_an_action_error_and_keeps_the_saved_point(): void
    {
        $kupva = Kupva::factory()->create(['latitude' => -8.4, 'longitude' => 116.4]);
        $this->actingAs(User::factory()->create());
        Http::preventStrayRequests();

        Livewire::test(ViewKupva::class, ['record' => $kupva->id])
            ->callAction('correctLocation', ['location_input' => 'https://evil.example/maps?q=-8.5,116.1'])
            ->assertHasActionErrors(['location_input']);

        $this->assertDatabaseHas('kupvas', ['id' => $kupva->id, 'latitude' => -8.4, 'longitude' => 116.4]);
        Http::assertNothingSent();
    }

    #[TestWith(['-8.5830695, 116.11618'])]
    #[TestWith(['https://www.google.com/maps/search/?api=1&query=-8.5830695%2C116.11618'])]
    #[TestWith(['https://maps.google.com/?q=-8.5830695,116.11618'])]
    #[TestWith(['https://www.google.co.id/maps/place/Usaha/@-8.4,116.4,17z/data=!3d-8.5830695!4d116.11618'])]
    public function test_coordinate_input_and_google_place_links_resolve_the_business_point(string $input): void
    {
        Http::preventStrayRequests();

        $point = app(KupvaLocationInput::class)->resolve($input);

        $this->assertSame(['latitude' => -8.5830695, 'longitude' => 116.11618], $point);
        Http::assertNothingSent();
    }

    #[TestWith(['https://maps.app.goo.gl/Office123'])]
    #[TestWith(['https://goo.gl/maps/Office123'])]
    public function test_short_google_links_are_resolved_before_reading_the_coordinates(string $input): void
    {
        Http::preventStrayRequests();
        Http::fake([$input => Http::response('', 302, ['Location' => 'https://www.google.com/maps/place/Office/data=!3d-8.5830695!4d116.11618'])]);

        $point = app(KupvaLocationInput::class)->resolve($input);

        $this->assertSame(['latitude' => -8.5830695, 'longitude' => 116.11618], $point);
        Http::assertSentCount(1);
    }

    #[TestWith(['116.11618, -8.5830695'])]
    #[TestWith(['-6.2, 106.8'])]
    #[TestWith(['https://google.com.evil.example/maps?q=-8.5,116.1'])]
    #[TestWith(['https://127.0.0.1/maps?q=-8.5,116.1'])]
    #[TestWith(['https://www.google.com:443/maps?q=-8.5,116.1'])]
    #[TestWith(['http://www.google.com/maps?q=-8.5,116.1'])]
    #[TestWith(['https://user@www.google.com/maps?q=-8.5,116.1'])]
    #[TestWith(['https://www.google.com/maps/dir/A/B/data=!3d-8.5!4d116.1'])]
    #[TestWith(['https://www.google.com/maps/place/Usaha/@-8.5830695,116.11618,17z'])]
    public function test_invalid_urls_and_map_camera_positions_are_not_saved_as_business_locations(string $input): void
    {
        Http::preventStrayRequests();

        try {
            app(KupvaLocationInput::class)->resolve($input);
            $this->fail('Input lokasi tidak valid harus ditolak.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('location_input', $exception->errors());
        }

        Http::assertNothingSent();
    }

    public function test_short_link_redirects_cannot_request_an_unapproved_host(): void
    {
        Http::preventStrayRequests();
        Http::fake(['https://maps.app.goo.gl/Office123' => Http::response('', 302, ['Location' => 'http://127.0.0.1/private'])]);

        try {
            app(KupvaLocationInput::class)->resolve('https://maps.app.goo.gl/Office123');
            $this->fail('Redirect ke layanan lain harus ditolak.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('tautan HTTPS lokasi Google Maps', $exception->getMessage());
        }

        Http::assertSentCount(1);
    }

    public function test_google_short_link_connection_failure_is_explained(): void
    {
        Http::preventStrayRequests();
        Http::fake(['https://maps.app.goo.gl/Office123' => Http::failedConnection()]);

        try {
            app(KupvaLocationInput::class)->resolve('https://maps.app.goo.gl/Office123');
            $this->fail('Gangguan koneksi harus dijelaskan.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('Coba lagi atau masukkan koordinat langsung', $exception->getMessage());
        }

        Http::assertSentCount(1);
    }

    public function test_half_a_coordinate_pair_cannot_be_saved_in_the_edit_form(): void
    {
        $kupva = Kupva::factory()->create(['latitude' => null, 'longitude' => null]);
        $this->actingAs(User::factory()->create());

        Livewire::test(EditKupva::class, ['record' => $kupva->id])->fillForm(['latitude' => -8.5, 'longitude' => null])
            ->call('save')->assertHasFormErrors(['longitude' => 'required_with']);

        $this->assertNull($kupva->fresh()->latitude);
    }

    public function test_reading_an_invalid_point_shows_the_error_beside_the_input_without_saving(): void
    {
        $kupva = Kupva::factory()->create(['latitude' => -8.4, 'longitude' => 116.4]);
        $this->actingAs(User::factory()->create());

        Livewire::test(ViewKupva::class, ['record' => $kupva->id])->mountAction('correctLocation')
            ->fillForm(['location_input' => 'https://evil.example/maps'], 'mountedActionSchema0')
            ->callAction(TestAction::make('readLocation')->schemaComponent('location_input', 'mountedActionSchema0'))
            ->assertHasErrors(['mountedActions.0.data.location_input']);

        $this->assertDatabaseHas('kupvas', ['id' => $kupva->id, 'latitude' => -8.4, 'longitude' => 116.4]);
    }

    public function test_empty_coordinates_and_out_of_bounds_coordinates_cannot_be_saved(): void
    {
        $kupva = Kupva::factory()->create(['latitude' => null, 'longitude' => null]);
        $this->actingAs(User::factory()->create());

        Livewire::test(ViewKupva::class, ['record' => $kupva->id])->callAction('correctLocation', [])
            ->assertHasActionErrors(['latitude' => 'required_without', 'longitude' => 'required_without']);
        Livewire::test(ViewKupva::class, ['record' => $kupva->id])->callAction('correctLocation', ['latitude' => -6.2, 'longitude' => 106.8])
            ->assertHasActionErrors(['latitude' => 'max', 'longitude' => 'min']);

        $this->assertNull($kupva->fresh()->latitude);
    }

    #[TestWith(['nominatim'])]
    #[TestWith(['nominatim_area'])]
    public function test_editing_coordinates_directly_marks_the_point_verified(string $source): void
    {
        $kupva = Kupva::factory()->create(['latitude' => -8.4, 'longitude' => 116.4, 'location_source' => $source]);
        $this->actingAs(User::factory()->create());

        Livewire::test(EditKupva::class, ['record' => $kupva->id])->fillForm(['latitude' => -8.5830695, 'longitude' => 116.11618])
            ->call('save')->assertHasNoFormErrors();

        $this->assertDatabaseHas('kupvas', ['id' => $kupva->id, 'latitude' => -8.5830695, 'longitude' => 116.11618, 'location_source' => 'manual']);
    }

    public function test_clearing_both_coordinates_clears_the_old_verification(): void
    {
        $kupva = Kupva::factory()->create(['latitude' => -8.4, 'longitude' => 116.4, 'location_source' => 'manual']);
        $this->actingAs(User::factory()->create());

        Livewire::test(EditKupva::class, ['record' => $kupva->id])->fillForm(['latitude' => null, 'longitude' => null])
            ->call('save')->assertHasNoFormErrors();

        $this->assertDatabaseHas('kupvas', ['id' => $kupva->id, 'latitude' => null, 'longitude' => null, 'location_source' => null]);
    }

    public function test_changing_other_office_data_does_not_mark_an_approximate_point_verified(): void
    {
        $kupva = Kupva::factory()->create(['latitude' => -8.4, 'longitude' => 116.4, 'location_source' => 'nominatim_area']);
        $this->actingAs(User::factory()->create());

        Livewire::test(EditKupva::class, ['record' => $kupva->id])->fillForm(['name' => 'Nama usaha diperbarui'])
            ->call('save')->assertHasNoFormErrors();

        $this->assertSame('nominatim_area', $kupva->fresh()->location_source);
    }

    #[TestWith([302, true, 4])]
    #[TestWith([404, false, 1])]
    #[TestWith([200, false, 1])]
    public function test_short_link_loops_and_unresolved_responses_are_bounded(int $status, bool $loop, int $requests): void
    {
        Http::preventStrayRequests();
        Http::fake(['https://maps.app.goo.gl/Office123' => Http::response('', $status, $loop ? ['Location' => 'https://maps.app.goo.gl/Office123'] : [])]);

        try {
            app(KupvaLocationInput::class)->resolve('https://maps.app.goo.gl/Office123');
            $this->fail('Tautan tanpa titik usaha harus ditolak.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('salin koordinatnya', $exception->getMessage());
        }

        Http::assertSentCount($requests);
    }
}
