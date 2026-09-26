import './map-layers';
import './report-form';
import './report-access';

// Mobile navigation toggle
const mobileMenuButton = document.querySelector('#mobile-menu-button');
const mobileNav = document.querySelector('#mobile-nav');
const mobileOpenIcon = document.querySelector('#mobile-menu-open-icon');
const mobileCloseIcon = document.querySelector('#mobile-menu-close-icon');

if (mobileMenuButton && mobileNav) {
    mobileMenuButton.addEventListener('click', () => {
        const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
        mobileMenuButton.setAttribute('aria-expanded', String(!isExpanded));
        mobileNav.classList.toggle('hidden', isExpanded);
        mobileOpenIcon?.classList.toggle('hidden', !isExpanded);
        mobileCloseIcon?.classList.toggle('hidden', isExpanded);
    });
}

// Copy access code & PIN
const copyButton = document.querySelector('[data-copy-access]');

copyButton?.addEventListener('click', async () => {
    const code = document.querySelector('#report-code')?.textContent?.trim();
    const pin = document.querySelector('#report-pin')?.textContent?.trim();

    if (!code || !pin) {
        return;
    }

    try {
        await navigator.clipboard.writeText(`Kode laporan: ${code}\nPIN: ${pin}`);
        copyButton.textContent = 'Berhasil disalin';
        window.setTimeout(() => {
            copyButton.textContent = 'Salin kode & PIN';
        }, 2200);
    } catch {
        window.prompt('Salin kode laporan dan PIN berikut:', `${code} / ${pin}`);
    }
});

// Format tracking code input
const trackingCode = document.querySelector('#tracking_code');

trackingCode?.addEventListener('input', (event) => {
    const raw = event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '').replace(/^LKP/, '');
    const parts = ['LKP', raw.slice(0, 4), raw.slice(4, 8)].filter(Boolean);
    event.target.value = parts.join('-');
});

// Lightweight status polling for public report tracking.
const liveRefresh = document.querySelector('[data-report-live-refresh]');

if (liveRefresh) {
    const updateUrl = liveRefresh.dataset.updateUrl;
    const liveRefreshLabel = liveRefresh.querySelector('[data-live-refresh-label]');
    const liveRefreshDot = liveRefresh.querySelector('[data-live-refresh-dot]');
    const messageField = document.querySelector('#body');
    let currentVersion = liveRefresh.dataset.version;
    let refreshTimer;
    let failedChecks = 0;
    let updateAvailable = false;

    const setLiveRefreshState = (label, state = 'active') => {
        liveRefreshLabel.textContent = label;
        liveRefresh.classList.remove(
            'border-emerald-200', 'bg-emerald-50', 'text-emerald-700',
            'border-amber-300', 'bg-amber-50', 'text-amber-800',
            'border-slate-200', 'bg-slate-50', 'text-slate-600',
        );

        const stateClasses = {
            active: ['border-emerald-200', 'bg-emerald-50', 'text-emerald-700'],
            available: ['border-amber-300', 'bg-amber-50', 'text-amber-800'],
            offline: ['border-slate-200', 'bg-slate-50', 'text-slate-600'],
        };

        liveRefresh.classList.add(...stateClasses[state]);
        liveRefreshDot.classList.toggle('hidden', state !== 'active');
    };

    const scheduleRefreshCheck = (delay = 5000) => {
        window.clearTimeout(refreshTimer);
        refreshTimer = window.setTimeout(checkForUpdates, delay);
    };

    const checkForUpdates = async () => {
        if (document.hidden || updateAvailable) {
            return;
        }

        try {
            const response = await fetch(updateUrl, {
                cache: 'no-store',
                credentials: 'same-origin',
                headers: { Accept: 'application/json' },
            });

            if (response.status === 404) {
                setLiveRefreshState('Akses berakhir — masuk kembali', 'offline');
                return;
            }

            if (!response.ok) {
                throw new Error('Status update check failed.');
            }

            const { version } = await response.json();
            failedChecks = 0;

            if (version !== currentVersion) {
                currentVersion = version;

                if (messageField?.value.trim()) {
                    updateAvailable = true;
                    setLiveRefreshState('Pembaruan baru — klik untuk memuat', 'available');
                    return;
                }

                setLiveRefreshState('Pembaruan ditemukan…', 'available');
                window.setTimeout(() => window.location.reload(), 450);
                return;
            }

            setLiveRefreshState('Pembaruan otomatis aktif');
            scheduleRefreshCheck();
        } catch {
            failedChecks += 1;
            setLiveRefreshState('Mencoba menghubungkan kembali…', 'offline');
            scheduleRefreshCheck(Math.min(30000, 5000 * (2 ** failedChecks)));
        }
    };

    liveRefresh.addEventListener('click', () => {
        if (updateAvailable) {
            window.location.reload();
            return;
        }

        window.clearTimeout(refreshTimer);
        checkForUpdates();
    });

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            window.clearTimeout(refreshTimer);
            checkForUpdates();
        }
    });

    window.addEventListener('online', checkForUpdates);
    window.addEventListener('offline', () => {
        window.clearTimeout(refreshTimer);
        setLiveRefreshState('Tidak ada koneksi', 'offline');
    });

    scheduleRefreshCheck();
}
