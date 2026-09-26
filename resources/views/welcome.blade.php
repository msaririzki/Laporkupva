<x-layouts.public title="Lapor KUPVA secara aman">
    @php
        $reportingSteps = [
            ['number' => '01', 'title' => 'Ceritakan kejadian', 'description' => 'Sampaikan informasi penting secara singkat dan jelas.'],
            ['number' => '02', 'title' => 'Tentukan lokasi', 'description' => 'Gunakan lokasi saat ini atau pilih titik pada peta.'],
            ['number' => '03', 'title' => 'Lampirkan bukti', 'description' => 'Tambahkan minimal satu foto atau PDF sebagai petunjuk.'],
            ['number' => '04', 'title' => 'Simpan akses', 'description' => 'Gunakan kode, PIN, atau QR untuk memantau laporan.'],
        ];

        $timelineSteps = [
            ['title' => 'Laporan dikirim', 'state' => 'done'],
            ['title' => 'Laporan diterima', 'state' => 'done'],
            ['title' => 'Koordinasi dengan APH', 'state' => 'active'],
            ['title' => 'Kunjungan lapangan / penertiban', 'state' => 'upcoming'],
            ['title' => 'Laporan hasil', 'state' => 'upcoming'],
            ['title' => 'Selesai', 'state' => 'upcoming'],
        ];
    @endphp

    <section class="relative isolate overflow-hidden bg-[#061b38] text-white">
        <img
            src="{{ asset('images/hero-bg.webp') }}"
            alt=""
            class="absolute inset-0 -z-20 h-full w-full object-cover object-[62%_center] lg:object-center"
            width="1600"
            height="900"
            fetchpriority="high"
        >
        <div class="absolute inset-0 -z-10 bg-[linear-gradient(90deg,rgba(3,20,46,0.98)_0%,rgba(5,31,69,0.94)_46%,rgba(5,31,69,0.52)_72%,rgba(5,31,69,0.28)_100%)]"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-[#061b38]/80 via-transparent to-[#061b38]/20 lg:hidden"></div>

        <div class="public-container grid min-h-[560px] items-center gap-10 py-12 sm:min-h-[600px] sm:py-16 lg:grid-cols-[minmax(0,1fr)_400px] lg:gap-16 lg:py-18">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.13em] text-blue-50 backdrop-blur-md sm:text-[11px]">
                    <span class="size-1.5 rounded-full bg-emerald-400 shadow-[0_0_0_4px_rgba(52,211,153,0.12)]"></span>
                    Kanal resmi Bank Indonesia wilayah NTB
                </div>

                <h1 class="mt-5 max-w-xl text-[2.35rem] font-extrabold leading-[1.08] tracking-[-0.035em] text-white sm:text-5xl lg:text-[3.5rem]">
                    Berani melapor.<br>
                    <span class="text-[#79c9ff]">Identitas tetap aman.</span>
                </h1>

                <p class="mt-5 max-w-xl text-sm leading-6 text-slate-200 sm:text-base sm:leading-7">
                    Laporkan dugaan kegiatan penukaran valuta asing tanpa izin di NTB. Tidak perlu membuat akun, tidak diminta nama atau NIK, dan perkembangan laporan dapat dipantau secara mandiri.
                </p>

                <div class="mt-7 grid gap-3 sm:flex sm:flex-wrap">
                    <a href="{{ route('reports.create') }}" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[#2563EB] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-950/25 transition hover:-translate-y-0.5 hover:bg-[#1D4ED8] focus-visible:outline-white">
                        <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="m22 2-7 20-4-9-9-4Z" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 2 11 13" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Buat laporan anonim
                        <span aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('reports.track') }}" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/8 px-6 py-3 text-sm font-bold text-white backdrop-blur-md transition hover:-translate-y-0.5 hover:border-white/35 hover:bg-white/14 focus-visible:outline-white">
                        <svg class="size-4.5 text-sky-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4" stroke-linecap="round"/>
                        </svg>
                        Cek status laporan
                    </a>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-3 border-t border-white/12 pt-5 text-xs text-slate-200 sm:grid-cols-3 sm:gap-4">
                    <div class="flex items-center gap-2.5">
                        <span class="grid size-7 shrink-0 place-items-center rounded-full bg-emerald-400/12 text-emerald-300">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                        </span>
                        <span><strong class="block text-white">Tanpa nama &amp; NIK</strong>Anonim sejak awal</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="grid size-7 shrink-0 place-items-center rounded-full bg-sky-400/12 text-sky-300">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 5-5.5 10.2-7.4 11.8a1 1 0 0 1-1.2 0C9.5 20.2 4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <span><strong class="block text-white">Lokasi akurat</strong>GPS atau pilih di peta</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="grid size-7 shrink-0 place-items-center rounded-full bg-amber-400/12 text-amber-300">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 19V9"/><path d="M10 19V5"/><path d="M16 19v-7"/><path d="M22 19V3"/></svg>
                        </span>
                        <span><strong class="block text-white">Dapat dipantau</strong>Dengan kode dan PIN</span>
                    </div>
                </div>
            </div>

            <aside class="hidden lg:block" aria-label="Contoh alur penanganan laporan">
                <div class="rounded-[1.75rem] border border-white/70 bg-white/96 p-5 text-[#0B2342] shadow-[0_30px_80px_-30px_rgba(2,12,27,0.7)] backdrop-blur-xl">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-blue-600">Contoh alur penanganan</p>
                            <h2 class="mt-1 text-base font-extrabold">Progres yang mudah dipahami</h2>
                        </div>
                        <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700">Terlindungi</span>
                    </div>

                    <div class="mt-4 flex items-center justify-between text-[11px]">
                        <span class="font-semibold text-slate-500">Contoh: tahap 2 dari 6 selesai</span>
                        <span class="font-bold text-blue-600">Tahap 3 berjalan</span>
                    </div>
                    <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full w-1/2 rounded-full bg-gradient-to-r from-emerald-500 to-blue-600"></div>
                    </div>

                    <ol class="mt-4 space-y-1.5">
                        @foreach ($timelineSteps as $index => $step)
                            <li class="flex items-center gap-3 rounded-xl px-2.5 py-2 {{ $step['state'] === 'active' ? 'border border-blue-200 bg-blue-50/70' : '' }}">
                                <span class="grid size-7 shrink-0 place-items-center rounded-lg text-xs font-extrabold {{ $step['state'] === 'done' ? 'bg-emerald-600 text-white' : ($step['state'] === 'active' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-400') }}">
                                    @if ($step['state'] === 'done')
                                        <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.7 4.15a.75.75 0 0 1 .15 1.05l-8 10.5a.75.75 0 0 1-1.13.08l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.9 3.89 7.47-9.82a.75.75 0 0 1 1.05-.14Z" clip-rule="evenodd"/></svg>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </span>
                                <span class="min-w-0 flex-1 text-xs font-bold {{ $step['state'] === 'upcoming' ? 'text-slate-500' : 'text-slate-800' }}">{{ $step['title'] }}</span>
                                @if ($step['state'] === 'active')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-700"><span class="size-1.5 rounded-full bg-blue-600"></span>Proses</span>
                                @elseif ($step['state'] === 'done')
                                    <span class="text-[10px] font-bold text-emerald-700">Selesai</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>
            </aside>
        </div>
    </section>

    <section id="cara-kerja" class="scroll-mt-16 bg-white py-14 sm:py-18 lg:py-20">
        <div class="public-container">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs font-extrabold uppercase tracking-[0.15em] text-blue-600">Mudah dan cepat</p>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-[#0B2342] sm:text-3xl lg:text-4xl">Empat langkah untuk melapor</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">Tidak perlu login. Siapkan informasi lokasi dan bukti pendukung, lalu simpan akses rahasia Anda.</p>
                </div>
                <a href="{{ route('guide') }}" class="inline-flex items-center gap-2 self-start text-sm font-bold text-blue-700 transition hover:text-blue-900 sm:self-auto">Lihat panduan lengkap <span aria-hidden="true">→</span></a>
            </div>

            <div class="relative mt-8 grid gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-4">
                <div class="pointer-events-none absolute left-[12.5%] right-[12.5%] top-8 hidden h-px bg-slate-200 lg:block" aria-hidden="true"></div>
                @foreach ($reportingSteps as $step)
                    <article class="relative rounded-2xl border border-slate-200/80 bg-[#F8FAFC] p-5 transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:bg-white hover:shadow-lg hover:shadow-slate-200/50">
                        <span class="relative z-10 grid size-10 place-items-center rounded-xl bg-[#0B2342] text-xs font-extrabold text-white shadow-sm">{{ $step['number'] }}</span>
                        <h3 class="mt-5 text-base font-extrabold text-[#0B2342]">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $step['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="keamanan" class="scroll-mt-16 bg-[#F2F6FB] py-14 sm:py-18 lg:py-20">
        <div class="public-container">
            <div class="overflow-hidden rounded-[1.75rem] border border-slate-200/80 bg-white shadow-[0_24px_70px_-45px_rgba(11,35,66,0.45)]">
                <div class="grid items-center lg:grid-cols-[minmax(0,1fr)_420px]">
                    <div class="p-6 sm:p-9 lg:p-12">
                        <p class="text-xs font-extrabold uppercase tracking-[0.15em] text-emerald-700">Privasi pelapor</p>
                        <h2 class="mt-2 max-w-xl text-2xl font-extrabold tracking-tight text-[#0B2342] sm:text-3xl lg:text-4xl">Lapor dengan tenang. Kami hanya meminta informasi kejadian.</h2>
                        <p class="mt-4 max-w-xl text-sm leading-6 text-slate-600 sm:text-base sm:leading-7">TAMBORA dirancang untuk menerima laporan tanpa akun dan tanpa identitas pribadi. Informasi hanya digunakan untuk pemeriksaan dan tindak lanjut oleh petugas berwenang.</p>

                        <ul class="mt-6 grid gap-3 sm:grid-cols-3">
                            <li class="flex items-start gap-2.5 text-sm font-semibold text-slate-700">
                                <span class="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full bg-emerald-100 text-emerald-700"><svg class="size-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.7 4.15a.75.75 0 0 1 .15 1.05l-8 10.5a.75.75 0 0 1-1.13.08l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.9 3.89 7.47-9.82a.75.75 0 0 1 1.05-.14Z" clip-rule="evenodd"/></svg></span>
                                Tanpa nama dan NIK
                            </li>
                            <li class="flex items-start gap-2.5 text-sm font-semibold text-slate-700">
                                <span class="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full bg-emerald-100 text-emerald-700"><svg class="size-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.7 4.15a.75.75 0 0 1 .15 1.05l-8 10.5a.75.75 0 0 1-1.13.08l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.9 3.89 7.47-9.82a.75.75 0 0 1 1.05-.14Z" clip-rule="evenodd"/></svg></span>
                                Tanpa nomor telepon
                            </li>
                            <li class="flex items-start gap-2.5 text-sm font-semibold text-slate-700">
                                <span class="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full bg-emerald-100 text-emerald-700"><svg class="size-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.7 4.15a.75.75 0 0 1 .15 1.05l-8 10.5a.75.75 0 0 1-1.13.08l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.9 3.89 7.47-9.82a.75.75 0 0 1 1.05-.14Z" clip-rule="evenodd"/></svg></span>
                                Akses dengan PIN rahasia
                            </li>
                        </ul>

                        <div class="mt-7 flex flex-wrap gap-3">
                            <a href="{{ route('reports.create') }}" class="button-primary">Mulai membuat laporan <span aria-hidden="true">→</span></a>
                            <a href="{{ route('privacy') }}" class="button-secondary">Pelajari privasi</a>
                        </div>
                    </div>

                    <div class="relative flex min-h-64 items-center justify-center overflow-hidden bg-[radial-gradient(circle_at_center,#dbeafe_0%,#edf5ff_52%,#f8fbff_100%)] p-8 sm:min-h-80 lg:h-full">
                        <div class="absolute left-8 top-8 size-24 rounded-full border border-blue-200/70"></div>
                        <div class="absolute bottom-8 right-8 size-40 rounded-full border border-blue-200/50"></div>
                        <img
                            src="{{ asset('images/illustrations/security-shield.webp') }}"
                            alt="Perlindungan privasi pelapor TAMBORA"
                            class="relative w-full max-w-[320px] object-contain drop-shadow-xl"
                            width="680"
                            height="382"
                            loading="lazy"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#0B2342] py-10 text-white sm:py-12">
        <div class="public-container flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-extrabold uppercase tracking-[0.15em] text-sky-300">Sudah pernah melapor?</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Lihat status tindak lanjut terbaru.</h2>
                <p class="mt-2 text-sm leading-6 text-slate-300">Masukkan kode laporan dan PIN, atau unggah QR yang tersimpan pada perangkat Anda.</p>
            </div>
            <a href="{{ route('reports.track') }}" class="inline-flex min-h-12 shrink-0 items-center justify-center gap-2 self-start rounded-xl bg-white px-6 py-3 text-sm font-extrabold text-[#0B2342] shadow-lg transition hover:-translate-y-0.5 hover:bg-sky-50 md:self-auto">
                Cek status laporan
                <span aria-hidden="true">→</span>
            </a>
        </div>
    </section>
</x-layouts.public>
