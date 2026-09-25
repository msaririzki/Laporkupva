<aside class="admin-login-intro" aria-label="Tentang portal admin TAMBORA">
    <div class="admin-login-brand">
        <div class="admin-login-logo-shell">
            <img
                src="{{ asset('images/brand/tambora.webp') }}"
                alt="TAMBORA"
                class="admin-login-logo"
                width="720"
                height="316"
            >
        </div>

        <span class="admin-login-badge">Admin</span>
    </div>

    <div class="admin-login-menu" aria-label="Ruang kerja TAMBORA">
        <p class="admin-login-menu-label">Ruang kerja</p>

        <div class="admin-login-menu-item is-active">
            <x-filament::icon icon="heroicon-m-squares-2x2" class="size-5" />
            <span>Portal admin</span>
        </div>

        <div class="admin-login-menu-item">
            <x-filament::icon icon="heroicon-m-document-text" class="size-5" />
            <span>Kelola laporan</span>
        </div>

        <div class="admin-login-menu-item">
            <x-filament::icon icon="heroicon-m-building-office-2" class="size-5" />
            <span>Data KUPVA</span>
        </div>
    </div>

    <div class="admin-login-security">
        <span class="admin-login-security-icon">
            <x-filament::icon icon="heroicon-m-shield-check" class="size-5" />
        </span>

        <span class="admin-login-security-copy">
            <strong>Akses internal</strong>
            <small>Bank Indonesia · NTB</small>
        </span>
    </div>
</aside>
