import L from 'leaflet';
import markerIconUrl from 'leaflet/dist/images/marker-icon.png';
import markerIconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import markerShadowUrl from 'leaflet/dist/images/marker-shadow.png';

L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIconRetinaUrl,
    iconUrl: markerIconUrl,
    shadowUrl: markerShadowUrl,
});

const BYTES_PER_MEGABYTE = 1024 * 1024;
const MAX_EVIDENCE_FILES = 5;
const MAX_EVIDENCE_FILE_BYTES = 10 * BYTES_PER_MEGABYTE;
const MAX_TOTAL_EVIDENCE_BYTES = 50 * BYTES_PER_MEGABYTE;
const IMAGE_PRESERVE_BYTES = 1 * BYTES_PER_MEGABYTE;
const IMAGE_TARGET_BYTES = 1.25 * BYTES_PER_MEGABYTE;
const IMAGE_MAX_EDGE = 2048;
const COMPRESSIBLE_IMAGE_TYPES = new Set(['image/jpeg', 'image/png', 'image/webp']);

const formatFileSize = (bytes) => `${(bytes / BYTES_PER_MEGABYTE).toFixed(bytes >= BYTES_PER_MEGABYTE ? 1 : 2)} MB`;

const canvasToBlob = (canvas, type, quality) => new Promise((resolve, reject) => {
    canvas.toBlob((blob) => {
        if (blob) {
            resolve(blob);
            return;
        }

        reject(new Error('Browser tidak dapat memproses gambar ini.'));
    }, type, quality);
});

const loadImageSource = async (file) => {
    if ('createImageBitmap' in window) {
        try {
            const bitmap = await createImageBitmap(file, { imageOrientation: 'from-image' });

            return {
                height: bitmap.height,
                source: bitmap,
                width: bitmap.width,
                cleanup: () => bitmap.close(),
            };
        } catch {
            // Fall back to an HTML image for browsers with partial ImageBitmap support.
        }
    }

    const sourceUrl = URL.createObjectURL(file);
    const image = new Image();

    await new Promise((resolve, reject) => {
        image.addEventListener('load', resolve, { once: true });
        image.addEventListener('error', () => reject(new Error('Gambar tidak dapat dibaca.')), { once: true });
        image.src = sourceUrl;
    });

    return {
        height: image.naturalHeight,
        source: image,
        width: image.naturalWidth,
        cleanup: () => URL.revokeObjectURL(sourceUrl),
    };
};

const optimizedFileName = (fileName, mimeType) => {
    const extension = {
        'image/jpeg': 'jpg',
        'image/png': 'png',
        'image/webp': 'webp',
    }[mimeType] ?? 'jpg';
    const baseName = fileName.replace(/\.[^.]+$/, '') || 'bukti';

    return `${baseName}.${extension}`;
};

const optimizeEvidenceImage = async (file) => {
    if (!COMPRESSIBLE_IMAGE_TYPES.has(file.type) || file.size <= IMAGE_PRESERVE_BYTES) {
        return { file, originalSize: file.size, optimized: false };
    }

    const image = await loadImageSource(file);
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d', { alpha: true });

    if (!context) {
        image.cleanup();
        throw new Error('Browser tidak mendukung kompresi gambar.');
    }

    let scale = Math.min(1, IMAGE_MAX_EDGE / Math.max(image.width, image.height));
    let smallestBlob = null;

    try {
        for (let attempt = 0; attempt < 5; attempt += 1) {
            canvas.width = Math.max(1, Math.round(image.width * scale));
            canvas.height = Math.max(1, Math.round(image.height * scale));
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.imageSmoothingEnabled = true;
            context.imageSmoothingQuality = 'high';
            context.drawImage(image.source, 0, 0, canvas.width, canvas.height);

            const quality = Math.max(0.68, 0.88 - (attempt * 0.05));
            const blob = await canvasToBlob(canvas, 'image/webp', quality);

            if (!smallestBlob || blob.size < smallestBlob.size) {
                smallestBlob = blob;
            }

            if (blob.size <= IMAGE_TARGET_BYTES) {
                break;
            }

            scale *= 0.85;
        }
    } finally {
        image.cleanup();
    }

    if (!smallestBlob || smallestBlob.size >= file.size) {
        return { file, originalSize: file.size, optimized: false };
    }

    return {
        file: new File(
            [smallestBlob],
            optimizedFileName(file.name, smallestBlob.type),
            { type: smallestBlob.type, lastModified: file.lastModified },
        ),
        originalSize: file.size,
        optimized: true,
    };
};

/**
 * Accessible Custom Dropdown Enhancement
 * Conforms to strict rules:
 * - Trigger remains WHITE background (never turns gold when selected).
 * - Only the highlighted/active option in the open menu has #FFF4D6 / #F2B84B background.
 * - Text remains Navy #0B2342.
 * - Two-way sync with native select.
 */
const initCustomSelect = (selectElement) => {
    if (!selectElement || selectElement.dataset.customized === 'true') return;
    selectElement.dataset.customized = 'true';

    const wrapper = document.createElement('div');
    wrapper.className = 'tambora-select-wrap relative';

    // Create trigger button - ALWAYS WHITE, never gold
    const trigger = document.createElement('button');
    trigger.type = 'button';
    trigger.className = 'tambora-select-trigger';
    trigger.setAttribute('aria-haspopup', 'listbox');
    trigger.setAttribute('aria-expanded', 'false');

    const triggerText = document.createElement('span');
    triggerText.className = 'truncate text-left text-sm';

    const triggerIcon = document.createElement('span');
    triggerIcon.className = 'tambora-select-arrow shrink-0 ml-2';
    triggerIcon.innerHTML = `<svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>`;

    trigger.append(triggerText, triggerIcon);

    // Create menu listbox
    const menu = document.createElement('div');
    menu.className = 'tambora-select-menu hidden';
    menu.setAttribute('role', 'listbox');
    menu.setAttribute('tabindex', '-1');

    const updateTriggerText = () => {
        const selectedOption = selectElement.selectedOptions[0];
        if (selectedOption && selectedOption.value) {
            triggerText.textContent = selectedOption.textContent;
            triggerText.className = 'truncate text-left text-sm text-[#0B2342] font-medium';
        } else {
            triggerText.textContent = selectElement.options[0]?.textContent || 'Pilih opsi';
            triggerText.className = 'truncate text-left text-sm text-[#64748B]';
        }
    };

    const renderOptions = () => {
        menu.replaceChildren();
        const currentValue = selectElement.value;

        [...selectElement.options].forEach((option, index) => {
            const item = document.createElement('div');
            item.className = 'tambora-select-option';
            if (selectElement.id === 'incident_type') {
                item.classList.add('uppercase');
            }
            item.setAttribute('role', 'option');
            item.setAttribute('tabindex', '0');
            item.dataset.value = option.value;
            item.textContent = option.textContent;

            const isSelected = option.value === currentValue || (!currentValue && index === 0);
            if (isSelected && option.value) {
                item.classList.add('is-selected');
                item.setAttribute('aria-selected', 'true');
            } else {
                item.setAttribute('aria-selected', 'false');
            }

            item.addEventListener('click', (e) => {
                e.stopPropagation();
                selectElement.value = option.value;
                selectElement.dispatchEvent(new Event('change', { bubbles: true }));
                selectElement.dispatchEvent(new Event('input', { bubbles: true }));
                closeMenu();
                trigger.focus();
            });

            item.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    item.click();
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    (item.nextElementSibling || menu.firstElementChild)?.focus();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    (item.previousElementSibling || menu.lastElementChild)?.focus();
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    closeMenu();
                    trigger.focus();
                }
            });

            menu.appendChild(item);
        });
    };

    const openMenu = () => {
        renderOptions();
        menu.classList.remove('hidden');
        trigger.setAttribute('aria-expanded', 'true');
        const selectedItem = menu.querySelector('.is-selected') || menu.firstElementChild;
        selectedItem?.focus();
    };

    const closeMenu = () => {
        menu.classList.add('hidden');
        trigger.setAttribute('aria-expanded', 'false');
    };

    trigger.addEventListener('click', () => {
        const isOpen = !menu.classList.contains('hidden');
        if (isOpen) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    trigger.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            openMenu();
        }
    });

    document.addEventListener('click', (e) => {
        if (!wrapper.contains(e.target)) {
            closeMenu();
        }
    });

    selectElement.addEventListener('change', () => {
        updateTriggerText();
        renderOptions();
        trigger.classList.remove('is-invalid');
    });

    // Intercept native value setter so programmatic updates (e.g. reverse geocode) auto-sync
    const originalValueDesc = Object.getOwnPropertyDescriptor(HTMLSelectElement.prototype, 'value');
    if (originalValueDesc && originalValueDesc.set) {
        Object.defineProperty(selectElement, 'value', {
            get() {
                return originalValueDesc.get.call(this);
            },
            set(newValue) {
                originalValueDesc.set.call(this, newValue);
                updateTriggerText();
                renderOptions();
            },
            configurable: true,
        });
    }

    // Insert wrapper and hide native select off-screen while retaining form validation
    selectElement.parentNode.insertBefore(wrapper, selectElement);
    wrapper.appendChild(selectElement);
    wrapper.appendChild(trigger);
    wrapper.appendChild(menu);

    selectElement.style.position = 'absolute';
    selectElement.style.opacity = '0';
    selectElement.style.pointerEvents = 'none';
    selectElement.style.height = '1px';
    selectElement.style.width = '1px';
    selectElement.style.clip = 'rect(0, 0, 0, 0)';

    updateTriggerText();
};

const form = document.querySelector('#report-form');

if (form) {
    const steps = [...form.querySelectorAll('.form-step')];
    const progressItems = [...document.querySelectorAll('[data-progress]')];
    const progressTrack = document.querySelector('#stepper-progress-fill');
    const previousButton = document.querySelector('#previous-step');
    const nextButton = document.querySelector('#next-step');
    const submitButton = document.querySelector('#submit-report');
    const description = document.querySelector('#description');
    const descriptionCount = document.querySelector('#description-count');
    const evidence = document.querySelector('#evidence');
    const fileList = document.querySelector('#file-list');
    const uploadZone = document.querySelector('.upload-zone');
    const evidencePreviewModal = document.querySelector('#evidence-preview-modal');
    const evidencePreviewImage = document.querySelector('#evidence-preview-image');
    const evidencePreviewTitle = document.querySelector('#evidence-preview-title');
    const evidencePreviewMeta = document.querySelector('#evidence-preview-meta');
    const evidencePreviewCloseButton = document.querySelector('#evidence-preview-close');
    const latitude = document.querySelector('#latitude');
    const longitude = document.querySelector('#longitude');
    const accuracy = document.querySelector('#location_accuracy');
    const mapMessage = document.querySelector('#map-message');
    const stepStatus = document.querySelector('#form-step-status');
    const reviewIncident = document.querySelector('#review-incident');
    const reviewDate = document.querySelector('#review-date');
    const reviewLocation = document.querySelector('#review-location');
    const locationDetailsSummary = document.querySelector('#location-details-summary');
    let currentStep = 1;
    let map;
    let marker;
    let reverseTimer;
    let isOptimizingEvidence = false;
    let evidencePreviewUrls = [];
    let evidencePreviewTrigger = null;

    // Enhance dropdowns
    document.querySelectorAll('#incident_type, #regency').forEach(initCustomSelect);

    const firstInvalidStep = form.querySelector('.is-invalid, .form-error')?.closest('.form-step');
    const urlStep = new URLSearchParams(window.location.search).get('step');
    if (firstInvalidStep) {
        currentStep = Number(firstInvalidStep.dataset.step);
    } else if (urlStep && [1, 2, 3].includes(Number(urlStep))) {
        currentStep = Number(urlStep);
    }

    const updateReview = () => {
        const incidentType = document.querySelector('#incident_type');
        const incidentDate = document.querySelector('#incident_date').value;
        const district = document.querySelector('#district').value.trim();
        const village = document.querySelector('#village').value.trim();
        const regency = document.querySelector('#regency').value;

        reviewIncident.textContent = incidentType.selectedOptions[0]?.textContent || 'Belum dipilih';
        reviewDate.textContent = incidentDate
            ? new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium' }).format(new Date(`${incidentDate}T00:00:00`))
            : 'Belum dipilih';
        reviewLocation.textContent = [village, district, regency].filter(Boolean).join(', ') || 'Belum dipilih';
    };

    const setStep = (step, shouldScroll = true) => {
        currentStep = Math.min(Math.max(step, 1), steps.length);

        steps.forEach((item) => item.classList.toggle('hidden', Number(item.dataset.step) !== currentStep));
        progressItems.forEach((item) => {
            const itemStep = Number(item.dataset.progress);
            item.classList.toggle('is-active', itemStep === currentStep);
            item.classList.toggle('is-complete', itemStep < currentStep);
            item.disabled = itemStep >= currentStep;

            if (itemStep === currentStep) {
                item.setAttribute('aria-current', 'step');
            } else {
                item.removeAttribute('aria-current');
            }
        });

        if (progressTrack) {
            const pct = ((currentStep - 1) / (steps.length - 1)) * 100;
            progressTrack.style.width = `${pct}%`;
        }

        previousButton.classList.toggle('hidden', currentStep === 1);
        nextButton.classList.toggle('hidden', currentStep === steps.length);
        submitButton.classList.toggle('hidden', currentStep !== steps.length);
        stepStatus.textContent = `Langkah ${currentStep} dari ${steps.length}`;
        nextButton.textContent = currentStep === 1 ? 'Lanjut ke lokasi' : 'Lanjut ke bukti';

        if (currentStep === 2) {
            initializeMap();
            window.setTimeout(() => map?.invalidateSize(), 50);
        }

        if (currentStep === 3) {
            updateReview();
        }

        if (shouldScroll) {
            window.scrollTo({ top: form.offsetTop - 100, behavior: 'smooth' });
        }
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
        mapMessage.className = `mt-3 text-xs sm:text-sm font-semibold ${isError ? 'text-[#DC4C4C]' : 'text-[#2E9B68]'}`;
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
        showMapMessage('Titik lokasi sudah dipilih. Anda dapat menggeser penanda jika perlu.');
        return true;
    };

    const normalizeRegency = (address) => {
        const options = [...document.querySelector('#regency').options];
        const findExactOption = (value) => options.find(
            (option) => option.value.toLowerCase() === value.toLowerCase(),
        )?.value;
        const withoutPrefix = (value) => value.replace(/^(kabupaten|kota)\s+/i, '').trim();
        const city = address.city || address.town;
        const regency = address.regency || address.county;

        if (city) {
            const cityMatch = findExactOption(city) || findExactOption(`Kota ${withoutPrefix(city)}`);
            if (cityMatch) return cityMatch;
        }

        if (regency) {
            const regencyMatch = findExactOption(regency) || findExactOption(`Kabupaten ${withoutPrefix(regency)}`);
            if (regencyMatch) return regencyMatch;
        }

        const district = address.municipality || address.state_district || '';

        return findExactOption(district)
            || findExactOption(`Kabupaten ${withoutPrefix(district)}`)
            || findExactOption(`Kota ${withoutPrefix(district)}`);
    };

    const fillAddress = (result) => {
        const address = result.address || {};
        const regency = normalizeRegency(address);
        if (regency) document.querySelector('#regency').value = regency;
        document.querySelector('#district').value = address.suburb || address.district || address.municipality || '';
        document.querySelector('#village').value = address.village || address.hamlet || address.neighbourhood || '';
        document.querySelector('#address').value = [address.road, address.house_number, address.shop].filter(Boolean).join(' ') || result.display_name?.split(',').slice(0, 2).join(',') || '';
        if (locationDetailsSummary?.firstChild) {
            locationDetailsSummary.firstChild.textContent = 'Petunjuk alamat terisi otomatis ';
        }
    };

    const reverseGeocode = async (lat, lng) => {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}&addressdetails=1&accept-language=id`);
            if (response.ok) fillAddress(await response.json());
        } catch {
            showMapMessage('Titik sudah dipilih. Detail alamat dapat Anda lengkapi secara manual.');
        }
    };

    const scheduleReverseGeocode = (lat, lng) => {
        window.clearTimeout(reverseTimer);
        reverseTimer = window.setTimeout(() => reverseGeocode(lat, lng), 450);
    };

    const initializeMap = () => {
        if (map) return;

        const mapElement = document.querySelector('#report-map');
        const layers = window.TamboraMap.createBaseLayers(L, mapElement.dataset.mapboxToken);

        map = L.map(mapElement, {
            zoomControl: true,
            minZoom: 7,
            maxZoom: 19,
            scrollWheelZoom: false,
            wheelDebounceTime: 40,
            wheelPxPerZoomLevel: 100,
        }).setView([-8.72, 117.35], 8);
        window.TamboraMap.addStyleControl(L, map, layers);
        window.TamboraMap.enableSafeScrollZoom(map, document.querySelector('#report-map-zoom-hint'));

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
        showMapMessage('Sedang mencari lokasi di NTB…');

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
            showMapMessage('Pencarian sedang tidak tersedia. Anda dapat langsung memilih titik pada peta.', true);
        } finally {
            button.disabled = false;
            button.textContent = 'Cari';
        }
    };

    nextButton.addEventListener('click', () => {
        if (validateStep()) setStep(currentStep + 1);
    });
    previousButton.addEventListener('click', () => setStep(currentStep - 1));
    progressItems.forEach((item) => {
        item.addEventListener('click', () => {
            const destination = Number(item.dataset.progress);
            if (destination < currentStep) setStep(destination);
        });
    });

    const formatDateInput = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    };

    document.querySelectorAll('[data-date-offset]').forEach((button) => {
        button.addEventListener('click', () => {
            const date = new Date();
            date.setDate(date.getDate() - Number(button.dataset.dateOffset));
            document.querySelector('#incident_date').value = formatDateInput(date);

            document.querySelectorAll('[data-date-offset]').forEach((item) => {
                item.setAttribute('aria-pressed', String(item === button));
            });
        });
    });

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

    const showEvidenceMessage = (message, isError = false) => {
        evidencePreviewUrls.forEach((url) => URL.revokeObjectURL(url));
        evidencePreviewUrls = [];
        fileList.replaceChildren();
        const notice = document.createElement('p');
        notice.className = isError ? 'form-error' : 'rounded-xl bg-blue-50/70 border border-blue-100 px-4 py-3 text-xs font-semibold text-[#0B2342]';
        notice.textContent = message;
        fileList.append(notice);
    };

    const closeEvidencePreview = () => {
        if (!evidencePreviewModal || evidencePreviewModal.classList.contains('hidden')) return;

        evidencePreviewModal.classList.add('hidden');
        evidencePreviewModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        evidencePreviewImage.removeAttribute('src');
        evidencePreviewTrigger?.focus();
        evidencePreviewTrigger = null;
    };

    const openEvidencePreview = ({ name, size, url }, trigger) => {
        if (!evidencePreviewModal || !evidencePreviewImage) return;

        evidencePreviewTrigger = trigger;
        evidencePreviewTitle.textContent = name;
        evidencePreviewMeta.textContent = `${formatFileSize(size)} · siap dilampirkan`;
        evidencePreviewImage.src = url;
        evidencePreviewImage.alt = `Pratinjau ${name}`;
        evidencePreviewModal.classList.remove('hidden');
        evidencePreviewModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        evidencePreviewCloseButton?.focus();
    };

    evidencePreviewModal?.querySelectorAll('[data-evidence-preview-close]').forEach((button) => {
        button.addEventListener('click', closeEvidencePreview);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeEvidencePreview();
    });

    const renderEvidenceFiles = (files) => {
        evidencePreviewUrls.forEach((url) => URL.revokeObjectURL(url));
        evidencePreviewUrls = [];
        fileList.replaceChildren();

        files.forEach(({ file, originalSize, optimized }) => {
            const item = document.createElement('div');
            item.className = 'overflow-hidden rounded-2xl border border-[#E2E8F0] bg-white text-xs shadow-sm';

            if (file.type.startsWith('image/')) {
                const previewUrl = URL.createObjectURL(file);
                evidencePreviewUrls.push(previewUrl);

                const previewButton = document.createElement('button');
                previewButton.type = 'button';
                previewButton.className = 'group relative block aspect-[4/3] w-full overflow-hidden bg-white border-b border-slate-100 focus:outline-none focus:ring-4 focus:ring-inset focus:ring-blue-500/40';
                previewButton.setAttribute('aria-label', `Perbesar pratinjau ${file.name}`);

                const previewImage = document.createElement('img');
                previewImage.src = previewUrl;
                previewImage.alt = `Pratinjau ${file.name}`;
                previewImage.className = 'size-full object-contain transition duration-300 group-hover:scale-[1.02]';

                const previewLabel = document.createElement('span');
                previewLabel.className = 'absolute inset-x-3 bottom-3 flex items-center justify-center gap-1.5 rounded-full bg-slate-950/75 px-3 py-2 text-[11px] font-semibold text-white opacity-100 shadow-lg backdrop-blur-sm transition sm:opacity-0 sm:group-hover:opacity-100 sm:group-focus-visible:opacity-100';
                previewLabel.innerHTML = '<svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12s3-5.25 8.25-5.25S20.25 12 20.25 12 17.25 17.25 12 17.25 3.75 12 3.75 12Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 12a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg><span>Perbesar foto</span>';

                previewButton.append(previewImage, previewLabel);
                previewButton.addEventListener('click', () => openEvidencePreview({
                    name: file.name,
                    size: file.size,
                    url: previewUrl,
                }, previewButton));
                item.append(previewButton);
            } else {
                const documentPreview = document.createElement('div');
                documentPreview.className = 'grid aspect-[4/3] place-items-center bg-slate-50 text-slate-400';
                documentPreview.innerHTML = '<div class="flex flex-col items-center gap-2"><svg class="size-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg><span class="text-[11px] font-semibold uppercase tracking-wider">Dokumen PDF</span></div>';
                item.append(documentPreview);
            }

            const footer = document.createElement('div');
            footer.className = 'flex items-center justify-between gap-3 px-3.5 py-3';

            const details = document.createElement('span');
            details.className = 'min-w-0';

            const name = document.createElement('strong');
            name.className = 'block truncate font-semibold text-[#0B2342]';
            name.textContent = file.name;

            const result = document.createElement('span');
            result.className = optimized ? 'mt-1 block text-[#2E9B68] font-medium' : 'mt-1 block text-[#64748B]';
            result.textContent = optimized
                ? `${formatFileSize(originalSize)} menjadi ${formatFileSize(file.size)} · dioptimalkan`
                : `${formatFileSize(file.size)} · ukuran asli`;

            const badge = document.createElement('span');
            badge.className = optimized
                ? 'shrink-0 rounded-lg bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-[#2E9B68]'
                : 'shrink-0 rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-[#64748B]';
            badge.textContent = optimized ? 'Siap kirim' : 'Asli';

            details.append(name, result);
            footer.append(details, badge);
            item.append(footer);
            fileList.append(item);
        });
    };

    evidence.addEventListener('change', async () => {
        const files = [...evidence.files];
        fileList.replaceChildren();

        if (files.length > MAX_EVIDENCE_FILES) {
            evidence.value = '';
            showEvidenceMessage('Maksimal 5 berkas dapat dilampirkan.', true);
            return;
        }

        if (!files.length) {
            return;
        }

        isOptimizingEvidence = true;
        evidence.disabled = true;
        submitButton.disabled = true;
        submitButton.textContent = 'Menyiapkan berkas…';
        uploadZone.setAttribute('aria-busy', 'true');

        const optimizedFiles = [];

        try {
            for (const [index, file] of files.entries()) {
                showEvidenceMessage(`Menyiapkan berkas ${index + 1} dari ${files.length}…`);

                try {
                    optimizedFiles.push(await optimizeEvidenceImage(file));
                } catch {
                    optimizedFiles.push({ file, originalSize: file.size, optimized: false });
                }
            }

            const oversizedFile = optimizedFiles.find(({ file }) => file.size > MAX_EVIDENCE_FILE_BYTES);
            if (oversizedFile) {
                evidence.value = '';
                showEvidenceMessage(`${oversizedFile.file.name} melebihi batas 10 MB. Pilih berkas yang lebih kecil.`, true);
                return;
            }

            const totalSize = optimizedFiles.reduce((total, { file }) => total + file.size, 0);
            if (totalSize > MAX_TOTAL_EVIDENCE_BYTES) {
                evidence.value = '';
                showEvidenceMessage('Total seluruh berkas melebihi 50 MB. Kurangi jumlah atau ukuran berkas.', true);
                return;
            }

            const dataTransfer = new DataTransfer();
            optimizedFiles.forEach(({ file }) => dataTransfer.items.add(file));
            evidence.files = dataTransfer.files;
            renderEvidenceFiles(optimizedFiles);
        } finally {
            isOptimizingEvidence = false;
            evidence.disabled = false;
            submitButton.disabled = false;
            submitButton.textContent = 'Kirim laporan';
            uploadZone.removeAttribute('aria-busy');
        }
    });

    form.addEventListener('submit', (event) => {
        if (isOptimizingEvidence) {
            event.preventDefault();
            showEvidenceMessage('Tunggu sebentar, berkas sedang disiapkan sebelum dikirim.');
            return;
        }

        if (!validateStep() || !form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = 'Mengirim laporan…';
    });

    setStep(currentStep, Boolean(firstInvalidStep));
}
