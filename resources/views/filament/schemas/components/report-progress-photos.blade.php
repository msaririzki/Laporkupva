@if (count($record->activity_photos ?? []))
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach (array_values($record->activity_photos) as $index => $path)
            @php
                $photoUrl = route('admin.report-progress-photos.preview', ['reportProgressRequest' => $record, 'photo' => $index]);
                $photoName = $record->activity_photo_names[$path] ?? 'Dokumentasi '.($index + 1);
            @endphp
            <button type="button" wire:click="mountAction('previewPhoto', { photo: {{ $index }} })" wire:loading.attr="disabled" wire:target="mountAction" aria-label="Pratinjau {{ $photoName }}" class="group/photo w-full cursor-pointer overflow-hidden rounded-xl border border-gray-200 bg-gray-50 text-left focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:opacity-70 dark:border-gray-700 dark:bg-gray-900">
                <img src="{{ $photoUrl }}" alt="{{ $photoName }}" loading="lazy" class="h-64 w-full object-contain transition group-hover/photo:opacity-90">
                <span class="flex items-center justify-between gap-2 border-t border-gray-200 p-3 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-200">
                    <span class="min-w-0 truncate">{{ $photoName }}</span>
                    <x-filament::icon icon="heroicon-m-magnifying-glass-plus" class="size-4 shrink-0" />
                </span>
                <span class="sr-only">Perbesar foto dokumentasi</span>
            </button>
        @endforeach
    </div>
@else
    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada foto dokumentasi yang dilampirkan pada pengajuan ini.</p>
@endif
