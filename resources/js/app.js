import './map-layers';
import './report-form';

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
