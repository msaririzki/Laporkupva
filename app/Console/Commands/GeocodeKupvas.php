<?php

namespace App\Console\Commands;

use App\Models\Kupva;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;
use Illuminate\Support\Str;

#[Signature('kupvas:geocode {--id=* : ID KUPVA yang dicari} {--limit=25 : Maksimal data dalam satu proses} {--save : Simpan titik perkiraan yang ditemukan}')]
#[Description('Cari koordinat dari alamat KUPVA yang belum memiliki titik, dengan pratinjau sebagai bawaan')]
class GeocodeKupvas extends Command
{
    public function handle(): int
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
                $candidate = $this->findLocation($kupva);

                if ($candidate === null) {
                    $this->warn("#{$kupva->id} {$kupva->name}: belum ditemukan titik alamat yang cukup spesifik.");

                    continue;
                }

                $found++;
                $this->line("#{$kupva->id} {$kupva->name}: {$candidate['latitude']}, {$candidate['longitude']} — {$candidate['address']}");

                if ($this->option('save')) {
                    Kupva::query()->whereKey($kupva->id)->whereNull('latitude')->whereNull('longitude')->update([
                        'latitude' => $candidate['latitude'],
                        'longitude' => $candidate['longitude'],
                        'location_source' => 'nominatim',
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

    /** @return array{latitude: float, longitude: float, address: string}|null */
    private function findLocation(Kupva $kupva): ?array
    {
        $address = preg_replace(
            ['/\bJl\b\.?\s*/iu', '/\bKab\b\.?\s*/iu', '/\bKec\b\.?\s*/iu', '/\bKel\b\.?\s*/iu', '/Loinbok/iu', '/Nusa Tenggara Baret/iu'],
            ['Jalan ', 'Kabupaten ', 'Kecamatan ', 'Kelurahan ', 'Lombok', 'Nusa Tenggara Barat'],
            trim($kupva->address),
        );
        $queries = ["{$kupva->name}, {$address}, {$kupva->regency}, Nusa Tenggara Barat, Indonesia", "{$address}, {$kupva->regency}, Nusa Tenggara Barat, Indonesia"];

        if (preg_match('/^Jalan\s+(.+?)(?:\s+(?:No\.?\s*\d|Km\.?\s*\d)|,|$)/iu', $address, $matches)) {
            $queries[] = "Jalan {$matches[1]}, {$kupva->regency}, Nusa Tenggara Barat, Indonesia";
        }

        foreach (array_unique($queries) as $search) {
            $results = Cache::remember('kupva-geocode:'.hash('sha256', config('services.geocoding.search_url').$search), now()->addDays(30), function () use ($search): array {
                Sleep::for(1)->seconds();

                return Http::acceptJson()
                    ->withUserAgent(config('services.geocoding.user_agent'))
                    ->connectTimeout(5)->timeout(15)
                    ->get(config('services.geocoding.search_url'), [
                        'format' => 'jsonv2', 'q' => $search, 'countrycodes' => 'id',
                        'viewbox' => '115,-8,120,-11', 'bounded' => 1,
                        'limit' => 5, 'addressdetails' => 1, 'accept-language' => 'id',
                    ])->throw()->json() ?? [];
            });

            foreach ($results as $result) {
                if (! $this->isUsableResult($result, $kupva)) {
                    continue;
                }

                return [
                    'latitude' => (float) $result['lat'],
                    'longitude' => (float) $result['lon'],
                    'address' => Str::limit((string) ($result['display_name'] ?? $search), 1000, ''),
                ];
            }
        }

        return null;
    }

    /** @param array<string, mixed> $result */
    private function isUsableResult(array $result, Kupva $kupva): bool
    {
        $latitude = $result['lat'] ?? null;
        $longitude = $result['lon'] ?? null;
        $address = $result['address'] ?? [];
        $category = $result['category'] ?? $result['class'] ?? '';
        $type = $result['type'] ?? '';
        $regency = Str::lower(preg_replace('/^(Kota|Kabupaten)\s+/u', '', $kupva->regency));

        $localities = collect([$kupva->district, $kupva->village])->filter()->values()->all();
        preg_match_all('/\b(?:Kec(?:amatan)?\.?|Desa|Kel(?:urahan)?\.?)\s+([\p{L}-]+(?:\s+(?:Barat|Timur|Utara|Selatan|Tengah|Agung|Indah))?)/iu', $kupva->address, $matches);
        $localities = array_merge($localities, $matches[1]);
        if (preg_match('/\b(?:No\.?\s*\d+\p{L}?|Km\.?\s*\d+)\b\s*(.+?)(?:,|$)/iu', $kupva->address, $tail)) {
            $locality = preg_replace('/\b(?:Kota|Kab(?:upaten)?\.?|Provinsi)\b.*$/iu', '', $tail[1]);
            $locality = Str::replace($kupva->regency, '', $locality);
            $locality = str_ireplace(preg_replace('/^(Kota|Kabupaten)\s+/u', '', $kupva->regency), '', $locality);
            $locality = trim(preg_replace('/^(?:Dusun|Lingkungan|Desa|Kelurahan)\s+/iu', '', trim($locality)));

            if ($locality !== '') {
                $localities[] = $locality;
            }
        }

        foreach (array_slice(explode(',', $kupva->address), 1) as $segment) {
            $segment = trim(preg_replace('/\s*-?\s*\b(?:Kab(?:upaten)?\.?|Kota|Lombok|Provinsi|Nusa Tenggara)\b.*$/iu', '', $segment));
            $segment = trim(preg_replace('/^(?:Dusun|Lingkungan|Kec(?:amatan)?\.?|Kel(?:urahan)?\.?|Desa)\s+/iu', '', $segment));

            if ($segment !== '' && preg_match('/^[\p{L}]+(?:\s+[\p{L}]+){0,2}$/u', $segment)) {
                $localities[] = $segment;
            }
        }

        $matchAddress = Str::lower(implode(' ', $address));

        foreach ($localities as $locality) {
            if (! Str::contains(preg_replace('/\s+/u', '', $matchAddress), preg_replace('/\s+/u', '', Str::lower($locality)))) {
                return false;
            }
        }

        if ($category === 'highway') {
            $bounds = $result['boundingbox'] ?? [];

            if (count($bounds) !== 4 || abs((float) $bounds[1] - (float) $bounds[0]) > 0.03 || abs((float) $bounds[3] - (float) $bounds[2]) > 0.03) {
                return false;
            }
        }

        return is_numeric($latitude) && is_numeric($longitude)
            && (float) $latitude >= -11 && (float) $latitude <= -8
            && (float) $longitude >= 115 && (float) $longitude <= 120
            && ($address['country_code'] ?? '') === 'id'
            && in_array($address['state'] ?? '', ['Nusa Tenggara Barat', 'West Nusa Tenggara'], true)
            && Str::contains($matchAddress, $regency)
            && (in_array($category, ['highway', 'shop', 'office', 'building', 'amenity'], true) || in_array($type, ['house', 'commercial', 'retail'], true));
    }
}
