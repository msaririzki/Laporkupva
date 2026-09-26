@php
    $statuses = \App\Enums\ReportStatus::cases();
    $currentIndex = array_search($report->status, $statuses, true);
@endphp

@foreach ($statuses as $index => $status)
    @php
        $history = $report->statusHistories->where('to_status', $status)->last();
        $isDone = $index < $currentIndex;
        $isCurrent = $index === $currentIndex;
    @endphp
    <div class="status-item {{ $isDone ? 'is-done' : '' }} {{ $isCurrent ? 'is-current' : '' }}">
        <div class="status-marker">
            @if ($isDone)
                <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/>
                </svg>
            @else
                <span>{{ $index + 1 }}</span>
            @endif
        </div>
        <div class="status-content">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="text-xs font-semibold text-[#0B2342] sm:text-sm">{{ $status->label() }}</h3>
                @if ($history)
                    <time class="text-[11px] text-[#64748B]">{{ $history->created_at->translatedFormat('d M Y, H:i') }}</time>
                @endif
            </div>
            <p class="mt-0.5 text-xs leading-relaxed text-[#64748B]">{{ $history?->public_note ?: $status->description() }}</p>
            @if ($isCurrent)
                <span class="mt-2 inline-flex items-center gap-1.5 rounded-full border border-amber-300/80 bg-[#FFF4D6] px-2.5 py-0.5 text-[11px] font-semibold text-[#B45309]">
                    <span class="size-1.5 rounded-full bg-[#B45309]"></span>
                    Tahap sekarang
                </span>
            @endif
        </div>
    </div>
@endforeach
