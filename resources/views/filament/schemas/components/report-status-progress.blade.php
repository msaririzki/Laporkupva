@php
    use App\Enums\ReportStatus;

    $statuses = ReportStatus::cases();
    $currentPosition = array_search($record->status, $statuses, true);
    $currentPosition = $currentPosition === false ? 0 : $currentPosition;
@endphp

<section class="@container/report-progress overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900 sm:rounded-2xl">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-4 py-3.5 dark:border-gray-800 sm:px-6 sm:py-4">
        <div class="flex flex-wrap items-center gap-2.5">
            <h2 class="text-base font-bold text-gray-950 dark:text-white">Status penanganan</h2>
            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 ring-1 ring-blue-100 dark:bg-blue-950/60 dark:text-blue-200 dark:ring-blue-900">
                Tahap {{ $currentPosition + 1 }} dari {{ count($statuses) }}
            </span>
        </div>

        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs font-medium text-gray-500 dark:text-gray-400" aria-label="Keterangan warna progres">
            <span class="flex items-center gap-1.5">
                <span aria-hidden="true" class="size-2.5 rounded-full bg-emerald-500"></span>
                Sudah dikerjakan
            </span>
            <span class="flex items-center gap-1.5">
                <span class="relative flex size-2.5" aria-hidden="true">
                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-blue-400 opacity-60 motion-reduce:hidden"></span>
                    <span class="relative inline-flex size-2.5 rounded-full bg-blue-600"></span>
                </span>
                Sedang dikerjakan
            </span>
            <span class="flex items-center gap-1.5">
                <span aria-hidden="true" class="size-2.5 rounded-full border-2 border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-900"></span>
                Belum dikerjakan
            </span>
        </div>
    </div>

    <div class="px-4 py-4 sm:px-6 sm:py-5">
        <ol class="grid gap-0 @2xl/report-progress:grid-cols-6" aria-label="Tahapan penanganan laporan">
            @foreach ($statuses as $position => $status)
                @php
                    $isCompleted = $position < $currentPosition;
                    $isCurrent = $position === $currentPosition;
                    $connectorState = $isCompleted ? 'completed' : ($isCurrent ? 'current' : 'pending');
                @endphp

                <li
                    data-progress-state="{{ $isCompleted ? 'completed' : ($isCurrent ? 'current' : 'pending') }}"
                    @if ($isCurrent) aria-current="step" @endif
                    @class([
                        'relative flex min-w-0 items-center gap-3 pb-3 last:pb-0 sm:pb-4',
                        '@2xl/report-progress:flex-col @2xl/report-progress:gap-2 @2xl/report-progress:px-2 @2xl/report-progress:pb-0 @2xl/report-progress:text-center',
                    ])
                >
                    @if (! $loop->last)
                        <span
                            aria-hidden="true"
                            class="report-progress-connector report-progress-connector--{{ $connectorState }} absolute left-[1.0625rem] top-9 h-[calc(100%-0.5rem)] w-0.5 overflow-hidden rounded-full @2xl/report-progress:left-1/2 @2xl/report-progress:top-[1.0625rem] @2xl/report-progress:h-0.5 @2xl/report-progress:w-full"
                            style="animation-delay: -{{ $position * 220 }}ms"
                        ></span>
                    @endif

                    <span class="relative z-10 grid size-9 shrink-0 place-items-center">
                        @if ($isCurrent)
                            <span aria-hidden="true" class="absolute inset-0 animate-ping rounded-full bg-blue-400/35 motion-reduce:hidden"></span>
                        @endif

                        <span
                            @class([
                                'relative grid size-8 place-items-center rounded-full border-2 border-white text-xs font-extrabold shadow-sm dark:border-gray-900',
                                'bg-emerald-600 text-white' => $isCompleted,
                                'bg-blue-700 text-white ring-4 ring-blue-100 dark:ring-blue-900/50' => $isCurrent,
                                'bg-white text-gray-500 ring-1 ring-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-600' => ! $isCompleted && ! $isCurrent,
                            ])
                        >
                            @if ($isCompleted)
                                <x-filament::icon icon="heroicon-m-check" class="size-4" />
                            @else
                                {{ $position + 1 }}
                            @endif
                        </span>
                    </span>

                    <p
                        @class([
                            'relative z-10 min-w-0 flex-1 text-sm font-semibold leading-5',
                            '@2xl/report-progress:min-h-10 @2xl/report-progress:w-full @2xl/report-progress:flex-none @2xl/report-progress:text-xs @4xl/report-progress:text-sm',
                            'text-emerald-700 dark:text-emerald-300' => $isCompleted,
                            'font-bold text-blue-800 dark:text-blue-200' => $isCurrent,
                            'text-gray-500 dark:text-gray-400' => ! $isCompleted && ! $isCurrent,
                        ])
                    >
                        {{ $status->label() }}
                    </p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
