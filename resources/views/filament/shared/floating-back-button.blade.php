<nav class="admin-detail-floating-back" aria-label="Navigasi kembali">
    <x-filament::button
        tag="a"
        :href="$url"
        color="gray"
        icon="heroicon-m-arrow-left"
        class="admin-detail-floating-back-button"
        :aria-label="$label"
    >
        <span class="admin-detail-floating-back-text">{{ $label }}</span>
        <span class="admin-detail-floating-back-text-mobile">Kembali</span>
    </x-filament::button>
</nav>
