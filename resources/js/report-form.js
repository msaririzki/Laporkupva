import L from 'leaflet';

const form = document.querySelector('#report-form');

if (form) {
    const steps = [...form.querySelectorAll('.form-step')];
    const progressItems = [...document.querySelectorAll('[data-progress]')];
    const previousButton = document.querySelector('#previous-step');
    const nextButton = document.querySelector('#next-step');
    const submitButton = document.querySelector('#submit-report');
    const description = document.querySelector('#description');
    const descriptionCount = document.querySelector('#description-count');
    const evidence = document.querySelector('#evidence');
    const fileList = document.querySelector('#file-list');
    const latitude = document.querySelector('#latitude');
    const longitude = document.querySelector('#longitude');
    const accuracy = document.querySelector('#location_accuracy');
    const mapMessage = document.querySelector('#map-message');
    let currentStep = 1;
    let map;
    let marker;
    let reverseTimer;

    const firstInvalidStep = form.querySelector('.is-invalid, .form-error')?.closest('.form-step');
    if (firstInvalidStep) {
        currentStep = Number(firstInvalidStep.dataset.step);
    }

    const setStep = (step) => {
        currentStep = Math.min(Math.max(step, 1), steps.length);

        steps.forEach((item) => item.classList.toggle('hidden', Number(item.dataset.step) !== currentStep));
        progressItems.forEach((item) => {
            const itemStep = Number(item.dataset.progress);
            item.classList.toggle('is-active', itemStep === currentStep);
            item.classList.toggle('is-complete', itemStep < currentStep);
        });

        previousButton.classList.toggle('invisible', currentStep === 1);
        nextButton.classList.toggle('hidden', currentStep === steps.length);
        submitButton.classList.toggle('hidden', currentStep !== steps.length);

        if (currentStep === 2) {
            initializeMap();
            window.setTimeout(() => map?.invalidateSize(), 50);
        }

        window.scrollTo({ top: form.offsetTop - 120, behavior: 'smooth' });
    };

    const validateStep = () => {
        const step = steps[currentStep - 1];
        const requiredFields = [...step.querySelectorAll('[required]')];

        if (currentStep === 2 && (!latitude.value || !longitude.value)) {
            showMapMessage('Pilih titik lokasi kejadian pada peta terlebih dahulu.', true);
            return false;
        }

        for (const field of requiredFields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                field.focus({ preventScroll: false });
                return false;
            }
        }

        return true;
    };

    const showMapMessage = (message, isError = false) => {
        mapMessage.textContent = message;
        mapMessage.className = `mt-3 text-sm font-semibold ${isError ? 'text-red-600' : 'text-teal-700'}`;
    };

    const withinNtb = (lat, lng) => lat >= -11 && lat <= -8 && lng >= 115 && lng <= 120;

    const updatePoint = (lat, lng, locationAccuracy = '') => {
        if (!withinNtb(lat, lng)) {
            showMapMessage('Titik berada di luar wilayah Nusa Tenggara Barat.', true);
            return false;
        }

        latitude.value = Number(lat).toFixed(7);
        longitude.value = Number(lng).toFixed(7);
        accuracy.value = locationAccuracy ? Number(locationAccuracy).toFixed(2) : '';

        if (!marker) {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', () => {
                const point = marker.getLatLng();
                updatePoint(point.lat, point.lng);
                scheduleReverseGeocode(point.lat, point.lng);
            });
        } else {
            marker.setLatLng([lat, lng]);
        }

        map.panTo([lat, lng]);
        showMapMessage('Titik lokasi sudah dipilih. Anda masih dapat menggeser penandanya.');
        return true;
    };

    const normalizeRegency = (address) => {
        const source = `${address.city || ''} ${address.county || ''} ${address.state_district || ''}`.toLowerCase();
        const options = [...document.querySelector('#regency').options];
        return options.find((option) => {
            const candidate = option.value.toLowerCase().replace('kabupaten ', '').replace('kota ', '');
            return candidate && source.includes(candidate);
        })?.value;
    };

    const fillAddress = (result) => {
        const address = result.address || {};
        const regency = normalizeRegency(address);
        if (regency) document.querySelector('#regency').value = regency;
        document.querySelector('#district').value = address.suburb || address.district || address.municipality || '';
        document.querySelector('#village').value = address.village || address.hamlet || address.neighbourhood || '';
        document.querySelector('#address').value = [address.road, address.house_number, address.shop].filter(Boolean).join(' ') || result.display_name?.split(',').slice(0, 2).join(',') || '';
    };

    const reverseGeocode = async (lat, lng) => {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}&addressdetails=1&accept-language=id`);
            if (response.ok) fillAddress(await response.json());
        } catch {
            showMapMessage('Titik sudah dipilih. Detail alamat dapat Anda isi secara manual.');
        }
    };

    const scheduleReverseGeocode = (lat, lng) => {
        window.clearTimeout(reverseTimer);
        reverseTimer = window.setTimeout(() => reverseGeocode(lat, lng), 450);
    };

    const initializeMap = () => {
        if (map) return;

        map = L.map('report-map', { zoomControl: true, minZoom: 7, maxZoom: 19 }).setView([-8.72, 117.35], 8);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        map.on('click', (event) => {
            if (updatePoint(event.latlng.lat, event.latlng.lng)) scheduleReverseGeocode(event.latlng.lat, event.latlng.lng);
        });

        if (latitude.value && longitude.value) {
            const lat = Number(latitude.value);
            const lng = Number(longitude.value);
            updatePoint(lat, lng, accuracy.value);
            map.setView([lat, lng], 16);
        }

    };

    const searchLocation = async () => {
        const input = document.querySelector('#location-search');
        const query = input.value.trim();
        if (query.length < 3) {
            showMapMessage('Ketik minimal 3 karakter untuk mencari lokasi.', true);
            input.focus();
            return;
        }

        const button = document.querySelector('#search-location');
        button.disabled = true;
        button.textContent = 'Mencari…';
        showMapMessage('Sedang mencari lokasi di wilayah NTB…');

        try {
            const params = new URLSearchParams({
                format: 'jsonv2',
                q: `${query}, Nusa Tenggara Barat, Indonesia`,
                addressdetails: '1',
                limit: '1',
                countrycodes: 'id',
                viewbox: '115,-8,120,-11',
                bounded: '1',
                'accept-language': 'id',
            });
            const response = await fetch(`https://nominatim.openstreetmap.org/search?${params}`);
            const results = response.ok ? await response.json() : [];

            if (!results.length) {
                showMapMessage('Lokasi tidak ditemukan. Coba nama jalan atau wilayah yang lebih umum.', true);
                return;
            }

            const result = results[0];
            const lat = Number(result.lat);
            const lng = Number(result.lon);
            if (updatePoint(lat, lng)) {
                map.setView([lat, lng], 16);
                fillAddress(result);
            }
        } catch {
            showMapMessage('Pencarian sedang tidak tersedia. Anda tetap dapat memilih titik langsung pada peta.', true);
        } finally {
            button.disabled = false;
            button.textContent = 'Cari';
        }
    };

    nextButton.addEventListener('click', () => {
        if (validateStep()) setStep(currentStep + 1);
    });
    previousButton.addEventListener('click', () => setStep(currentStep - 1));

    description.addEventListener('input', () => {
        descriptionCount.textContent = `${description.value.length}/5000`;
    });
    description.dispatchEvent(new Event('input'));

    document.querySelector('#search-location').addEventListener('click', searchLocation);
    document.querySelector('#location-search').addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            searchLocation();
        }
    });

    document.querySelector('#use-location').addEventListener('click', () => {
        if (!navigator.geolocation) {
            showMapMessage('Perangkat ini tidak mendukung akses lokasi. Pilih titik pada peta.', true);
            return;
        }

        showMapMessage('Meminta lokasi perangkat…');
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const { latitude: lat, longitude: lng, accuracy: meters } = position.coords;
                if (updatePoint(lat, lng, meters)) {
                    map.setView([lat, lng], 17);
                    scheduleReverseGeocode(lat, lng);
                }
            },
            () => showMapMessage('Lokasi tidak dapat diakses. Izinkan akses lokasi atau pilih titik pada peta.', true),
            { enableHighAccuracy: true, timeout: 12000, maximumAge: 30000 },
        );
    });

    evidence.addEventListener('change', () => {
        const files = [...evidence.files];
        fileList.replaceChildren();

        if (files.length > 5) {
            evidence.value = '';
            const warning = document.createElement('p');
            warning.className = 'form-error';
            warning.textContent = 'Maksimal 5 berkas dapat dilampirkan.';
            fileList.append(warning);
            return;
        }

        files.forEach((file) => {
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs';
            const name = document.createElement('span');
            name.className = 'truncate font-semibold text-slate-700';
            name.textContent = file.name;
            const size = document.createElement('span');
            size.className = 'shrink-0 text-slate-400';
            size.textContent = `${(file.size / 1024 / 1024).toFixed(1)} MB`;
            item.append(name, size);
            fileList.append(item);
        });
    });

    form.addEventListener('submit', (event) => {
        if (!validateStep() || !form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = 'Mengirim laporan…';
    });

    setStep(currentStep);
}
