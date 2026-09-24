@php
    use App\Enums\ReportStatus;

    $statuses = ReportStatus::cases();
    $currentPosition = array_search($record->status, $statuses, true);
    $currentPosition = $currentPosition === false ? 0 : $currentPosition;
    $nextStatus = $record->status->next();
@endphp

<section class="overflow-hidden rounded-2xl border border-blue-200 bg-white shadow-sm">
    <div class="flex flex-col gap-5 bg-gradient-to-r from-blue-950 via-blue-900 to-blue-700 px-5 py-6 text-white sm:flex-row sm:items-center sm:justify-between sm:px-7">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-200">Progres penanganan</p>
            <div class="mt-2 flex flex-wrap items-center gap-3">
                <h2 class="text-xl font-bold tracking-tight sm:text-2xl">{{ $record->status->label() }}</h2>
                <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold text-blue-50">
                    Tahap {{ $currentPosition + 1 }} dari {{ count($statuses) }}
                </span>
            </div>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-blue-100">{{ $record->status->description() }}</p>
        </div>

        <div class="shrink-0 rounded-xl border border-white/15 bg-white/10 px-4 py-3 sm:max-w-xs">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-blue-200">Langkah berikutnya</p>
            <p class="mt-1 text-sm font-bold">
                {{ $nextStatus?->label() ?? 'Penanganan telah selesai' }}
            </p>
        </div>
    </div>

    <div class="p-5 sm:p-7">
        <ol class="grid gap-0 xl:grid-cols-6" aria-label="Tahapan penanganan laporan">
            @foreach ($statuses as $position => $status)
                @php
                    $isCompleted = $position < $currentPosition;
                    $isCurrent = $position === $currentPosition;
                    $isNext = $position === $currentPosition + 1;
                    $connectorState = $isCompleted ? 'completed' : ($isCurrent ? 'current' : 'pending');
                @endphp

                <li
                    data-progress-state="{{ $isCompleted ? 'completed' : ($isCurrent ? 'current' : 'pending') }}"
                    @if ($isCurrent) aria-current="step" @endif
                    @class([
                        'relative flex min-w-0 items-start gap-4 pb-5 last:pb-0',
                        'xl:flex-col xl:items-center xl:gap-3 xl:px-2 xl:pb-0',
                    ])
                >
                    @if (! $loop->last)
                        <span
                            aria-hidden="true"
                            class="report-progress-connector report-progress-connector--{{ $connectorState }} absolute left-[1.3125rem] top-11 h-[calc(100%-1rem)] w-1 overflow-hidden rounded-full xl:left-1/2 xl:top-[1.3125rem] xl:h-1 xl:w-full"
                            style="animation-delay: -{{ $position * 220 }}ms"
                        ></span>
                    @endif

                    <span class="relative z-10 grid size-11 shrink-0 place-items-center">
                        @if ($isCurrent)
                            <span aria-hidden="true" class="absolute inset-0 rounded-full bg-blue-400/40 motion-safe:animate-ping motion-reduce:hidden"></span>
                        @endif

                        <span
                            @class([
                                'relative grid size-10 place-items-center rounded-full border-4 border-white text-sm font-extrabold shadow-sm dark:border-gray-900',
                                'bg-emerald-600 text-white' => $isCompleted,
                                'bg-blue-700 text-white ring-4 ring-blue-100 dark:ring-blue-900/50' => $isCurrent,
                                'bg-white text-gray-500 ring-1 ring-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-600' => ! $isCompleted && ! $isCurrent,
                            ])
                        >
                            @if ($isCompleted)
                                <x-filament::icon icon="heroicon-m-check" class="size-5" />
                            @else
                                {{ $position + 1 }}
                            @endif
                        </span>
                    </span>

                    <div
                        @class([
                            'relative z-10 min-w-0 flex-1 rounded-xl border px-4 py-3 shadow-sm transition duration-300',
                            'xl:min-h-28 xl:w-full xl:text-center',
                            'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-100' => $isCompleted,
                            'border-blue-300 bg-blue-50 text-blue-950 ring-2 ring-blue-100 dark:border-blue-700 dark:bg-blue-950/50 dark:text-blue-100 dark:ring-blue-900/60' => $isCurrent,
                            'border-gray-200 bg-gray-50 text-gray-600 dark:border-gray-700 dark:bg-gray-800/70 dark:text-gray-300' => ! $isCompleted && ! $isCurrent,
                        ])
                    >
                        <div class="flex items-center justify-between gap-2 xl:flex-col xl:justify-start xl:gap-1">
                            <span class="text-[10px] font-bold uppercase tracking-[0.14em] opacity-70">Tahap {{ $position + 1 }}</span>

                            @if ($isCompleted)
                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700 dark:bg-emerald-900/70 dark:text-emerald-200">Selesai</span>
                            @elseif ($isCurrent)
                                <span class="rounded-full bg-blue-700 px-2 py-0.5 text-[10px] font-bold text-white">Saat ini</span>
                            @elseif ($isNext)
                                <span class="rounded-full bg-white px-2 py-0.5 text-[10px] font-bold text-blue-700 ring-1 ring-blue-200 dark:bg-gray-900 dark:text-blue-300 dark:ring-blue-800">Berikutnya</span>
                            @endif
                        </div>

                        <p class="mt-1.5 text-sm font-bold leading-5 xl:mt-2">{{ $status->label() }}</p>
                    </div>
                </li>
            @endforeach
        </ol>

        <div class="mt-5 flex items-start gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
            <x-filament::icon icon="heroicon-m-information-circle" class="mt-0.5 size-5 shrink-0 text-blue-600" />
            <p class="leading-6">
                @if ($nextStatus)
                    Klik <strong>Update progres</strong> di kanan atas, lalu tulis keterangan singkat yang aman untuk dibaca pelapor.
                @else
                    Laporan ini sudah mencapai tahap selesai. Koreksi hanya tersedia untuk Super Admin melalui menu <strong>Lainnya</strong>.
                @endif
            </p>
        </div>
    </div>
</section>
