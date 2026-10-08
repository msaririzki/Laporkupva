import * as L from 'leaflet';
import './map-layers';

export const initKupvaLocationEditor = (element, state, watch) => {
    const canvas = element.querySelector('[data-kupva-location-editor]');
    if (!canvas || !element.isConnected) return;

    const validPoint = () => state.latitude !== null && state.longitude !== null
        && String(state.latitude).trim() !== '' && String(state.longitude).trim() !== ''
        && Number.isFinite(Number(state.latitude)) && Number.isFinite(Number(state.longitude))
        && Number(state.latitude) >= -11 && Number(state.latitude) <= -8
        && Number(state.longitude) >= 115 && Number(state.longitude) <= 120;
    const point = () => [Number(state.latitude), Number(state.longitude)];
    const map = L.map(canvas, { minZoom: 6, maxZoom: 19, scrollWheelZoom: false })
        .setView(validPoint() ? point() : [-8.65, 117.6], validPoint() ? 17 : 8);
    const layers = window.TamboraMap.createBaseLayers(L, '');
    window.TamboraMap.addStyleControl(L, map, layers);
    window.TamboraMap.enableSafeScrollZoom(map);
    const icon = L.divIcon({
        className: 'kupva-map-marker',
        html: '<span class="kupva-map-marker__pin"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 21V4h14v17M3 21h18M9 8h1m4 0h1m-6 4h1m4 0h1m-6 9v-5h6v5"/></svg></span>',
        iconSize: [38, 44], iconAnchor: [19, 44],
    });
    let marker = null;
    const setPoint = ({ lat, lng }) => {
        if (lat < -11 || lat > -8 || lng < 115 || lng > 120) {
            state.error = 'Pilih titik dalam rentang wilayah NTB.';
            if (validPoint()) marker?.setLatLng(point());
            return;
        }
        state.error = '';
        state.latitude = Number(lat.toFixed(7));
        state.longitude = Number(lng.toFixed(7));
        state.locationInput = null;
        updateMarker();
    };
    const updateMarker = () => {
        if (!validPoint()) {
            if (marker) map.removeLayer(marker);
            marker = null;
            return;
        }
        if (marker) {
            marker.setLatLng(point());
        } else {
            marker = L.marker(point(), { icon, draggable: true, autoPan: true, title: 'Geser untuk mengoreksi titik usaha' }).addTo(map);
            marker.on('dragend', () => setPoint(marker.getLatLng()));
        }
        map.setView(point(), Math.max(map.getZoom(), 16));
    };
    map.on('click', event => setPoint(event.latlng));
    watch('latitude', updateMarker);
    watch('longitude', updateMarker);
    updateMarker();
    const resizeObserver = new ResizeObserver(() => map.invalidateSize());
    resizeObserver.observe(canvas);
    state.cleanup = () => { resizeObserver.disconnect(); map.remove(); };
    state.ready = true;
    requestAnimationFrame(() => map.invalidateSize());
};

window.TamboraKupvaLocationEditor = { init: initKupvaLocationEditor };
