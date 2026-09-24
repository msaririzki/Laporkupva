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
            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-8">
                    <h2 class="text-xl font-extrabold text-navy-950">Tahapan penanganan</h2>
                    <p class="mt-2 text-sm text-slate-500">Perkembangan terbaru akan tampil pada linimasa ini.</p>
                    <div class="mt-8">
                        @foreach ($statuses as $index => $status)
                            @php
                                $history = $report->statusHistories->where('to_status', $status)->last();
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

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-8">
                    <div class="flex items-start gap-3">
                        <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-blue-50 text-blue-700">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 4.142-4.03 7.5-9 7.5a10.23 10.23 0 0 1-3.876-.74L3 20.25l1.61-4.293A6.97 6.97 0 0 1 3 12c0-4.142 4.03-7.5 9-7.5s9 3.358 9 7.5Z"/></svg>
                        </span>
                        <div>
                            <h2 class="text-xl font-extrabold text-navy-950">Komunikasi dengan petugas</h2>
                            <p class="mt-1 text-sm leading-6 text-slate-500">Sampaikan informasi tambahan tanpa membuka identitas Anda.</p>
                        </div>
                    </div>

                    @if (session('message_sent'))
                        <div class="mt-5 rounded-xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-semibold text-teal-800">{{ session('message_sent') }}</div>
                    @endif

                    <div class="mt-6 space-y-3">
                        @forelse ($report->anonymousMessages as $message)
                            <article class="flex {{ $message->sender_type === 'reporter' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[88%] rounded-2xl px-4 py-3 {{ $message->sender_type === 'reporter' ? 'rounded-br-md bg-blue-700 text-white' : 'rounded-bl-md bg-slate-100 text-slate-700' }}">
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[10px] font-bold uppercase tracking-wider {{ $message->sender_type === 'reporter' ? 'text-blue-100' : 'text-slate-400' }}">
                                        <span>{{ $message->sender_type === 'reporter' ? 'Anda' : 'Petugas TAMBORA' }}</span>
                                        <time>{{ $message->created_at->translatedFormat('d M Y, H:i') }}</time>
                                    </div>
                                    <p class="mt-2 whitespace-pre-line text-sm leading-6">{{ $message->body }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-6 text-center text-sm leading-6 text-slate-500">Belum ada percakapan. Anda dapat mengirim informasi tambahan kapan saja.</div>
                        @endforelse
                    </div>

                    <form method="POST" action="{{ route('reports.messages.store', ['report' => $report->public_code]) }}" class="mt-6 border-t border-slate-200 pt-6">
                        @csrf
                        <label for="body" class="form-label">Pesan tambahan <span>*</span></label>
                        <textarea id="body" name="body" rows="4" maxlength="2000" required class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}" placeholder="Tulis informasi tambahan atau balasan untuk petugas...">{{ old('body') }}</textarea>
                        @error('body')<p class="form-error">{{ $message }}</p>@enderror
                        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs leading-5 text-slate-500">Jangan menuliskan nama, NIK, nomor telepon, atau identitas pribadi lainnya.</p>
                            <button type="submit" class="button-primary shrink-0">Kirim pesan</button>
                        </div>
                    </form>
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
