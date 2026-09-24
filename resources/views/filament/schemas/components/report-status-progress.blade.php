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
        <ol class="grid gap-3 sm:grid-cols-2 xl:grid-cols-6" aria-label="Tahapan penanganan laporan">
            @foreach ($statuses as $position => $status)
                @php
                    $isCompleted = $position < $currentPosition;
                    $isCurrent = $position === $currentPosition;
                @endphp

                <li
                    @class([
                        'flex min-w-0 items-center gap-3 rounded-xl border px-3 py-3',
                        'border-emerald-200 bg-emerald-50 text-emerald-800' => $isCompleted,
                        'border-blue-300 bg-blue-50 text-blue-900 ring-2 ring-blue-100' => $isCurrent,
                        'border-gray-200 bg-gray-50 text-gray-500' => ! $isCompleted && ! $isCurrent,
                    ])
                >
                    <span
                        @class([
                            'grid size-7 shrink-0 place-items-center rounded-full text-xs font-bold',
                            'bg-emerald-600 text-white' => $isCompleted,
                            'bg-blue-700 text-white' => $isCurrent,
                            'bg-white text-gray-500 ring-1 ring-gray-300' => ! $isCompleted && ! $isCurrent,
                        ])
                    >
                        @if ($isCompleted)
                            <x-filament::icon icon="heroicon-m-check" class="size-4" />
                        @else
                            {{ $position + 1 }}
                        @endif
                    </span>
                    <span class="text-xs font-semibold leading-5">{{ $status->label() }}</span>
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
