@php
    $statePrefix = substr($getStatePath(), 0, strrpos($getStatePath(), '.') + 1);
@endphp
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            latitude: $wire.$entangle(@js($statePrefix.'latitude')),
            longitude: $wire.$entangle(@js($statePrefix.'longitude')),
            locationInput: $wire.$entangle(@js($statePrefix.'location_input')),
            ready: false,
            error: '',
            cleanup: null,
            destroy() { this.cleanup?.(); },
        }"
        x-load-css="[@js(\Illuminate\Support\Facades\Vite::asset('resources/css/leaflet.css'))]"
        x-init="import(@js(\Illuminate\Support\Facades\Vite::asset('resources/js/kupva-location-editor.js'))).then(() => window.TamboraKupvaLocationEditor.init($el, $data, $watch)).catch(() => error = 'Peta belum dapat dimuat. Koordinat tetap dapat diisi pada kolom di atas.')"
        class="space-y-3"
    >
        <p x-show="!ready && !error" class="text-xs text-slate-500" role="status">Menyiapkan peta koreksi…</p>
        <p x-show="error" x-text="error" class="text-xs text-red-600" role="alert"></p>
        <div wire:ignore>
            <div data-kupva-location-editor class="h-80 w-full overflow-hidden rounded-xl bg-slate-100" role="region" aria-label="Peta koreksi lokasi KUPVA"></div>
        </div>
        <p class="text-xs leading-relaxed text-slate-500">Klik lokasi usaha atau geser penanda. Koordinat akan terisi otomatis. Anda juga dapat mengisi Latitude dan Longitude langsung.</p>
        <a x-show="latitude && longitude" :href="'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(latitude + ',' + longitude)" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-blue-600 hover:underline">Periksa titik ini di Google Maps</a>
    </div>
</x-dynamic-component>
