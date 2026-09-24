<x-layouts.public title="Progres laporan">
    @php
        $statuses = \App\Enums\ReportStatus::cases();
        $currentIndex = array_search($report->status, $statuses, true);
        $incidentTypes = [
            'kupva_tanpa_izin' => 'Dugaan KUPVA tanpa izin',
            'transaksi_mencurigakan' => 'Transaksi penukaran mencurigakan',
            'pelanggaran_kurs' => 'Informasi kurs tidak wajar/tidak transparan',
            'penolakan_rupiah' => 'Penolakan penggunaan Rupiah',
            'lainnya' => 'Lainnya terkait penukaran valuta asing',
        ];
    @endphp
    <section class="bg-navy-950 py-10 text-white sm:py-14">
        <div class="public-container max-w-4xl">
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div><p class="text-xs font-bold uppercase tracking-[.18em] text-blue-200">Progres laporan</p><h1 class="mt-2 font-mono text-2xl font-extrabold tracking-wide sm:text-3xl">{{ $report->public_code }}</h1></div>
                <div class="rounded-xl bg-white/10 px-4 py-3"><span class="block text-xs text-blue-200">Status saat ini</span><strong class="mt-1 block text-sm">{{ $report->status->label() }}</strong></div>
            </div>
        </div>
    </section>

    <section class="py-10 sm:py-14">
        <div class="public-container grid max-w-4xl gap-6 lg:grid-cols-[1fr_280px]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-8">
                <h2 class="text-xl font-extrabold text-navy-950">Tahapan penanganan</h2>
                <p class="mt-2 text-sm text-slate-500">Perkembangan terbaru akan tampil pada linimasa ini.</p>
                <div class="mt-8">
                    @foreach ($statuses as $index => $status)
                        @php
                            $history = $report->statusHistories->firstWhere('to_status', $status);
                            $isDone = $index <= $currentIndex;
                            $isCurrent = $index === $currentIndex;
                        @endphp
                        <div class="status-item {{ $isDone ? 'is-done' : '' }} {{ $isCurrent ? 'is-current' : '' }}">
                            <div class="status-marker">@if ($isDone)<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/></svg>@else<span>{{ $index + 1 }}</span>@endif</div>
                            <div class="status-content">
                                <div class="flex flex-wrap items-center justify-between gap-2"><h3>{{ $status->label() }}</h3>@if ($history)<time>{{ $history->created_at->translatedFormat('d M Y, H:i') }}</time>@endif</div>
                                <p>{{ $history?->public_note ?: $status->description() }}</p>
                                @if ($isCurrent)<span class="current-badge">Tahap sekarang</span>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="space-y-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <h2 class="text-sm font-extrabold text-navy-950">Ringkasan laporan</h2>
                    <dl class="mt-4 grid gap-4 text-sm">
                        <div><dt class="text-xs text-slate-400">Jenis laporan</dt><dd class="mt-1 font-semibold text-slate-700">{{ $incidentTypes[$report->incident_type] ?? 'Laporan masyarakat' }}</dd></div>
                        <div><dt class="text-xs text-slate-400">Tanggal kejadian</dt><dd class="mt-1 font-semibold text-slate-700">{{ $report->incident_date->translatedFormat('d F Y') }}</dd></div>
                        <div><dt class="text-xs text-slate-400">Wilayah</dt><dd class="mt-1 font-semibold text-slate-700">{{ $report->regency }}</dd></div>
                        <div><dt class="text-xs text-slate-400">Dikirim</dt><dd class="mt-1 font-semibold text-slate-700">{{ $report->created_at->translatedFormat('d M Y, H:i') }}</dd></div>
                    </dl>
                </div>
                <div class="rounded-2xl bg-blue-50 p-5 text-sm leading-6 text-blue-900"><strong class="block">Jaga kode dan PIN</strong><span class="mt-1 block text-blue-800">Jangan membagikan akses pelacakan kepada pihak lain.</span></div>
                <a href="{{ route('reports.track') }}" class="button-secondary w-full">Cek laporan lain</a>
            </aside>
        </div>
    </section>
</x-layouts.public>
