import './realtime';
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

// Copy the public report number.
const copyButton = document.querySelector('[data-copy-access]');

copyButton?.addEventListener('click', async () => {
    const code = document.querySelector('#report-code')?.textContent?.trim();

    if (!code) {
        return;
    }

    try {
        await navigator.clipboard.writeText(`Nomor laporan: ${code}`);
        copyButton.textContent = 'Berhasil disalin';
        window.setTimeout(() => {
            copyButton.textContent = 'Salin nomor laporan';
        }, 2200);
    } catch {
        window.prompt('Salin nomor laporan berikut:', code);
    }
});

// Format tracking code input
const trackingCode = document.querySelector('#tracking_code');

trackingCode?.addEventListener('input', (event) => {
    const raw = event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '').replace(/^LKP/, '');
    const parts = ['LKP', raw.slice(0, 4), raw.slice(4, 8)].filter(Boolean);
    event.target.value = parts.join('-');
});

// Real-time status and conversation updates with a lightweight polling fallback.
const liveRefresh = document.querySelector('[data-report-live-refresh]');

if (liveRefresh) {
    const updateUrl = liveRefresh.dataset.updateUrl;
    const channelName = liveRefresh.dataset.channel;
    const liveRefreshLabel = liveRefresh.querySelector('[data-live-refresh-label]');
    const liveRefreshDot = liveRefresh.querySelector('[data-live-refresh-dot]');
    const statusLabel = document.querySelector('[data-report-status-label]');
    const timeline = document.querySelector('[data-report-timeline]');
    const conversation = document.querySelector('[data-report-conversation]');
    let currentVersion = liveRefresh.dataset.version;
    let refreshTimer;
    let failedChecks = 0;
    let isChecking = false;

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

    const scheduleRefreshCheck = (delay = 30000) => {
        window.clearTimeout(refreshTimer);
        refreshTimer = window.setTimeout(checkForUpdates, delay);
    };

    const checkForUpdates = async (fromRealtime = false) => {
        if (isChecking || (!fromRealtime && document.hidden)) {
            return;
        }

        isChecking = true;

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

            const update = await response.json();
            failedChecks = 0;

            if (update.version !== currentVersion) {
                currentVersion = update.version;
                liveRefresh.dataset.version = update.version;

                if (statusLabel) {
                    statusLabel.textContent = update.status_label;
                }

                if (timeline) {
                    timeline.innerHTML = update.timeline_html;
                }

                if (conversation) {
                    conversation.innerHTML = update.messages_html;
                }
            }

            setLiveRefreshState(window.Echo ? 'Terhubung real-time' : 'Pembaruan otomatis aktif');
        } catch {
            failedChecks += 1;
            setLiveRefreshState('Mencoba menghubungkan kembali…', 'offline');
        } finally {
            isChecking = false;
            scheduleRefreshCheck(Math.min(60000, 30000 * (2 ** failedChecks)));
        }
    };

    liveRefresh.addEventListener('click', () => {
        window.clearTimeout(refreshTimer);
        checkForUpdates(true);
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

    if (window.Echo && channelName) {
        window.Echo.channel(channelName)
            .listen('.report.updated', () => checkForUpdates(true));

        const connection = window.Echo.connector?.pusher?.connection;
        connection?.bind('connected', () => setLiveRefreshState('Terhubung real-time'));
        connection?.bind('unavailable', () => setLiveRefreshState('Mencoba menghubungkan kembali…', 'offline'));
        connection?.bind('failed', () => setLiveRefreshState('Pembaruan otomatis aktif', 'available'));
    }

    scheduleRefreshCheck();
}
