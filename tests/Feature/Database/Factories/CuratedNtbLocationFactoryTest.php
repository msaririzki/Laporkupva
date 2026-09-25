<?php

namespace Tests\Feature\Database\Factories;

use App\Enums\NtbDemoLocation;
use App\Enums\NtbRegency;
use App\Models\Kupva;
use App\Models\Report;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CuratedNtbLocationFactoryTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_report_and_kupva_factories_use_consistent_curated_ntb_locations(): void
    {
        $report = Report::factory()->create();
        $kupva = Kupva::factory()->create();
        $knownLocations = array_map(
            fn (NtbDemoLocation $location): string => $this->locationKey($location->attributes()),
            NtbDemoLocation::cases(),
        );

        $this->assertContains($this->locationKey($report->only([
            'regency',
            'district',
            'village',
            'address',
            'latitude',
            'longitude',
        ])), $knownLocations);
        $this->assertContains($this->locationKey($kupva->only([
            'regency',
            'district',
            'village',
            'address',
            'latitude',
            'longitude',
        ])), $knownLocations);
    }

    public function test_curated_locations_cover_every_supported_ntb_regency(): void
    {
        $expectedRegencies = array_map(
            fn (NtbRegency $regency): string => $regency->value,
            NtbRegency::cases(),
        );
        $coveredRegencies = array_values(array_unique(array_map(
            fn (NtbDemoLocation $location): string => $location->attributes()['regency'],
            NtbDemoLocation::cases(),
        )));

        sort($expectedRegencies);
        sort($coveredRegencies);

        $this->assertSame($expectedRegencies, $coveredRegencies);
    }

    /**
     * @param  array{
     *     regency: string,
     *     district: string,
     *     village: string,
     *     address: string,
     *     latitude: float|string,
     *     longitude: float|string
     * }  $location
     */
    private function locationKey(array $location): string
    {
        return implode('|', [
            $location['regency'],
            $location['district'],
            $location['village'],
            $location['address'],
            number_format((float) $location['latitude'], 4, '.', ''),
            number_format((float) $location['longitude'], 4, '.', ''),
        ]);
    }
}
