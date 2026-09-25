<x-layouts.public title="Lapor KUPVA secara aman">
    <section class="relative overflow-hidden bg-navy-950 text-white">
        <div class="hero-grid absolute inset-0 opacity-25" aria-hidden="true"></div>
        <div class="hero-glow absolute -right-32 -top-40 size-[36rem] rounded-full" aria-hidden="true"></div>
        <div class="public-container relative grid min-h-[650px] items-center gap-12 py-20 lg:grid-cols-[1.08fr_.92fr] lg:py-24">
            <div>
                <div class="eyebrow-dark"><span class="size-2 rounded-full bg-teal-300"></span>Kanal pelaporan resmi wilayah NTB</div>
                <h1 class="mt-7 max-w-3xl text-4xl font-extrabold leading-[1.08] tracking-[-0.035em] sm:text-5xl lg:text-6xl">Berani melapor,<br><span class="text-gradient">bersama menjaga.</span></h1>
                <p class="mt-6 max-w-xl text-base leading-7 text-blue-100/80 sm:text-lg">Laporkan dugaan kegiatan penukaran valuta asing yang tidak sesuai ketentuan. Identitas Anda tidak diminta dan proses penanganannya dapat dipantau secara aman.</p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('reports.create') }}" class="button-light">Buat laporan anonim <svg class="size-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69L10.22 5.03a.75.75 0 0 1 1.06-1.06l5.5 5.5a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 1 1-1.06-1.06l4.22-4.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg></a>
                    <a href="{{ route('reports.track') }}" class="button-ghost-light">Cek status laporan</a>
                </div>
                <div class="mt-10 flex flex-wrap gap-x-7 gap-y-3 text-sm text-blue-100/70">
                    @foreach (['Tanpa nama & NIK', 'Lokasi akurat', 'Progres transparan'] as $feature)
                        <span class="inline-flex items-center gap-2"><svg class="size-4 text-teal-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/></svg>{{ $feature }}</span>
                    @endforeach
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-lg lg:ml-auto">
                <div class="absolute -inset-4 rounded-[2.2rem] bg-white/5 blur-sm" aria-hidden="true"></div>
                <div class="relative overflow-hidden rounded-[1.75rem] border border-white/15 bg-white/10 p-4 shadow-2xl backdrop-blur-md">
                    <div class="flex items-center justify-between px-2 py-2"><div><p class="text-xs font-semibold uppercase tracking-[.18em] text-blue-200">Alur penanganan</p><p class="mt-1 font-bold">Laporan Anda terus bergerak</p></div><span class="rounded-full bg-teal-300/15 px-3 py-1 text-xs font-bold text-teal-200">Terlindungi</span></div>
                    <div class="mt-3 rounded-2xl bg-white p-5 text-slate-900">
                        @foreach ([['Laporan dikirim', 'Data tersimpan dengan aman'], ['Laporan diterima', 'Pemeriksaan awal oleh petugas'], ['Koordinasi dengan APH', 'Koordinasi penanganan'], ['Kunjungan lapangan', 'Verifikasi atau penertiban'], ['Laporan hasil', 'Hasil penanganan tersedia'], ['Selesai', 'Proses telah dituntaskan']] as $index => [$label, $description])
                            <div class="relative flex gap-4 pb-5 last:pb-0">
                                @if (! $loop->last)<span class="absolute left-[15px] top-8 h-[calc(100%-1.5rem)] w-px bg-slate-200"></span>@endif
                                <span class="relative z-10 grid size-8 shrink-0 place-items-center rounded-full {{ $index < 2 ? 'bg-teal-500 text-white' : 'bg-slate-100 text-slate-400' }}">
                                    @if ($index < 2)<svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/></svg>@else<span class="text-[11px] font-extrabold">{{ $index + 1 }}</span>@endif
                                </span>
                                <span><span class="block text-sm font-bold text-slate-800">{{ $label }}</span><span class="mt-0.5 block text-xs text-slate-500">{{ $description }}</span></span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="relative overflow-hidden scroll-mt-20 bg-[#f0f6fe] py-12 sm:py-16">
        <!-- Subtle Decorative Background Pattern & Glow -->
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-96 w-[44rem] rounded-full bg-gradient-to-b from-blue-200/30 via-blue-100/15 to-transparent blur-3xl"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#93c5fd_1px,transparent_1px)] [background-size:24px_24px] opacity-25"></div>
            <div class="absolute top-10 left-12 size-1.5 rounded-full bg-blue-400/40 hidden sm:block"></div>
            <div class="absolute top-20 right-16 size-2 rounded-full bg-blue-400/30 hidden sm:block"></div>
            <div class="absolute bottom-10 left-20 size-2 rounded-full bg-blue-400/30 hidden sm:block"></div>
            <div class="absolute bottom-8 right-24 size-1.5 rounded-full bg-blue-400/40 hidden sm:block"></div>
        </div>

        <div class="public-container">
            <!-- Header Section -->
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-blue-700">MUDAH DAN TERARAH</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl lg:text-4xl">
                    Tiga langkah untuk ikut menjaga NTB
                </h2>
                <p class="mt-2 text-xs leading-relaxed text-slate-500 sm:text-sm max-w-xl mx-auto">
                    Laporkan kejadian dengan mudah dan pantau perkembangannya secara aman.
                </p>
            </div>

            <!-- Zig-zag 3-Card Flow Container -->
            <div class="relative mt-7 sm:mt-9 pb-2 lg:pb-12">
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-3 lg:gap-6 xl:gap-8">

                    <!-- STEP 01 (Kiri Atas - Paling Tinggi) -->
                    <div class="relative flex flex-col justify-start lg:translate-y-0">
                        <article class="group relative mx-auto w-full max-w-[340px] rounded-3xl border border-slate-100/90 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:border-blue-200/80 hover:shadow-xl hover:shadow-blue-900/5 sm:p-7">
                            <!-- Header Row: Step Number on Left, Icon on Right (Minimalist & Aesthetic) -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="font-sans text-xl font-bold tracking-tight text-navy-950 sm:text-2xl">Step</span>
                                    <span class="font-sans text-xl font-normal tracking-tight text-slate-400 sm:text-2xl">01</span>
                                </div>
                                <div class="flex size-11 items-center justify-center rounded-full bg-blue-600 text-white shadow-sm shadow-blue-600/20 transition-transform duration-300 group-hover:scale-105">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Title -->
                            <h3 class="mt-5 text-base font-bold text-navy-950 transition-colors group-hover:text-blue-700 sm:text-lg">
                                Ceritakan kejadian
                            </h3>

                            <!-- Description -->
                            <p class="mt-2 text-xs leading-relaxed text-slate-500 sm:text-[13px]">
                                Jelaskan kejadian yang Anda temukan, pilih jenis laporan, waktu kejadian, dan tuliskan kronologi yang Anda ketahui.
                            </p>
                        </article>

                        <!-- Connector Desktop (Step 01 -> Step 02 - Elegant Flowing Arc) -->
                        <div class="pointer-events-none absolute left-[calc(100%-6px)] top-7 z-10 hidden h-20 w-28 lg:block xl:w-36" aria-hidden="true">
                            <svg class="h-full w-full overflow-visible" viewBox="0 0 130 80" fill="none">
                                <defs>
                                    <marker id="arrowhead-1" viewBox="0 0 10 10" refX="5" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                        <path d="M 0 1.5 L 8 5 L 0 8.5 z" fill="#2563eb" />
                                    </marker>
                                </defs>
                                <circle cx="6" cy="10" r="3.5" fill="#2563eb" />
                                <path d="M 12 10 C 65 10, 105 20, 114 58" stroke="#2563eb" stroke-width="2" stroke-dasharray="5 5" marker-end="url(#arrowhead-1)" />
                            </svg>
                        </div>

                        <!-- Connector Mobile (Step 01 -> Step 02 - Vertical Flow) -->
                        <div class="flex flex-col items-center justify-center py-3 lg:hidden" aria-hidden="true">
                            <div class="size-2 rounded-full bg-blue-600 ring-2 ring-blue-100"></div>
                            <div class="h-8 w-0 border-l-2 border-dashed border-blue-600"></div>
                            <svg class="-mt-1 size-3 text-blue-600" viewBox="0 0 12 12" fill="currentColor">
                                <path d="M6 10.5L1.5 4.5h9L6 10.5z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- STEP 02 (Tengah - Sedikit Lebih Rendah) -->
                    <div class="relative flex flex-col justify-start lg:translate-y-8 xl:translate-y-10">
                        <article class="group relative mx-auto w-full max-w-[340px] rounded-3xl border border-slate-100/90 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:border-blue-200/80 hover:shadow-xl hover:shadow-blue-900/5 sm:p-7">
                            <!-- Header Row: Step Number on Left, Icon on Right (Minimalist & Aesthetic) -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="font-sans text-xl font-bold tracking-tight text-navy-950 sm:text-2xl">Step</span>
                                    <span class="font-sans text-xl font-normal tracking-tight text-slate-400 sm:text-2xl">02</span>
                                </div>
                                <div class="flex size-11 items-center justify-center rounded-full bg-blue-600 text-white shadow-sm shadow-blue-600/20 transition-transform duration-300 group-hover:scale-105">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                            </div>

                            <!-- Title -->
                            <h3 class="mt-5 text-base font-bold text-navy-950 transition-colors group-hover:text-blue-700 sm:text-lg">
                                Tandai lokasinya
                            </h3>

                            <!-- Description -->
                            <p class="mt-2 text-xs leading-relaxed text-slate-500 sm:text-[13px]">
                                Tentukan lokasi kejadian menggunakan posisi perangkat, pencarian wilayah, atau pilih titik secara langsung pada peta.
                            </p>
                        </article>

                        <!-- Connector Desktop (Step 02 -> Step 03 - Elegant Flowing Arc) -->
                        <div class="pointer-events-none absolute left-[calc(100%-6px)] top-7 z-10 hidden h-20 w-28 lg:block xl:w-36" aria-hidden="true">
                            <svg class="h-full w-full overflow-visible" viewBox="0 0 130 80" fill="none">
                                <defs>
                                    <marker id="arrowhead-2" viewBox="0 0 10 10" refX="5" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                        <path d="M 0 1.5 L 8 5 L 0 8.5 z" fill="#2563eb" />
                                    </marker>
                                </defs>
                                <circle cx="6" cy="10" r="3.5" fill="#2563eb" />
                                <path d="M 12 10 C 65 10, 105 20, 114 58" stroke="#2563eb" stroke-width="2" stroke-dasharray="5 5" marker-end="url(#arrowhead-2)" />
                            </svg>
                        </div>

                        <!-- Connector Mobile (Step 02 -> Step 03 - Vertical Flow) -->
                        <div class="flex flex-col items-center justify-center py-3 lg:hidden" aria-hidden="true">
                            <div class="size-2 rounded-full bg-blue-600 ring-2 ring-blue-100"></div>
                            <div class="h-8 w-0 border-l-2 border-dashed border-blue-600"></div>
                            <svg class="-mt-1 size-3 text-blue-600" viewBox="0 0 12 12" fill="currentColor">
                                <path d="M6 10.5L1.5 4.5h9L6 10.5z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- STEP 03 (Kanan Bawah - Paling Rendah) -->
                    <div class="relative flex flex-col justify-start lg:translate-y-16 xl:translate-y-20">
                        <article class="group relative mx-auto w-full max-w-[340px] rounded-3xl border border-slate-100/90 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:border-blue-200/80 hover:shadow-xl hover:shadow-blue-900/5 sm:p-7">
                            <!-- Header Row: Step Number on Left, Icon on Right (Minimalist & Aesthetic) -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="font-sans text-xl font-bold tracking-tight text-navy-950 sm:text-2xl">Step</span>
                                    <span class="font-sans text-xl font-normal tracking-tight text-slate-400 sm:text-2xl">03</span>
                                </div>
                                <div class="flex size-11 items-center justify-center rounded-full bg-blue-600 text-white shadow-sm shadow-blue-600/20 transition-transform duration-300 group-hover:scale-105">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Title -->
                            <h3 class="mt-5 text-base font-bold text-navy-950 transition-colors group-hover:text-blue-700 sm:text-lg">
                                Simpan kode akses
                            </h3>

                            <!-- Description -->
                            <p class="mt-2 text-xs leading-relaxed text-slate-500 sm:text-[13px]">
                                Dapatkan kode laporan dan PIN rahasia untuk melihat status serta memantau perkembangan laporan Anda.
                            </p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="keamanan" class="scroll-mt-24 bg-white py-20 sm:py-24">
        <div class="public-container grid items-center gap-12 lg:grid-cols-2">
            <div class="rounded-[2rem] bg-gradient-to-br from-blue-50 to-teal-50 p-7 sm:p-10">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="security-card sm:col-span-2"><span class="security-icon">01</span><div><h3>Tanpa identitas pribadi</h3><p>Formulir tidak meminta nama, NIK, nomor telepon, atau alamat pelapor.</p></div></div>
                    <div class="security-card"><span class="security-icon">02</span><div><h3>Akses privat</h3><p>Status hanya terbuka dengan kode dan PIN.</p></div></div>
                    <div class="security-card"><span class="security-icon">03</span><div><h3>Bukti terbatas</h3><p>Lampiran hanya dapat diakses petugas.</p></div></div>
                </div>
            </div>
            <div><p class="eyebrow">Anonim sejak awal</p><h2 class="section-title text-left">Lapor dengan tenang,<br>pantau dengan pasti.</h2><p class="section-lead text-left">TAMBORA dirancang agar masyarakat tidak ragu menyampaikan informasi. Sistem hanya mengumpulkan data kejadian yang dibutuhkan untuk penanganan.</p><a href="{{ route('reports.create') }}" class="mt-7 inline-flex items-center gap-2 font-bold text-blue-700 hover:text-blue-800">Mulai membuat laporan <span aria-hidden="true">→</span></a></div>
        </div>
    </section>

    <section class="bg-blue-700 py-14 text-white">
        <div class="public-container flex flex-col items-start justify-between gap-7 md:flex-row md:items-center"><div><p class="text-sm font-bold uppercase tracking-[.18em] text-blue-200">Sudah pernah melapor?</p><h2 class="mt-2 text-2xl font-extrabold sm:text-3xl">Lihat progres penanganan laporan Anda.</h2></div><a href="{{ route('reports.track') }}" class="button-light shrink-0">Cek status sekarang</a></div>
    </section>
</x-layouts.public>
