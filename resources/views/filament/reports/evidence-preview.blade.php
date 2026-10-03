<div class="flex flex-col gap-4">
    <div class="relative grid min-h-[52vh] max-h-[78vh] place-items-center overflow-auto rounded-2xl border border-slate-200 bg-slate-50 p-3 shadow-inner sm:min-h-[64vh] sm:p-5">
        <img
            src="{{ route('admin.report-evidence.preview', $reportEvidence) }}"
            alt="Pratinjau {{ $reportEvidence->original_name }}"
            class="block max-h-[70vh] w-auto max-w-full rounded-xl border border-white bg-white object-contain shadow-lg"
            loading="eager"
            decoding="async"
        >

        <span class="pointer-events-none absolute bottom-4 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full border border-slate-200 bg-white/90 px-3 py-1.5 text-[11px] font-medium text-slate-600 shadow-sm backdrop-blur-sm">
            Foto ditampilkan utuh sesuai orientasi aslinya
        </span>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-gray-950 dark:text-white">{{ $reportEvidence->original_name }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ number_format($reportEvidence->size / 1024, 1, ',', '.') }} KB · {{ $reportEvidence->mime_type }}
            </p>
        </div>

        <div x-data="{ downloading: false }">
            <x-filament::button
                tag="a"
                :href="route('admin.report-evidence.download', $reportEvidence)"
                :spa-mode="false"
                color="gray"
                class="min-w-40 justify-center"
                download
                x-bind:aria-disabled="downloading.toString()"
                x-bind:class="{ 'pointer-events-none opacity-70': downloading }"
                x-on:click="
                    if (downloading) {
                        $event.preventDefault()
                        return
                    }

                    downloading = true
                    window.setTimeout(() => downloading = false, 4000)
                "
            >
                <span x-show="! downloading" class="inline-flex items-center gap-2">
                    <x-filament::icon icon="heroicon-m-arrow-down-tray" class="size-4" />
                    <span>Unduh gambar</span>
                </span>

                <span x-cloak x-show="downloading" class="inline-flex items-center gap-2">
                    <svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-90" fill="currentColor" d="M21 12a9 9 0 0 0-9-9v3a6 6 0 0 1 6 6h3Z"></path>
                    </svg>
                    <span>Mengunduh…</span>
                </span>
            </x-filament::button>
        </div>
    </div>
</div>
