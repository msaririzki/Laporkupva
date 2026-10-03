<x-layouts.public title="Laporan berhasil dikirim">
    <section class="bg-[#F4F7FB] py-5 sm:py-8 lg:py-10">
        <div class="public-container max-w-4xl">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_8px_30px_rgba(15,23,42,0.05)]">
                <header class="flex items-start gap-3 border-b border-slate-100 px-4 py-4 sm:items-center sm:gap-4 sm:px-6 sm:py-5">
                    <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-emerald-50 text-emerald-600 sm:size-12">
                        <svg class="size-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <h1 class="text-lg font-bold tracking-tight text-[#0B2342] sm:text-2xl">Laporan berhasil dikirim</h1>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500 sm:text-sm">Terima kasih. Laporan Anda telah tersimpan.</p>
                    </div>
                </header>

                <div
                    class="grid gap-4 p-4 sm:p-6 md:grid-cols-[minmax(0,1fr)_240px] md:gap-x-8"
                    data-access-card
                    data-code="{{ $submittedReport['code'] }}"
                    data-submitted-at="{{ \Illuminate\Support\Carbon::parse($submittedReport['submitted_at'])->translatedFormat('d F Y, H:i') }} WITA"
                    data-logo-url="{{ asset('images/brand/tambora.webp') }}"
                >
                    <div class="min-w-0">
                        <h2 class="text-sm font-semibold text-[#0B2342]">Simpan nomor laporan Anda</h2>
                        <div class="mt-3 rounded-xl border border-blue-100 bg-blue-50/70 px-3 py-3 sm:px-4 sm:py-4">
                            <strong id="report-code" class="block text-center font-mono text-lg font-bold tracking-wide text-[#0B2342] sm:text-2xl">{{ $submittedReport['code'] }}</strong>
                        </div>
                        <p class="mt-2 text-center text-[11px] text-slate-500 sm:text-xs md:text-left">
                            Dikirim {{ \Illuminate\Support\Carbon::parse($submittedReport['submitted_at'])->translatedFormat('d F Y, H:i') }} WITA
                        </p>
                        <p class="mt-4 text-xs leading-relaxed text-slate-600 sm:text-sm">
                            Simpan nomor atau unduh gambar QR. Jangan bagikan akses ini kepada orang lain.
                        </p>
                    </div>

                    <div class="flex flex-col items-center gap-2 rounded-xl bg-slate-50 p-3 text-center sm:p-4 md:col-start-2 md:row-start-1 md:row-span-2">
                        <img id="tracking-qr" class="size-40 shrink-0 rounded-lg bg-white sm:size-44 md:size-48" src="{{ $trackingQrCode }}" alt="QR akses rahasia laporan {{ $submittedReport['code'] }}" width="192" height="192">
                        <div>
                            <h2 class="text-xs font-semibold text-[#0B2342]">Buka status lewat QR</h2>
                            <p class="mt-1 max-w-56 text-[11px] leading-relaxed text-slate-500">Pindai dengan kamera atau unggah QR di halaman cek status.</p>
                        </div>
                    </div>

                    <div class="min-w-0 md:col-start-1 md:row-start-2">
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" class="button-secondary min-w-0 px-2 text-xs sm:text-sm" data-copy-access>
                                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h10v12H9V9Zm6-3V3H3v12h3"/>
                                </svg>
                                <span>Salin nomor</span>
                            </button>
                            <button type="button" class="button-secondary min-w-0 px-2 text-xs sm:text-sm" data-download-access>
                                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-4.5-6L12 15m0 0-4.5-4.5M12 15V3"/>
                                </svg>
                                <span data-download-label>Unduh gambar akses</span>
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-slate-500 empty:hidden" aria-live="polite" data-access-download-status></p>
                        <a href="{{ $trackingUrl }}" class="button-primary mt-3 w-full text-sm font-semibold">
                            <span>Cek status laporan</span>
                            <svg class="size-4 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m7.5 4.5 5.5 5.5-5.5 5.5"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
