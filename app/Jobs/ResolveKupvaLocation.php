<?php

namespace App\Jobs;

use App\Filament\Resources\Kupvas\KupvaGeocoder;
use App\Models\Kupva;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ResolveKupvaLocation implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 60;

    public int $uniqueFor = 3600;

    /** @var list<int> */
    public array $backoff = [60, 180];

    public function __construct(public int $kupvaId) {}

    public function uniqueId(): string
    {
        return (string) $this->kupvaId;
    }

    /**
     * Execute the job.
     */
    public function handle(KupvaGeocoder $geocoder): void
    {
        $kupva = Kupva::query()->find($this->kupvaId);

        if ($kupva === null || $kupva->latitude !== null || $kupva->longitude !== null || blank($kupva->address)) {
            return;
        }

        $candidate = $geocoder->resolve($kupva);

        if ($candidate !== null) {
            Kupva::query()->whereKey($kupva->id)->whereNull('latitude')->whereNull('longitude')
                ->where('address', $kupva->address)->where('regency', $kupva->regency)
                ->update([
                    'latitude' => $candidate['latitude'], 'longitude' => $candidate['longitude'],
                    'location_source' => $candidate['source'], 'location_match_address' => $candidate['address'],
                ]);
        }
    }
}
