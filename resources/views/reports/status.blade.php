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

    <!-- Header Banner (Compact, Dignified) -->
    <section class="bg-[#0B2342] py-5 sm:py-6 text-white">
        <div class="public-container max-w-4xl">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div>
                    <div class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#F2B84B]">
                        <span class="size-1.5 rounded-full bg-[#F2B84B]"></span>
                        <span>Progres Laporan Pengaduan</span>
                    </div>
                    <h1 class="mt-1 font-mono text-xl sm:text-2xl font-bold tracking-wide text-white">{{ $report->public_code }}</h1>
                </div>
                <div class="inline-flex items-center gap-2 self-start rounded-lg border border-white/15 bg-white/10 px-3.5 py-1.5 backdrop-blur-sm sm:self-auto">
                    <span class="text-xs text-slate-300">Status:</span>
                    <strong class="text-xs sm:text-sm font-semibold text-[#F2B84B]">{{ $report->status->label() }}</strong>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="py-6 sm:py-8 lg:py-10 bg-[#F4F7FB]">
        <div class="public-container grid max-w-[1240px] gap-6 lg:grid-cols-[1fr_340px]">
            <div class="space-y-5">
                @php
                    $macroStep = match (true) {
                        $report->status === \App\Enums\ReportStatus::Completed => 3,
                        $currentIndex >= 1 => 2,
                        default => 1,
                    };
                @endphp

                <!-- Simplified Status Overview Card -->
                <div class="rounded-xl sm:rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#2563EB]">Ringkasan Status</p>
                    <div class="mt-3 grid gap-3 sm:grid-cols-3">
                        <!-- Step 1: Laporan diterima -->
                        <div class="rounded-lg border p-3 transition-all {{ $macroStep >= 1 ? ($macroStep === 1 ? 'border-blue-200 bg-[#EAF2FF]/60' : 'border-slate-200 bg-slate-50/70') : 'border-slate-200 bg-slate-50/40 opacity-60' }}">
                            <div class="flex items-center gap-2">
                                <span class="grid size-5 place-items-center rounded-full text-[11px] font-bold {{ $macroStep > 1 ? 'bg-[#168A7A] text-white' : ($macroStep === 1 ? 'bg-[#2563EB] text-white' : 'bg-slate-200 text-slate-600') }}">
                                    @if ($macroStep > 1) ✓ @else 1 @endif
                                </span>
                                <strong class="text-xs font-semibold text-[#0B2342]">Laporan diterima</strong>
                            </div>
                            <p class="mt-1 text-[11px] leading-relaxed text-[#64748B]">Laporan telah diterima petugas.</p>
                        </div>

                        <!-- Step 2: Sedang diproses -->
                        <div class="rounded-lg border p-3 transition-all {{ $macroStep >= 2 ? ($macroStep === 2 ? 'border-amber-200 bg-[#FFF4D6]/60' : 'border-slate-200 bg-slate-50/70') : 'border-slate-200 bg-slate-50/40 opacity-60' }}">
                            <div class="flex items-center gap-2">
                                <span class="grid size-5 place-items-center rounded-full text-[11px] font-bold {{ $macroStep > 2 ? 'bg-[#168A7A] text-white' : ($macroStep === 2 ? 'bg-[#F2B84B] text-[#0B2342]' : 'bg-slate-200 text-slate-600') }}">
                                    @if ($macroStep > 2) ✓ @else 2 @endif
                                </span>
                                <strong class="text-xs font-semibold text-[#0B2342]">Sedang diproses</strong>
                            </div>
                            <p class="mt-1 text-[11px] leading-relaxed text-[#64748B]">Laporan dalam penanganan.</p>
                        </div>

                        <!-- Step 3: Selesai -->
                        <div class="rounded-lg border p-3 transition-all {{ $macroStep >= 3 ? 'border-teal-200 bg-[#E8F6F3]/60' : 'border-slate-200 bg-slate-50/40 opacity-60' }}">
                            <div class="flex items-center gap-2">
                                <span class="grid size-5 place-items-center rounded-full text-[11px] font-bold {{ $macroStep >= 3 ? 'bg-[#168A7A] text-white' : 'bg-slate-200 text-slate-600' }}">
                                    @if ($macroStep >= 3) ✓ @else 3 @endif
                                </span>
                                <strong class="text-xs font-semibold text-[#0B2342]">Selesai</strong>
                            </div>
                            <p class="mt-1 text-[11px] leading-relaxed text-[#64748B]">Proses telah selesai.</p>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline Card -->
                <div class="rounded-xl sm:rounded-2xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-xs">
                    <div class="flex flex-col gap-3 border-b border-slate-100 pb-3.5 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-[#0B2342]">Tahapan penanganan</h2>
                            <p class="mt-0.5 text-xs text-[#64748B]">Status terbaru muncul otomatis tanpa perlu memuat ulang halaman.</p>
                        </div>
                        <button
                            type="button"
                            data-report-live-refresh
                            data-update-url="{{ route('reports.status.updates', ['report' => $report->public_code]) }}"
                            data-version="{{ $statusVersion }}"
                            class="inline-flex min-h-8 shrink-0 items-center gap-2 self-start rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-semibold text-emerald-700 transition-colors hover:border-emerald-300 hover:bg-emerald-100"
                            title="Periksa pembaruan sekarang"
                        >
                            <span data-live-refresh-dot class="relative flex size-2" aria-hidden="true">
                                <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                                <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                            </span>
                            <span data-live-refresh-label>Pembaruan otomatis aktif</span>
                        </button>
                    </div>

                    <div class="mt-5">
                        @foreach ($statuses as $index => $status)
                            @php
                                $history = $report->statusHistories->where('to_status', $status)->last();
                                $isDone = $index < $currentIndex;
                                $isCurrent = $index === $currentIndex;
                            @endphp
                            <div class="status-item {{ $isDone ? 'is-done' : '' }} {{ $isCurrent ? 'is-current' : '' }}">
                                <div class="status-marker">
                                    @if ($isDone)
                                        <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <span>{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div class="status-content">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h3 class="text-xs sm:text-sm font-semibold text-[#0B2342]">{{ $status->label() }}</h3>
                                        @if ($history)
                                            <time class="text-[11px] text-[#64748B]">{{ $history->created_at->translatedFormat('d M Y, H:i') }}</time>
                                        @endif
                                    </div>
                                    <p class="mt-0.5 text-xs text-[#64748B] leading-relaxed">{{ $history?->public_note ?: $status->description() }}</p>
                                    @if ($isCurrent)
                                        <span class="mt-2 inline-flex items-center gap-1.5 rounded-full border border-amber-300/80 bg-[#FFF4D6] px-2.5 py-0.5 text-[11px] font-semibold text-[#B45309]">
                                            <span class="size-1.5 rounded-full bg-[#B45309]"></span>
                                            Tahap sekarang
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Anonymous Communication Card -->
                <div class="rounded-xl sm:rounded-2xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-xs">
                    <div class="border-b border-slate-100 pb-3.5">
                        <h2 class="text-base sm:text-lg font-semibold text-[#0B2342]">Komunikasi dengan petugas</h2>
                        <p class="mt-0.5 text-xs text-[#64748B]">Sampaikan informasi tambahan tanpa membuka identitas pribadi Anda.</p>
                    </div>

                    @if (session('message_sent'))
                        <div class="mt-3.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3.5 py-2.5 text-xs sm:text-sm font-semibold text-[#2E9B68]">
                            {{ session('message_sent') }}
                        </div>
                    @endif

                    <!-- Message history thread -->
                    <div class="mt-4 space-y-3">
                        @forelse ($report->anonymousMessages as $message)
                            <article class="flex {{ $message->sender_type === 'reporter' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[85%] rounded-xl px-3.5 py-2.5 {{ $message->sender_type === 'reporter' ? 'rounded-br-sm bg-[#2563EB] text-white' : 'rounded-bl-sm bg-slate-100 text-[#0B2342]' }}">
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[10px] font-semibold uppercase tracking-wider {{ $message->sender_type === 'reporter' ? 'text-blue-100' : 'text-[#64748B]' }}">
                                        <span>{{ $message->sender_type === 'reporter' ? 'Anda (Pelapor)' : 'Petugas TAMBORA' }}</span>
                                        <span>·</span>
                                        <time>{{ $message->created_at->translatedFormat('d M Y, H:i') }}</time>
                                    </div>
                                    <p class="mt-1 whitespace-pre-line text-xs sm:text-sm leading-relaxed">{{ $message->body }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50/50 px-4 py-5 text-center text-xs text-[#64748B] leading-relaxed">
                                Belum ada percakapan. Jika ada informasi atau klarifikasi baru yang ingin disampaikan, kirimkan melalui formulir di bawah ini.
                            </div>
                        @endforelse
                    </div>

                    <!-- Reply Form -->
                    <form method="POST" action="{{ route('reports.messages.store', ['report' => $report->public_code]) }}" class="mt-4 border-t border-slate-100 pt-4">
                        @csrf
                        <label for="body" class="form-label text-xs sm:text-sm font-semibold">Pesan tambahan <span>*</span></label>
                        <textarea
                            id="body"
                            name="body"
                            rows="3"
                            maxlength="2000"
                            required
                            class="form-control text-xs sm:text-sm {{ $errors->has('body') ? 'is-invalid' : '' }}"
                            placeholder="Tuliskan informasi tambahan atau klarifikasi untuk petugas..."
                        >{{ old('body') }}</textarea>
                        @error('body')<p class="form-error">{{ $message }}</p>@enderror

                        <div class="mt-3 flex flex-col gap-2.5 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-[11px] leading-relaxed text-[#64748B]">Jangan menuliskan nama, NIK, nomor telepon, atau data sensitif pelapor.</p>
                            <button type="submit" class="button-primary shrink-0 text-xs sm:text-sm font-semibold py-2">
                                <span>Kirim pesan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar Info -->
            <aside class="space-y-4">
                <!-- Summary Card -->
                <div class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-xs">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-[#0B2342]">Ringkasan laporan</h2>
                    <dl class="mt-3 grid gap-3 text-xs sm:text-sm">
                        <div>
                            <dt class="text-[11px] text-[#64748B]">Jenis laporan</dt>
                            <dd class="mt-0.5 font-medium text-[#0B2342]">{{ $incidentTypes[$report->incident_type] ?? 'Laporan masyarakat' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] text-[#64748B]">Tanggal kejadian</dt>
                            <dd class="mt-0.5 font-medium text-[#0B2342]">{{ $report->incident_date->translatedFormat('d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] text-[#64748B]">Wilayah</dt>
                            <dd class="mt-0.5 font-medium text-[#0B2342]">{{ $report->regency }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] text-[#64748B]">Waktu dikirim</dt>
                            <dd class="mt-0.5 font-medium text-[#0B2342]">{{ $report->created_at->translatedFormat('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Security Box -->
                <div class="rounded-xl bg-blue-50/60 border border-blue-100 p-3.5 text-xs leading-relaxed text-[#0B2342]">
                    <strong class="block font-semibold">Jaga kerahasiaan PIN</strong>
                    <span class="mt-0.5 block text-[11px] text-[#64748B]">Hanya pihak yang memegang kode dan PIN yang dapat mengakses linimasa ini. Jangan membagikan akses kepada siapapun.</span>
                </div>

                <a href="{{ route('reports.track') }}" class="button-secondary w-full text-xs font-medium py-2">
                    <span>Cek laporan lain</span>
                </a>
            </aside>
        </div>
    </section>
</x-layouts.public>
