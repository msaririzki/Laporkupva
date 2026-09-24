<x-layouts.public title="Laporan berhasil dikirim">
    <section class="min-h-[70vh] py-14 sm:py-20">
        <div class="public-container max-w-2xl">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-soft">
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 px-6 py-9 text-center text-white sm:px-10">
                    <span class="mx-auto grid size-16 place-items-center rounded-full bg-white/20 ring-8 ring-white/10"><svg class="size-8" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/></svg></span>
                    <h1 class="mt-6 text-2xl font-extrabold sm:text-3xl">Laporan berhasil dikirim</h1>
                    <p class="mt-2 text-sm text-teal-50">Terima kasih sudah ikut membantu menjaga masyarakat NTB.</p>
                </div>
                <div class="p-6 sm:p-10">
                    <div class="rounded-2xl border-2 border-dashed border-blue-200 bg-blue-50 p-5 sm:p-6">
                        <p class="text-center text-xs font-extrabold uppercase tracking-[.18em] text-blue-700">Simpan akses rahasia Anda</p>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div class="access-code"><span>Kode laporan</span><strong id="report-code">{{ $submittedReport['code'] }}</strong></div>
                            <div class="access-code"><span>PIN pelacakan</span><strong id="report-pin">{{ $submittedReport['pin'] }}</strong></div>
                        </div>
                        <p class="mt-4 text-center text-xs font-semibold text-blue-700">Dikirim {{ \Illuminate\Support\Carbon::parse($submittedReport['submitted_at'])->translatedFormat('d F Y, H:i') }} WITA</p>
                        <p class="mt-5 text-center text-xs leading-5 text-blue-800">PIN hanya ditampilkan pada halaman ini. Simpan keduanya di tempat aman dan jangan berikan kepada orang lain.</p>
                    </div>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <button type="button" class="button-secondary flex-1" data-copy-access>Salin kode & PIN</button>
                        <button type="button" class="button-secondary flex-1" onclick="window.print()">Cetak / simpan PDF</button>
                    </div>
                    <a href="{{ route('reports.track') }}" class="button-primary mt-4 w-full">Cek status laporan</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
