<?php

namespace App\Filament\Resources\Kupvas;

use App\Models\Kupva;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;
use Illuminate\Support\Str;

class KupvaGeocoder
{
    /** @return array{latitude: float, longitude: float, address: string, source: string}|null */
    public function resolve(Kupva $kupva): ?array
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
            $results = $this->search($search);

            foreach ($results as $result) {
                if (! $this->isUsableResult($result, $kupva)) {
                    continue;
                }

                return [
                    'latitude' => (float) $result['lat'],
                    'longitude' => (float) $result['lon'],
                    'address' => Str::limit((string) ($result['display_name'] ?? $search), 1000, ''),
                    'source' => 'nominatim',
                ];
            }
        }

        foreach (array_slice($this->areaCandidates($address, $kupva), 0, 5) as $area) {
            $search = "{$area}, {$kupva->regency}, Nusa Tenggara Barat, Indonesia";

            foreach ($this->search($search) as $result) {
                if (! $this->isUsableArea($result, $area, $kupva)) {
                    continue;
                }

                return [
                    'latitude' => (float) $result['lat'],
                    'longitude' => (float) $result['lon'],
                    'address' => Str::limit((string) ($result['display_name'] ?? $search), 1000, ''),
                    'source' => 'nominatim_area',
                ];
            }
        }

        return null;
    }

    /** @return list<array<string, mixed>> */
    private function search(string $search): array
    {
        return Cache::remember('kupva-geocode:'.hash('sha256', config('services.geocoding.search_url').$search), now()->addDays(30), function () use ($search): array {
            return Cache::lock('kupva-geocoding-http', 15)->block(3, function () use ($search): array {
                Sleep::for(1)->seconds();
                $results = Http::acceptJson()->withUserAgent(config('services.geocoding.user_agent'))
                    ->connectTimeout(3)->timeout(5)
                    ->get(config('services.geocoding.search_url'), [
                        'format' => 'jsonv2', 'q' => $search, 'countrycodes' => 'id',
                        'viewbox' => '115,-8,120,-11', 'bounded' => 1,
                        'limit' => 5, 'addressdetails' => 1, 'accept-language' => 'id',
                    ])->throw()->json();

                return is_array($results) ? array_values(array_filter($results, 'is_array')) : [];
            });
        });
    }

    /** @return list<string> */
    private function areaCandidates(string $address, Kupva $kupva): array
    {
        $areas = collect([$kupva->village])->filter()->values()->all();
        preg_match_all('/\b(?:Dusun|Lingkungan|Desa|Kelurahan)\s+(.+?)(?=\b(?:RT|RW|Dusun|Lingkungan|Desa|Kelurahan|Kecamatan|Kabupaten|Kota|Provinsi)\b|,|$)/iu', $address, $matches);
        $areas = array_merge($areas, $matches[1]);

        foreach (explode(',', $address) as $segment) {
            if (! preg_match('/\b(?:Jalan|Kecamatan|Kabupaten|Kota|Provinsi)\b/iu', $segment)) {
                $areas[] = trim($segment);
            } else {
                $areas[] = trim(preg_replace('/\b(?:Kabupaten|Kota|Provinsi)\b.*$/iu', '', $segment));
            }
        }

        if (preg_match('/\b(?:No\.?\s*\d+\p{L}?|Km\.?\s*\d+)\b\s*(.+?)(?:,|$)/iu', $address, $tail)) {
            $areas[] = $tail[1];
        }

        $areas[] = $kupva->district;
        preg_match_all('/\bKecamatan\s+(.+?)(?=\b(?:Kabupaten|Kota|Provinsi)\b|,|$)/iu', $address, $matches);
        $areas = array_merge($areas, $matches[1]);

        if (preg_match('/\bJalan\s+(?:Raya\s+)?(.+?)(?:\s+(?:No\.?\s*\d|Km\.?\s*\d)|,|$)/iu', $address, $street)) {
            $areas[] = $street[1];
        }

        $regency = preg_replace('/^(Kota|Kabupaten)\s+/u', '', $kupva->regency);
        $candidates = [];

        foreach (array_filter($areas) as $area) {
            $area = trim(preg_replace('/\s*-?\s*\b(?:Kabupaten|Kota|Provinsi|Lombok|Nusa Tenggara)\b.*$/iu', '', $area));
            $area = trim(str_ireplace($regency, '', $area));

            if (! preg_match('/\b(?:Jalan|Blok|Pertokoan)\b/iu', $area) && preg_match('/^[\p{L}]+(?:\s+[\p{L}]+){0,2}$/u', $area) && Str::length($area) >= 3) {
                $candidates[] = $area;
            }
        }

        foreach ($candidates as $area) {
            $words = preg_split('/\s+/u', $area);

            if (count($words) > 1) {
                $candidates[] = $words[0];
                $candidates[] = $words[count($words) - 1];
            }
        }

        return array_values(array_unique($candidates));
    }

    /** @param array<string, mixed> $result */
    private function isUsableArea(array $result, string $area, Kupva $kupva): bool
    {
        $address = $result['address'] ?? [];
        $name = preg_replace('/^(Desa|Kelurahan|Kecamatan|Pulau)\s+/iu', '', (string) ($result['name'] ?? ''));
        $normalizedName = preg_replace('/[^\p{L}]/u', '', Str::lower($name));
        $normalizedArea = preg_replace('/[^\p{L}]/u', '', Str::lower($area));
        $regency = Str::lower(preg_replace('/^(Kota|Kabupaten)\s+/u', '', $kupva->regency));
        $areaType = $result['addresstype'] ?? $result['type'] ?? '';
        $bounds = $result['boundingbox'] ?? [];

        return is_numeric($result['lat'] ?? null) && is_numeric($result['lon'] ?? null)
            && (float) $result['lat'] >= -11 && (float) $result['lat'] <= -8
            && (float) $result['lon'] >= 115 && (float) $result['lon'] <= 120
            && $normalizedName === $normalizedArea
            && in_array($areaType, ['village', 'town', 'hamlet', 'suburb', 'neighbourhood', 'island', 'quarter', 'municipality', 'county'], true)
            && ($address['country_code'] ?? '') === 'id'
            && in_array($address['state'] ?? '', ['Nusa Tenggara Barat', 'West Nusa Tenggara'], true)
            && Str::contains(Str::lower(implode(' ', $address)), $regency)
            && count($bounds) === 4
            && abs((float) $bounds[1] - (float) $bounds[0]) <= 0.2
            && abs((float) $bounds[3] - (float) $bounds[2]) <= 0.2;
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
