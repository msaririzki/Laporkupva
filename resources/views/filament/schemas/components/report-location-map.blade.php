<div wire:ignore>
    <div
        id="report-location-map-{{ $record->getKey() }}"
        class="w-full overflow-hidden rounded-xl bg-gray-100"
        style="height: 320px; width: 100%;"
    ></div>
</div>

@assets
    @vite('resources/css/leaflet.css')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endassets

@script
    <script>
        (() => {
            const element = document.getElementById(@js('report-location-map-'.$record->getKey()));

            if (!element || element.dataset.initialized || typeof L === 'undefined') return;
            element.dataset.initialized = 'true';

            const latitude = @js((float) $record->latitude);
            const longitude = @js((float) $record->longitude);
            const point = [latitude, longitude];
            const map = L.map(element, { minZoom: 8, maxZoom: 18, scrollWheelZoom: false }).setView(point, 15);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            const markerIcon = L.divIcon({
                className: '',
                html: '<span style="display:block;width:18px;height:18px;border-radius:999px;background:#2563eb;border:4px solid white;box-shadow:0 2px 10px rgb(15 23 42 / .4)"></span>',
                iconSize: [18, 18],
                iconAnchor: [9, 9],
            });

            L.marker(point, { icon: markerIcon }).addTo(map).bindTooltip(@js($record->business_name ?: $record->public_code), {
                permanent: false,
                direction: 'top',
            });

            requestAnimationFrame(() => requestAnimationFrame(() => map.invalidateSize()));
        })();
    </script>
@endscript
