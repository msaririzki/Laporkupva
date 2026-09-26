<x-layouts.public title="Progres laporan">
    @php
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
                    <strong data-report-status-label class="text-xs sm:text-sm font-semibold text-[#F2B84B]">{{ $report->status->label() }}</strong>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="py-6 sm:py-8 lg:py-10 bg-[#F4F7FB]">
        <div class="public-container grid max-w-[1240px] gap-6 lg:grid-cols-[1fr_340px]">
            <div class="space-y-5">
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
                            data-channel="{{ $report->realtimeChannelName() }}"
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

                    <div data-report-timeline class="mt-5">
                        @include('reports.partials.status-timeline', ['report' => $report])
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
                    <div data-report-conversation class="mt-4 space-y-3" aria-live="polite">
                        @include('reports.partials.conversation-messages', ['report' => $report])
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
                    <strong class="block font-semibold">Simpan nomor laporan</strong>
                    <span class="mt-0.5 block text-[11px] text-[#64748B]">Siapa pun yang mengetahui nomor laporan dapat melihat perkembangan dan percakapan ini. Bagikan hanya kepada pihak yang dipercaya.</span>
                </div>

                <a href="{{ route('reports.track') }}" class="button-secondary w-full text-xs font-medium py-2">
                    <span>Cek laporan lain</span>
                </a>
            </aside>
        </div>
    </section>
</x-layouts.public>
