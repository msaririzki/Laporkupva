import './realtime';
import './map-layers';
import { initCustomSelect } from './report-form';
import './report-access';

document.querySelectorAll('[data-custom-select]').forEach(initCustomSelect);

document.querySelectorAll('[data-auto-submit]').forEach((field) => {
    field.addEventListener('change', () => {
        field.form?.requestSubmit();
    });
});

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
    const messageForm = document.querySelector('[data-report-message-form]');
    const messageBody = messageForm?.querySelector('[name="body"]');
    const messageSubmit = messageForm?.querySelector('[data-report-message-submit]');
    const messageSubmitLabel = messageForm?.querySelector('[data-report-message-submit-label]');
    const messageSpinner = messageForm?.querySelector('[data-report-message-spinner]');
    const messageFeedback = document.querySelector('[data-report-message-feedback]');
    const messageError = messageForm?.querySelector('[data-report-message-error]');
    let currentVersion = liveRefresh.dataset.version;
    let refreshTimer;
    let failedChecks = 0;
    let isChecking = false;
    let checkAgain = false;
    let shouldFollowLatestMessage = false;

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
            checkAgain ||= fromRealtime;

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
                    statusLabel.classList.toggle('text-emerald-300', update.status === 'completed');
                    statusLabel.classList.toggle('text-[#F2B84B]', update.status !== 'completed');
                }

                if (timeline) {
                    timeline.innerHTML = update.timeline_html;
                }

                if (conversation) {
                    const wasNearLatest = (conversation.scrollHeight - conversation.scrollTop - conversation.clientHeight) < 80;
                    const previousScrollTop = conversation.scrollTop;
                    conversation.innerHTML = update.messages_html;
                    conversation.lastElementChild?.classList.add('report-message-arrived');

                    if (wasNearLatest || shouldFollowLatestMessage) {
                        conversation.scrollTop = conversation.scrollHeight;
                    } else {
                        conversation.scrollTop = previousScrollTop;
                    }

                    shouldFollowLatestMessage = false;
                }
            }

            setLiveRefreshState(window.Echo ? 'Terhubung real-time' : 'Pembaruan otomatis aktif');
        } catch {
            failedChecks += 1;
            setLiveRefreshState('Mencoba menghubungkan kembali…', 'offline');
        } finally {
            isChecking = false;

            if (checkAgain) {
                checkAgain = false;
                window.clearTimeout(refreshTimer);
                void checkForUpdates(true);

                return;
            }

            scheduleRefreshCheck(Math.min(60000, 30000 * (2 ** failedChecks)));
        }
    };

    const showMessageFeedback = (message) => {
        if (!messageFeedback) {
            return;
        }

        messageFeedback.textContent = message;
        messageFeedback.classList.remove('invisible');
    };

    const showMessageError = (message) => {
        if (!messageError) {
            return;
        }

        messageError.textContent = message;
        messageError.classList.remove('invisible');
        messageBody?.classList.add('is-invalid');
    };

    const clearMessageState = () => {
        messageFeedback?.classList.add('invisible');
        messageError?.classList.add('invisible');
        messageBody?.classList.remove('is-invalid');
    };

    messageForm?.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!messageBody || !messageSubmit || messageSubmit.disabled) {
            return;
        }

        clearMessageState();
        shouldFollowLatestMessage = true;
        messageSubmit.disabled = true;
        messageSubmitLabel.textContent = 'Mengirim…';
        messageSpinner?.classList.remove('hidden');

        try {
            const response = await fetch(messageForm.action, {
                method: 'POST',
                body: new FormData(messageForm),
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const result = await response.json().catch(() => ({}));

            if (response.status === 422) {
                shouldFollowLatestMessage = false;
                showMessageError(result.errors?.body?.[0] ?? 'Periksa kembali pesan Anda.');

                return;
            }

            if (response.status === 404 || response.status === 419) {
                shouldFollowLatestMessage = false;
                showMessageError('Akses laporan telah berakhir. Masukkan kembali nomor laporan untuk melanjutkan.');

                return;
            }

            if (!response.ok) {
                throw new Error('Message submission failed.');
            }

            messageBody.value = '';
            showMessageFeedback(result.message ?? 'Pesan berhasil dikirim kepada petugas.');
            await checkForUpdates(true);
            messageBody.focus({ preventScroll: true });
        } catch {
            shouldFollowLatestMessage = false;
            showMessageError('Pesan belum terkirim. Periksa koneksi internet, lalu coba lagi.');
        } finally {
            messageSubmit.disabled = false;
            messageSubmitLabel.textContent = 'Kirim pesan';
            messageSpinner?.classList.add('hidden');
        }
    });

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

    if (conversation) {
        conversation.scrollTop = conversation.scrollHeight;
    }
}
