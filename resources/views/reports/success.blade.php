<x-layouts.public title="Laporan berhasil dikirim">
    <section class="min-h-[75vh] py-8 sm:py-14 bg-[#F4F7FB]">
        <div class="public-container max-w-lg">
            <div class="overflow-hidden rounded-2xl border border-[#E2E8F0] bg-white shadow-[0_8px_30px_rgba(15,23,42,0.05)]">
                <!-- Success Header -->
                <div class="bg-[#0B2342] px-5 py-7 text-center text-white sm:px-8">
                    <span class="mx-auto grid size-12 place-items-center rounded-xl bg-[#168A7A] text-white shadow-2xs">
                        <svg class="size-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <h1 class="mt-3 text-lg sm:text-xl font-bold text-white">Laporan berhasil dikirim</h1>
                    <p class="mt-1 text-xs text-slate-300">Terima kasih telah berpartisipasi menjaga pengawasan KUPVA di Nusa Tenggara Barat.</p>
                </div>

                <!-- Access Details -->
                <div class="p-5 sm:p-7">
                    <div
                        class="rounded-xl border border-blue-100 bg-[#EAF2FF]/50 p-4 sm:p-5"
                        data-access-card
                        data-code="{{ $submittedReport['code'] }}"
                        data-pin="{{ $submittedReport['pin'] }}"
                        data-submitted-at="{{ \Illuminate\Support\Carbon::parse($submittedReport['submitted_at'])->translatedFormat('d F Y, H:i') }} WITA"
                        data-logo-url="{{ asset('images/brand/tambora.webp') }}"
                    >
                        <p class="text-center text-[11px] font-bold uppercase tracking-wider text-[#2563EB]">Simpan akses rahasia Anda</p>

                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-lg bg-white border border-[#CBD5E1] p-3 text-center">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-[#64748B]">Kode laporan</span>
                                <strong id="report-code" class="mt-1 block font-mono text-base sm:text-lg font-bold tracking-wider text-[#0F172A]">{{ $submittedReport['code'] }}</strong>
                            </div>
                            <div class="rounded-lg bg-white border border-[#CBD5E1] p-3 text-center">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-[#64748B]">PIN pelacakan</span>
                                <strong id="report-pin" class="mt-1 block font-mono text-base sm:text-lg font-bold tracking-wider text-[#0F172A]">{{ $submittedReport['pin'] }}</strong>
                            </div>
                        </div>

                        <p class="mt-3 text-center text-[11px] text-[#64748B]">
                            Waktu pengiriman: {{ \Illuminate\Support\Carbon::parse($submittedReport['submitted_at'])->translatedFormat('d F Y, H:i') }} WITA
                        </p>

                        <!-- QR Code Frame -->
                        <div class="mt-4 flex flex-col items-center gap-3.5 rounded-lg border border-slate-200/80 bg-white p-3.5 text-center sm:flex-row sm:text-left">
                            <img id="tracking-qr" class="size-40 shrink-0 rounded-md border border-slate-200 sm:size-44" src="{{ $trackingQrCode }}" alt="QR akses rahasia laporan {{ $submittedReport['code'] }}">
                            <div>
                                <p class="text-xs sm:text-sm font-semibold text-[#0B2342]">Pindai untuk membuka status langsung</p>
                                <p class="mt-0.5 text-[11px] leading-relaxed text-[#64748B]">QR ini menyimpan akses laporan secara aman. Pindai dengan kamera atau unggah gambarnya di halaman cek status tanpa mengetik kode dan PIN.</p>
                            </div>
                        </div>

                        <p class="mt-3 text-center text-[11px] leading-relaxed text-[#DC2626] font-medium">
                            PERHATIAN: PIN rahasia hanya ditampilkan satu kali pada layar ini. Segera salin atau simpan sebelum menutup halaman.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-5 flex flex-col gap-2 sm:flex-row">
                        <button type="button" class="button-secondary flex-1 text-xs font-semibold py-2" data-copy-access>
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75"/>
                            </svg>
                            <span>Salin kode & PIN</span>
                        </button>
                        <button type="button" class="button-secondary flex-1 text-xs font-semibold py-2" data-download-access>
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-4.5-6L12 15m0 0-4.5-4.5M12 15V3"/>
                            </svg>
                            <span data-download-label>Unduh gambar akses</span>
                        </button>
                    </div>

                    <p class="mt-3 text-center text-[11px] text-[#64748B]" aria-live="polite" data-access-download-status></p>

                    <a href="{{ $trackingUrl }}" class="button-primary mt-2.5 w-full text-xs sm:text-sm font-semibold py-2.5 shadow-2xs">
                        <span>Cek status</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
