<x-filament-widgets::widget class="h-full" wire:poll.30s>
    <x-filament::section class="h-full">
        <x-slot name="heading">Status laporan</x-slot>

        <div class="flex h-full flex-col gap-6">
            <div class="flex justify-center">
                <div
                    class="relative grid size-40 place-items-center rounded-full shadow-[0_18px_45px_-24px_rgba(15,23,42,0.45)] ring-1 ring-gray-200 dark:ring-gray-700"
                    style="background: {{ $chartBackground }}"
                    role="img"
                    aria-label="Distribusi {{ number_format($total, 0, ',', '.') }} laporan berdasarkan status"
                >
                    <div class="grid size-28 place-items-center rounded-full bg-white text-center shadow-inner dark:bg-gray-900">
                        <div>
                            <p class="text-3xl font-black tracking-tight text-gray-950 dark:text-white">
                                {{ number_format($total, 0, ',', '.') }}
                            </p>
                            <p class="mt-0.5 text-[11px] font-bold uppercase tracking-[0.16em] text-gray-500 dark:text-gray-400">
                                Laporan
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-1">
                @foreach ($statuses as $status)
                    <div data-status-percentage="{{ $status['percentageLabel'] }}">
                        <div class="flex items-start justify-between gap-3 text-xs">
                            <div class="flex min-w-0 items-start gap-2">
                                <span class="mt-1 size-2.5 shrink-0 rounded-full {{ $status['dotClass'] }}"></span>
                                <span class="font-semibold leading-5 text-gray-700 dark:text-gray-200">
                                    {{ $status['label'] }}
                                </span>
                            </div>

                            <div class="shrink-0 text-right">
                                <span class="font-black text-gray-950 dark:text-white">{{ $status['percentageLabel'] }}</span>
                                <span class="ml-1 text-gray-500 dark:text-gray-400">{{ $status['count'] }}</span>
                            </div>
                        </div>

                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                            <div
                                class="h-full rounded-full {{ $status['barClass'] }}"
                                style="width: {{ number_format($status['percentage'], 4, '.', '') }}%"
                                aria-hidden="true"
                            ></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
