<div class="flex flex-col gap-4">
    <div class="relative grid min-h-[52vh] max-h-[78vh] place-items-center overflow-auto rounded-2xl border border-white/10 bg-gray-950 p-2 shadow-inner sm:min-h-[64vh] sm:p-4">
        <img
            src="{{ route('admin.report-evidence.preview', $reportEvidence) }}"
            alt="Pratinjau {{ $reportEvidence->original_name }}"
            class="block max-h-[74vh] w-auto max-w-full rounded-xl object-contain shadow-2xl"
            loading="eager"
            decoding="async"
        >

        <span class="pointer-events-none absolute bottom-4 left-1/2 -translate-x-1/2 rounded-full bg-black/65 px-3 py-1.5 text-[11px] font-medium text-white/90 backdrop-blur-sm">
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

        <x-filament::button
            tag="a"
            :href="route('admin.report-evidence.download', $reportEvidence)"
            color="gray"
            icon="heroicon-m-arrow-down-tray"
        >
            Unduh gambar
        </x-filament::button>
    </div>
</div>
