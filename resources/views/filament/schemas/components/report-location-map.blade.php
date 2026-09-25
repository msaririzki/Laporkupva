<div wire:ignore>
    <div
        id="report-location-map-{{ $record->getKey() }}"
        class="w-full overflow-hidden rounded-xl bg-gray-100"
        style="height: 320px; width: 100%;"
    ></div>
</div>

@assets
    @vite(['resources/css/leaflet.css', 'resources/js/map-layers.js'])
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endassets

@script
    <script>
        (() => {
            const initializeLocationMap = () => {
                const element = document.getElementById(@js('report-location-map-'.$record->getKey()));

                if (!element || element.dataset.initialized || typeof L === 'undefined' || !window.TamboraMap) return;
                element.dataset.initialized = 'true';

                const latitude = @js((float) $record->latitude);
                const longitude = @js((float) $record->longitude);
                const point = [latitude, longitude];
                const map = L.map(element, {
                    minZoom: 8,
                    maxZoom: 18,
                    scrollWheelZoom: false,
                    wheelDebounceTime: 40,
                    wheelPxPerZoomLevel: 100,
                }).setView(point, 15);

                const layers = window.TamboraMap.createBaseLayers(L, @js(config('services.mapbox.public_token')));
                window.TamboraMap.addStyleControl(L, map, layers);
                window.TamboraMap.enableSafeScrollZoom(map);

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
            };

            if (window.TamboraMap) {
                initializeLocationMap();
            } else {
                window.addEventListener('tambora-map:ready', initializeLocationMap, { once: true });
            }
        })();
    </script>
@endscript
