@props(['name', 'address', 'latitude' => null, 'longitude' => null, 'approximate' => false])

@php
    $hasCoordinates = ! $approximate && is_numeric($latitude) && is_numeric($longitude)
        && (float) $latitude >= -11 && (float) $latitude <= -8
        && (float) $longitude >= 115 && (float) $longitude <= 120;
    $query = $hasCoordinates
        ? (float) $latitude.','.(float) $longitude
        : collect([$name, $address])->filter()->join(', ');
    $mapsUrl = 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($query);
@endphp

<a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" data-kupva-google-maps
    aria-label="Buka lokasi {{ $name }} di Google Maps (tab baru)"
    {{ $attributes->class(['inline-flex min-h-9 items-center justify-center gap-1.5 rounded-lg border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 transition hover:bg-blue-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600']) }}>
    <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M15 3h6v6m0-6L10 14"/><path d="M10 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5"/></svg>
    Google Maps
</a>
