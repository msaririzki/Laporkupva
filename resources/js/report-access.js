const loadImage = (source) => new Promise((resolve, reject) => {
    const image = new Image();

    image.addEventListener('load', () => resolve(image), { once: true });
    image.addEventListener('error', () => reject(new Error('Gambar tidak dapat dibaca.')), { once: true });
    image.src = source;
});

const drawRoundedRectangle = (context, x, y, width, height, radius, fillStyle, strokeStyle = null) => {
    context.beginPath();
    context.roundRect(x, y, width, height, radius);
    context.fillStyle = fillStyle;
    context.fill();

    if (strokeStyle) {
        context.strokeStyle = strokeStyle;
        context.lineWidth = 2;
        context.stroke();
    }
};

const drawContainedImage = (context, image, x, y, width, height) => {
    const scale = Math.min(width / image.naturalWidth, height / image.naturalHeight);
    const renderedWidth = image.naturalWidth * scale;
    const renderedHeight = image.naturalHeight * scale;

    context.drawImage(
        image,
        x + ((width - renderedWidth) / 2),
        y + ((height - renderedHeight) / 2),
        renderedWidth,
        renderedHeight,
    );
};

const drawCenteredText = (context, text, x, y, font, color) => {
    context.font = font;
    context.fillStyle = color;
    context.textAlign = 'center';
    context.fillText(text, x, y);
};

const initializeAccessCardDownload = () => {
    const accessCard = document.querySelector('[data-access-card]');
    const downloadButton = document.querySelector('[data-download-access]');

    if (!accessCard || !downloadButton) {
        return;
    }

    const downloadLabel = downloadButton.querySelector('[data-download-label]');
    const downloadStatus = document.querySelector('[data-access-download-status]');

    downloadButton.addEventListener('click', async () => {
        downloadButton.disabled = true;
        downloadLabel.textContent = 'Menyiapkan gambar…';
        downloadStatus.textContent = '';

        try {
            await document.fonts?.ready;

            const [logo, qrCode] = await Promise.all([
                loadImage(accessCard.dataset.logoUrl),
                loadImage(document.querySelector('#tracking-qr').src),
            ]);
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');

            canvas.width = 1200;
            canvas.height = 1500;

            context.fillStyle = '#F4F7FB';
            context.fillRect(0, 0, canvas.width, canvas.height);

            const headerGradient = context.createLinearGradient(0, 0, canvas.width, 300);
            headerGradient.addColorStop(0, '#0B2342');
            headerGradient.addColorStop(1, '#174B7A');
            context.fillStyle = headerGradient;
            context.fillRect(0, 0, canvas.width, 300);

            drawRoundedRectangle(context, 72, 58, 320, 150, 26, '#FFFFFF');
            drawContainedImage(context, logo, 96, 78, 272, 110);

            context.textAlign = 'left';
            context.fillStyle = '#FFFFFF';
            context.font = '800 48px "Plus Jakarta Sans", Arial, sans-serif';
            context.fillText('Akses Laporan', 460, 115);
            context.fillStyle = '#C9D8EA';
            context.font = '600 25px "Plus Jakarta Sans", Arial, sans-serif';
            context.fillText('TAMBORA · Bank Indonesia NTB', 460, 160);
            context.font = '500 22px "Plus Jakarta Sans", Arial, sans-serif';
            context.fillText('Simpan gambar ini di tempat yang aman.', 460, 205);

            drawCenteredText(context, 'KODE LAPORAN', 330, 385, '700 22px "Plus Jakarta Sans", Arial, sans-serif', '#64748B');
            drawRoundedRectangle(context, 70, 415, 520, 170, 28, '#FFFFFF', '#DCE5F0');
            drawCenteredText(context, accessCard.dataset.code, 330, 515, '800 42px "Plus Jakarta Sans", Arial, sans-serif', '#0B2342');

            drawCenteredText(context, 'PIN PELACAKAN', 870, 385, '700 22px "Plus Jakarta Sans", Arial, sans-serif', '#64748B');
            drawRoundedRectangle(context, 610, 415, 520, 170, 28, '#FFFFFF', '#DCE5F0');
            drawCenteredText(context, accessCard.dataset.pin, 870, 515, '800 52px "Plus Jakarta Sans", Arial, sans-serif', '#2563EB');

            drawCenteredText(context, 'PINDAI UNTUK MEMBUKA STATUS', 600, 665, '800 24px "Plus Jakarta Sans", Arial, sans-serif', '#0B2342');
            drawRoundedRectangle(context, 350, 700, 500, 500, 32, '#FFFFFF', '#DCE5F0');
            context.drawImage(qrCode, 390, 740, 420, 420);

            drawCenteredText(context, `Dikirim ${accessCard.dataset.submittedAt}`, 600, 1265, '600 22px "Plus Jakarta Sans", Arial, sans-serif', '#64748B');
            drawRoundedRectangle(context, 70, 1310, 1060, 120, 24, '#FFF4D6', '#F3D58A');
            drawCenteredText(context, 'RAHASIA · Siapa pun yang memiliki gambar ini dapat membuka laporan.', 600, 1370, '800 22px "Plus Jakarta Sans", Arial, sans-serif', '#8A5411');
            drawCenteredText(context, window.location.host, 600, 1468, '600 20px "Plus Jakarta Sans", Arial, sans-serif', '#64748B');

            const blob = await new Promise((resolve, reject) => {
                canvas.toBlob((result) => {
                    if (result) {
                        resolve(result);
                    } else {
                        reject(new Error('Gambar akses gagal dibuat.'));
                    }
                }, 'image/png');
            });
            const objectUrl = URL.createObjectURL(blob);
            const link = document.createElement('a');

            link.href = objectUrl;
            link.download = `akses-tambora-${accessCard.dataset.code}.png`;
            link.click();
            window.setTimeout(() => URL.revokeObjectURL(objectUrl), 1000);
            downloadStatus.textContent = 'Gambar akses berhasil diunduh.';
        } catch {
            downloadStatus.textContent = 'Gambar belum dapat dibuat. Coba unduh kembali.';
        } finally {
            downloadButton.disabled = false;
            downloadLabel.textContent = 'Unduh gambar akses';
        }
    });
};

const qrImageSource = async (file) => {
    if ('createImageBitmap' in window) {
        return createImageBitmap(file, { imageOrientation: 'from-image' });
    }

    const objectUrl = URL.createObjectURL(file);

    try {
        return await loadImage(objectUrl);
    } finally {
        URL.revokeObjectURL(objectUrl);
    }
};

const accessDetailsFromQrValue = (value) => {
    try {
        const url = new URL(value, window.location.origin);

        if (url.origin !== window.location.origin || url.pathname !== window.location.pathname) {
            return null;
        }

        const accessToken = new URLSearchParams(url.hash.slice(1)).get('access');

        if (accessToken) {
            return { accessToken };
        }

        const trackingCode = url.searchParams.get('code');

        return trackingCode ? { trackingCode } : null;
    } catch {
        return null;
    }
};

const initializeQrAccess = () => {
    const form = document.querySelector('#tracking-form');
    const qrUpload = document.querySelector('[data-qr-upload]');

    if (!form || !qrUpload) {
        return;
    }

    const accessTokenInput = form.querySelector('#access_token');
    const trackingCodeInput = form.querySelector('#tracking_code');
    const qrStatus = form.querySelector('[data-qr-status]');

    const showStatus = (message, isError = false) => {
        qrStatus.textContent = message;
        qrStatus.className = `mt-2 rounded-lg px-3 py-2 text-center text-xs font-semibold ${isError ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700'}`;
    };

    const useAccessDetails = (details) => {
        if (details?.accessToken) {
            accessTokenInput.value = details.accessToken;
            showStatus('QR terbaca. Membuka status laporan…');
            window.setTimeout(() => HTMLFormElement.prototype.submit.call(form), 250);

            return true;
        }

        if (details?.trackingCode) {
            trackingCodeInput.value = details.trackingCode;
            trackingCodeInput.dispatchEvent(new Event('input', { bubbles: true }));
            showStatus('Kode laporan ditemukan. Masukkan PIN untuk melanjutkan.');
            form.querySelector('#tracking_pin').focus();

            return true;
        }

        return false;
    };

    const hashAccessToken = new URLSearchParams(window.location.hash.slice(1)).get('access');

    if (hashAccessToken) {
        window.history.replaceState(null, '', `${window.location.pathname}${window.location.search}`);
        useAccessDetails({ accessToken: hashAccessToken });
    }

    qrUpload.addEventListener('change', async () => {
        const file = qrUpload.files?.[0];

        if (!file) {
            return;
        }

        if (file.size > 8 * 1024 * 1024) {
            showStatus('Ukuran gambar QR maksimal 8 MB.', true);
            qrUpload.value = '';

            return;
        }

        if (!('BarcodeDetector' in window)) {
            showStatus('Browser ini belum mendukung pembacaan QR dari gambar. Gunakan kamera ponsel atau isi kode dan PIN.', true);
            qrUpload.value = '';

            return;
        }

        showStatus('Membaca QR di perangkat Anda…');

        try {
            const detector = new BarcodeDetector({ formats: ['qr_code'] });
            const imageSource = await qrImageSource(file);
            const detectedCodes = await detector.detect(imageSource);

            imageSource.close?.();

            const details = detectedCodes
                .map(({ rawValue }) => accessDetailsFromQrValue(rawValue))
                .find(Boolean);

            if (!useAccessDetails(details)) {
                showStatus('QR TAMBORA tidak ditemukan. Pastikan gambar terang dan QR terlihat utuh.', true);
            }
        } catch {
            showStatus('QR belum dapat dibaca. Coba gambar yang lebih jelas atau isi akses secara manual.', true);
        } finally {
            qrUpload.value = '';
        }
    });
};

initializeAccessCardDownload();
initializeQrAccess();
