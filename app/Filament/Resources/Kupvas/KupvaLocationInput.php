<?php

namespace App\Filament\Resources\Kupvas;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class KupvaLocationInput
{
    /** @return array{latitude: float, longitude: float} */
    public function resolve(string $input, string $errorKey = 'location_input'): array
    {
        try {
            return $this->parse($input);
        } catch (ValidationException $exception) {
            throw ValidationException::withMessages([$errorKey => $exception->errors()['location_input']]);
        }
    }

    /** @return array{latitude: float, longitude: float} */
    private function parse(string $input): array
    {
        $input = trim($input);

        if (strlen($input) > 2048) {
            $this->invalid('Koordinat atau tautan terlalu panjang. Gunakan tautan lokasi Google Maps atau koordinat langsung.');
        }

        if (preg_match('/^(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)$/', $input, $matches)) {
            return $this->coordinates($matches[1], $matches[2]);
        }

        for ($redirect = 0; $redirect < 4; $redirect++) {
            $url = $this->allowedUrl($input);
            parse_str($url['query'] ?? '', $query);

            foreach (['query', 'q'] as $key) {
                if (is_string($query[$key] ?? null) && preg_match('/^(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)$/', trim($query[$key]), $matches)) {
                    return $this->coordinates($matches[1], $matches[2]);
                }
            }

            if (preg_match('/!3d(-?\d+(?:\.\d+)?)!4d(-?\d+(?:\.\d+)?)/', rawurldecode($input), $matches)) {
                return $this->coordinates($matches[1], $matches[2]);
            }

            if (! in_array($url['host'], ['maps.app.goo.gl', 'goo.gl'], true)) {
                break;
            }

            try {
                $response = Http::withoutRedirecting()->connectTimeout(3)->timeout(5)->get($input);
            } catch (ConnectionException) {
                $this->invalid('Tautan Google Maps belum dapat dibaca. Coba lagi atau masukkan koordinat langsung.');
            }

            if (! $response->redirect() || blank($response->header('Location'))) {
                break;
            }

            $input = $response->header('Location');
        }

        $this->invalid('Titik usaha tidak ditemukan pada tautan. Di Google Maps, klik kanan pada lokasi usaha dan salin koordinatnya, lalu tempel di sini.');
    }

    /** @return array<string, mixed> */
    private function allowedUrl(string $input): array
    {
        $url = parse_url($input);
        $host = strtolower($url['host'] ?? '');
        $path = $url['path'] ?? '';
        $googleHost = in_array($host, ['google.com', 'www.google.com', 'maps.google.com', 'google.co.id', 'www.google.co.id', 'maps.google.co.id'], true);
        $googlePath = $googleHost && (str_starts_with($path, '/maps/') || $path === '/maps' || ($host === 'maps.google.com' && $path === '/'));
        $shortPath = ($host === 'maps.app.goo.gl' && preg_match('#^/[a-zA-Z0-9]+/?$#', $path))
            || ($host === 'goo.gl' && preg_match('#^/maps/[a-zA-Z0-9]+/?$#', $path));

        if (! filter_var($input, FILTER_VALIDATE_URL) || preg_match('/[\x00-\x20]/', $input)
            || ($url['scheme'] ?? null) !== 'https' || isset($url['user']) || isset($url['pass']) || isset($url['port'])
            || (! $googlePath && ! $shortPath) || str_contains($path, '/dir/')) {
            $this->invalid('Gunakan koordinat latitude, longitude atau tautan HTTPS lokasi Google Maps.');
        }

        $url['host'] = $host;

        return $url;
    }

    /** @return array{latitude: float, longitude: float} */
    private function coordinates(string $latitude, string $longitude): array
    {
        $latitude = (float) $latitude;
        $longitude = (float) $longitude;

        if ($latitude < -11 || $latitude > -8 || $longitude < 115 || $longitude > 120) {
            $this->invalid('Koordinat berada di luar rentang wilayah NTB. Periksa urutan latitude, longitude dan titik yang dipilih.');
        }

        return ['latitude' => $latitude, 'longitude' => $longitude];
    }

    private function invalid(string $message): never
    {
        throw ValidationException::withMessages(['location_input' => $message]);
    }
}
