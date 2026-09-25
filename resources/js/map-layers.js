const streetAttribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>';
const satelliteAttribution = '&copy; <a href="https://www.mapbox.com/about/maps/">Mapbox</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>';

const createBaseLayers = (Leaflet, mapboxPublicToken = '') => {
    const street = Leaflet.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: streetAttribution,
    });

    const satellite = mapboxPublicToken
        ? Leaflet.tileLayer(
            `https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v12/tiles/512/{z}/{x}/{y}@2x?access_token=${encodeURIComponent(mapboxPublicToken)}`,
            {
                tileSize: 512,
                zoomOffset: -1,
                maxZoom: 19,
                attribution: satelliteAttribution,
            },
        )
        : null;

    return { street, satellite };
};

const addStyleControl = (Leaflet, map, layers) => {
    layers.street.addTo(map);

    if (!layers.satellite) {
        return null;
    }

    const control = Leaflet.control({ position: 'topright' });

    control.onAdd = () => {
        const container = Leaflet.DomUtil.create('div', 'tambora-map-switcher');
        container.setAttribute('role', 'group');
        container.setAttribute('aria-label', 'Tampilan peta');

        const options = [
            { key: 'street', label: 'Peta' },
            { key: 'satellite', label: 'Satelit' },
        ];

        const activate = (activeKey) => {
            options.forEach(({ key }) => {
                const layer = layers[key];
                const shouldBeVisible = key === activeKey;

                if (shouldBeVisible && !map.hasLayer(layer)) {
                    layer.addTo(map);
                }

                if (!shouldBeVisible && map.hasLayer(layer)) {
                    map.removeLayer(layer);
                }
            });

            container.querySelectorAll('button').forEach((button) => {
                button.setAttribute('aria-pressed', String(button.dataset.mapStyle === activeKey));
            });
        };

        options.forEach(({ key, label }) => {
            const button = Leaflet.DomUtil.create('button', 'tambora-map-switcher__button', container);
            button.type = 'button';
            button.dataset.mapStyle = key;
            button.textContent = label;
            button.setAttribute('aria-pressed', String(key === 'street'));
            button.addEventListener('click', () => activate(key));
        });

        Leaflet.DomEvent.disableClickPropagation(container);
        Leaflet.DomEvent.disableScrollPropagation(container);

        return container;
    };

    control.addTo(map);

    return control;
};

const enableSafeScrollZoom = (map, hintElement = null) => {
    const container = map.getContainer();
    const hint = hintElement ?? document.createElement('div');
    const inactiveMessage = 'Klik peta lalu gulir untuk zoom';
    const activeMessage = 'Zoom mouse aktif · Esc untuk keluar';

    hint.classList.add('tambora-map-zoom-hint');
    hint.setAttribute('aria-live', 'polite');
    hint.textContent = inactiveMessage;

    if (!hintElement) {
        container.append(hint);
    }

    map.scrollWheelZoom.disable();

    const deactivate = () => {
        if (!map.scrollWheelZoom.enabled()) {
            return;
        }

        map.scrollWheelZoom.disable();
        container.dataset.scrollZoom = 'inactive';
        hint.dataset.scrollZoom = 'inactive';
        hint.textContent = inactiveMessage;
    };

    const activate = () => {
        if (map.scrollWheelZoom.enabled()) {
            return;
        }

        map.scrollWheelZoom.enable();
        container.dataset.scrollZoom = 'active';
        hint.dataset.scrollZoom = 'active';
        hint.textContent = activeMessage;
        container.focus({ preventScroll: true });
    };

    map.on('click', activate);
    container.addEventListener('mouseleave', deactivate);
    container.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            deactivate();
            container.blur();
        }
    });

    container.dataset.scrollZoom = 'inactive';
    hint.dataset.scrollZoom = 'inactive';

    return { activate, deactivate };
};

window.TamboraMap = Object.freeze({
    addStyleControl,
    createBaseLayers,
    enableSafeScrollZoom,
});

window.dispatchEvent(new CustomEvent('tambora-map:ready'));
