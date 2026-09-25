<div class="flex flex-col gap-4">
    <div class="grid min-h-64 place-items-center overflow-hidden rounded-2xl bg-gray-950 p-2 sm:p-4">
        <img
            src="{{ route('admin.report-evidence.preview', $reportEvidence) }}"
            alt="Pratinjau {{ $reportEvidence->original_name }}"
            class="max-h-[72vh] w-auto max-w-full rounded-xl object-contain"
        >
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-gray-950">{{ $reportEvidence->original_name }}</p>
            <p class="text-xs text-gray-500">{{ number_format($reportEvidence->size / 1024, 1, ',', '.') }} KB</p>
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
