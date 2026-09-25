<x-filament-widgets::widget class="h-full">
    <x-filament::section class="h-full">
        <x-slot name="heading">Peta laporan</x-slot>
        <x-slot name="description">Lokasi yang dilaporkan, bukan lokasi pelapor.</x-slot>

        <x-slot name="afterHeader">
            <div class="flex items-center gap-2 rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700 dark:bg-primary-950 dark:text-primary-300">
                <span class="size-2 rounded-full bg-primary-500"></span>
                {{ number_format($reportCount, 0, ',', '.') }} titik
            </div>
        </x-slot>

        <div wire:ignore>
            <div id="admin-report-map-{{ $this->getId() }}" class="h-[22rem] w-full overflow-hidden rounded-xl bg-gray-100 sm:h-[24rem] xl:h-[26rem]"></div>
            <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-gray-500 dark:text-gray-400">
                <span
                    id="admin-report-map-{{ $this->getId() }}-zoom-hint"
                    class="tambora-map-zoom-hint tambora-map-zoom-hint--inline"
                ></span>
                <span class="inline-flex items-center gap-2"><span class="size-2.5 rounded-full bg-blue-500"></span>Baru / diterima</span>
                <span class="inline-flex items-center gap-2"><span class="size-2.5 rounded-full bg-amber-500"></span>Sedang ditangani</span>
                <span class="inline-flex items-center gap-2"><span class="size-2.5 rounded-full bg-emerald-500"></span>Selesai</span>
            </div>
        </div>
    </x-filament::section>

    @assets
        @vite(['resources/css/leaflet.css', 'resources/js/map-layers.js'])
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @endassets

    @script
        <script>
            (() => {
                const initializeReportMap = () => {
                    const element = document.getElementById(@js('admin-report-map-'.$this->getId()));
                    const zoomHint = document.getElementById(@js('admin-report-map-'.$this->getId().'-zoom-hint'));
                    const markers = @js($markers);

                    if (!element || element.dataset.initialized || typeof L === 'undefined' || !window.TamboraMap) return;
                    element.dataset.initialized = 'true';

                    const map = L.map(element, {
                        minZoom: 7,
                        maxZoom: 18,
                        scrollWheelZoom: false,
                        wheelDebounceTime: 40,
                        wheelPxPerZoomLevel: 100,
                    }).setView([-8.72, 117.35], 8);
                    const layers = window.TamboraMap.createBaseLayers(L, @js(config('services.mapbox.public_token')));
                    window.TamboraMap.addStyleControl(L, map, layers);
                    window.TamboraMap.enableSafeScrollZoom(map, zoomHint);

                    requestAnimationFrame(() => requestAnimationFrame(() => map.invalidateSize()));

                    const colors = {
                        submitted: '#3b82f6',
                        received: '#2563eb',
                        coordination: '#f59e0b',
                        field_action: '#f97316',
                        result_report: '#14b8a6',
                        completed: '#10b981',
                    };
                    const bounds = [];

                    markers.forEach((item) => {
                        const point = [item.latitude, item.longitude];
                        bounds.push(point);
                        const icon = L.divIcon({
                            className: '',
                            html: `<span style="display:block;width:16px;height:16px;border-radius:999px;background:${colors[item.statusKey] || '#64748b'};border:3px solid white;box-shadow:0 2px 9px rgb(15 23 42 / .35)"></span>`,
                            iconSize: [16, 16],
                            iconAnchor: [8, 8],
                        });
                        const popup = document.createElement('div');
                        popup.style.minWidth = '190px';
                        const title = document.createElement('strong');
                        title.textContent = item.business;
                        const details = document.createElement('p');
                        details.style.margin = '5px 0 9px';
                        details.textContent = `${item.code} · ${item.regency} · ${item.date}`;
                        const status = document.createElement('p');
                        status.style.margin = '0 0 9px';
                        status.textContent = `Status: ${item.status}`;
                        const link = document.createElement('a');
                        link.href = item.url;
                        link.textContent = 'Buka detail laporan →';
                        link.style.fontWeight = '700';
                        popup.append(title, details, status, link);
                        L.marker(point, { icon }).addTo(map).bindPopup(popup);
                    });

                    if (bounds.length === 1) map.setView(bounds[0], 14);
                    if (bounds.length > 1) map.fitBounds(bounds, { padding: [35, 35], maxZoom: 14 });
                };

                if (window.TamboraMap) {
                    initializeReportMap();
                } else {
                    window.addEventListener('tambora-map:ready', initializeReportMap, { once: true });
                }
            })();
        </script>
    @endscript
</x-filament-widgets::widget>
