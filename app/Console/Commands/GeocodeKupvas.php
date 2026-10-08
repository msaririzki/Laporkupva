<?php

namespace App\Console\Commands;

use App\Filament\Resources\Kupvas\KupvaGeocoder;
use App\Models\Kupva;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;

#[Signature('kupvas:geocode {--id=* : ID KUPVA yang dicari} {--limit=25 : Maksimal data dalam satu proses} {--save : Simpan titik perkiraan yang ditemukan}')]
#[Description('Cari koordinat dari alamat KUPVA yang belum memiliki titik, dengan pratinjau sebagai bawaan')]
class GeocodeKupvas extends Command
{
    public function handle(KupvaGeocoder $geocoder): int
    {
        $lock = Cache::lock('kupva-geocoding', 3600);

        if (! $lock->get()) {
            $this->error('Pencarian koordinat lain masih berjalan.');

            return self::FAILURE;
        }

        try {
            $query = Kupva::query()->whereNull('latitude')->whereNull('longitude')->whereNotNull('address')->orderBy('id');

            if ($this->option('id')) {
                $query->whereIn('id', $this->option('id'));
            }

            $kupvas = $query->limit(max(1, min(100, (int) $this->option('limit'))))->get();
            $this->info('Hasil otomatis adalah perkiraan dan perlu diperiksa petugas. Titik yang sudah terisi tidak diubah.');
            $this->line('Nominatim / OpenStreetMap: satu permintaan per detik, hasil disimpan dalam cache.');
            $this->line('https://operations.osmfoundation.org/policies/nominatim/');
            $found = 0;

            foreach ($kupvas as $kupva) {
                $candidate = $geocoder->resolve($kupva);

                if ($candidate === null) {
                    $this->warn("#{$kupva->id} {$kupva->name}: belum ditemukan titik alamat yang cukup spesifik.");

                    continue;
                }

                $found++;
                $this->line("#{$kupva->id} {$kupva->name}: {$candidate['latitude']}, {$candidate['longitude']} — {$candidate['address']}");

                if ($this->option('save')) {
                    Kupva::query()->whereKey($kupva->id)->whereNull('latitude')->whereNull('longitude')
                        ->where('address', $kupva->address)->where('regency', $kupva->regency)->update([
                            'latitude' => $candidate['latitude'],
                            'longitude' => $candidate['longitude'],
                            'location_source' => $candidate['source'],
                            'location_match_address' => $candidate['address'],
                        ]);
                }
            }

            $this->info("{$found} dari {$kupvas->count()} alamat ditemukan".($this->option('save') ? ' dan disimpan sebagai perkiraan.' : '. Pratinjau saja; gunakan --save untuk menyimpan.'));

            return self::SUCCESS;
        } catch (ConnectionException|RequestException $exception) {
            $this->error('Layanan pencarian alamat belum tersedia. Data yang belum ditemukan tetap tanpa koordinat.');

            return self::FAILURE;
        } finally {
            $lock->release();
        }
    }
}
