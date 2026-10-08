import { formatDistance, hasMapCoordinates, rankNearestKupvas } from './kupva-map-distance';

export const initPublicKupvaMap = async () => {
    const root = document.querySelector('[data-public-kupva-map]');

    if (!root || root.dataset.initialized) {
        return;
    }

    root.dataset.initialized = 'true';
    const canvas = root.querySelector('[data-kupva-map-canvas]');
    const status = root.querySelector('[data-kupva-map-status]');
    const locateButton = root.querySelector('[data-kupva-locate]');
    const resetButton = root.querySelector('[data-kupva-reset-map]');
    const resultList = root.querySelector('[data-kupva-results]');
    const configuration = JSON.parse(root.querySelector('[data-kupva-map-data]').textContent);
    const mappedKupvas = configuration.kupvas.filter(hasMapCoordinates);
    const missingCount = configuration.kupvas.length - mappedKupvas.length;
    const markers = new Map();
    const resultElements = new Map(Array.from(resultList.querySelectorAll('[data-kupva-result]'))
        .map((element) => [Number(element.dataset.kupvaResult), element]));

    const availabilityMessage = () => {
        if (!configuration.kupvas.length) {
            return 'Tidak ada hasil pencarian. Coba nama atau wilayah lain.';
        }

        const shown = `${mappedKupvas.length} Money Changer tampil pada peta.`;

        return missingCount ? `${shown} ${missingCount} lainnya dapat dilihat di daftar; titik lokasinya belum tersedia.` : shown;
    };

    try {
        const L = await import('leaflet');
        const map = L.map(canvas, { minZoom: 6, maxZoom: 18, scrollWheelZoom: false })
            .setView([-8.65, 117.6], 8);
        const layers = window.TamboraMap.createBaseLayers(L, configuration.mapboxPublicToken);
        window.TamboraMap.addStyleControl(L, map, layers);
        window.TamboraMap.enableSafeScrollZoom(map);

        const markerIcon = L.divIcon({
            className: 'kupva-map-marker',
            html: '<span class="kupva-map-marker__pin"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 21V4h14v17M3 21h18M9 8h1m4 0h1m-6 4h1m4 0h1m-6 9v-5h6v5"/></svg></span>',
            iconSize: [38, 44],
            iconAnchor: [19, 44],
            popupAnchor: [0, -42],
        });
        const groups = new Map();

        mappedKupvas.forEach((kupva) => {
            const key = `${kupva.latitude},${kupva.longitude}`;
            const group = groups.get(key) ?? [];
            group.push(kupva);
            groups.set(key, group);
        });

        const highlightResult = (id) => {
            resultElements.forEach((element, resultId) => {
                element.classList.toggle('bg-blue-50', resultId === id);
            });
            const result = resultElements.get(id);

            if (result) {
                resultList.scrollTo({ top: Math.max(0, result.offsetTop - resultList.offsetTop - 12), behavior: 'smooth' });
            }
        };

        groups.forEach((kupvas) => {
            const first = kupvas[0];
            const popup = document.createElement('div');
            popup.className = 'kupva-map-popup';
            kupvas.forEach((kupva) => {
                const office = document.createElement('section');
                const name = document.createElement('h3');
                name.textContent = kupva.name;
                const address = document.createElement('p');
                address.textContent = kupva.address;
                office.append(name, address);
                popup.append(office);
            });
            const marker = L.marker([first.latitude, first.longitude], {
                icon: markerIcon,
                title: kupvas.map((kupva) => kupva.name).join(' / '),
                alt: `Lokasi ${first.name}`,
                riseOnHover: true,
            }).addTo(map).bindPopup(popup, { maxWidth: 300 });
            marker.on('click', () => highlightResult(first.id));
            kupvas.forEach((kupva) => markers.set(kupva.id, marker));
        });

        const fitResults = () => {
            if (groups.size === 1) {
                const kupva = mappedKupvas[0];
                map.setView([kupva.latitude, kupva.longitude], kupva.area ? 13 : 15);
            } else if (groups.size > 1) {
                map.fitBounds(mappedKupvas.map((kupva) => [kupva.latitude, kupva.longitude]), { padding: [40, 40], maxZoom: 15 });
            } else {
                map.fitBounds([[-9.1, 115.8], [-8.05, 119.4]], { padding: [20, 20] });
            }
        };

        const focusOffice = (id) => {
            const marker = markers.get(id);

            if (!marker) {
                return;
            }

            const kupva = mappedKupvas.find((office) => office.id === id);
            map.setView(marker.getLatLng(), kupva.area ? 13 : 16);
            marker.openPopup();
            highlightResult(id);
            canvas.scrollIntoView({ behavior: 'smooth', block: 'start' });
        };

        root.querySelectorAll('[data-kupva-focus]').forEach((button) => {
            const id = Number(button.dataset.kupvaFocus);

            if (markers.has(id)) {
                button.disabled = false;
                button.addEventListener('click', () => focusOffice(id));
            } else {
                button.textContent = 'Titik lokasi belum tersedia';
            }
        });

        let userMarker = null;
        let userAccuracy = null;

        locateButton.disabled = !mappedKupvas.length;
        locateButton.addEventListener('click', () => {
            if (!navigator.geolocation) {
                status.textContent = 'Perangkat ini belum mendukung lokasi. Gunakan pencarian wilayah.';
                return;
            }

            locateButton.disabled = true;
            status.textContent = 'Mencari lokasi Anda…';
            navigator.geolocation.getCurrentPosition((position) => {
                locateButton.disabled = false;
                const origin = { latitude: position.coords.latitude, longitude: position.coords.longitude };
                const rankedKupvas = rankNearestKupvas(origin, mappedKupvas);
                const nearest = rankedKupvas[0];

                if (!nearest) {
                    status.textContent = 'Belum ada titik lokasi yang bisa dihitung. Gunakan pencarian wilayah atau tampilan Kartu.';
                    return;
                }

                userMarker?.remove();
                userAccuracy?.remove();
                userMarker = L.circleMarker([origin.latitude, origin.longitude], {
                    radius: 8, color: '#fff', weight: 3, fillColor: '#0ea5e9', fillOpacity: 1,
                }).addTo(map).bindTooltip('Lokasi Anda');
                userAccuracy = L.circle([origin.latitude, origin.longitude], {
                    radius: position.coords.accuracy, color: '#0ea5e9', weight: 1, fillOpacity: 0.08,
                }).addTo(map);
                rankedKupvas.forEach((kupva) => {
                    const element = resultElements.get(kupva.id);
                    element.querySelector('[data-kupva-distance]').textContent = `± ${formatDistance(kupva.distance)}`;
                    resultList.append(element);
                });
                configuration.kupvas.filter((kupva) => !hasMapCoordinates(kupva)).forEach((kupva) => resultList.append(resultElements.get(kupva.id)));
                focusOffice(nearest.id);
                const accuracyMessage = position.coords.accuracy > 1000 ? ' Lokasi perangkat masih kurang akurat; Anda dapat mencari wilayah secara langsung.' : '';
                status.textContent = `Perkiraan terdekat${configuration.focusResults ? ' dari hasil pencarian' : ''}: ${nearest.name}, sekitar ${formatDistance(nearest.distance)} dalam garis lurus.${accuracyMessage}`;
            }, (error) => {
                locateButton.disabled = false;
                status.textContent = error.code === 1
                    ? 'Akses lokasi belum diizinkan. Izinkan lokasi di browser atau gunakan pencarian wilayah.'
                    : 'Lokasi Anda belum dapat ditemukan. Coba kembali atau gunakan pencarian wilayah.';
            }, { enableHighAccuracy: true, timeout: 15000, maximumAge: 60000 });
        });

        resetButton.disabled = false;
        resetButton.addEventListener('click', () => {
            map.closePopup();
            userMarker?.remove();
            userAccuracy?.remove();
            configuration.kupvas.forEach((kupva) => {
                const element = resultElements.get(kupva.id);
                element.classList.remove('bg-blue-50');
                element.querySelector('[data-kupva-distance]').textContent = '';
                resultList.append(element);
            });
            fitResults();
            status.textContent = availabilityMessage();
        });

        requestAnimationFrame(() => {
            map.invalidateSize();

            if (configuration.focusResults) {
                fitResults();
            } else {
                map.fitBounds([[-9.1, 115.8], [-8.05, 119.4]], { padding: [20, 20] });
            }
        });
        status.textContent = availabilityMessage();
    } catch {
        status.textContent = 'Peta belum dapat dimuat. Anda tetap dapat melihat nama dan alamat pada daftar ini atau tampilan Kartu.';
        canvas.classList.add('hidden');
    }
};
