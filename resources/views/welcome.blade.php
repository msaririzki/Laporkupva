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
            <!-- Header Section (Terinspirasi Edukasi Publik & PRD TAMBORA) -->
            <div class="mx-auto max-w-2xl text-center">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100/80 px-3.5 py-1 text-xs font-extrabold uppercase tracking-wider text-blue-800">
                    <span class="size-2 rounded-full bg-blue-600 animate-pulse"></span>
                    CARA MELAPOR YANG BAIK
                </span>
                <h2 class="mt-3 text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl lg:text-4xl">
                    5 Langkah Mudah Menyampaikan Laporan
                </h2>
                <p class="mt-3 text-xs leading-relaxed text-slate-500 sm:text-sm max-w-xl mx-auto">
                    Tidak perlu paham teknologi. TAMBORA dirancang sangat mudah, tanpa perlu membuat akun, dan identitas Anda 100% terlindungi.
                </p>
            </div>

            <!-- 5-Step Process Flow (Ramah Orang Awam & Responsif) -->
            <div class="relative mt-8 sm:mt-10">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 lg:gap-3 xl:gap-4">

                    <!-- STEP 01: Buka Laman Tanpa Login -->
                    <div class="relative flex flex-col">
                        <article class="group relative flex h-full flex-col justify-between rounded-3xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-900/5 sm:p-6">
                            <div>
                                <!-- Step Header: Big Number & Badge -->
                                <div class="flex items-center justify-between">
                                    <span class="font-sans text-2xl font-black text-blue-700 tracking-tight sm:text-3xl">1.</span>
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-blue-700 ring-1 ring-inset ring-blue-700/10">Tanpa Akun</span>
                                </div>

                                <!-- Visual Icon Container -->
                                <div class="mt-4 flex size-13 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-700 ring-1 ring-blue-600/15 transition-all duration-300 group-hover:scale-105 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-md group-hover:shadow-blue-600/25">
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                        <path d="M12 7v3m0 0l-1.5-1.5M12 10l1.5-1.5"></path>
                                    </svg>
                                </div>

                                <!-- Title -->
                                <h3 class="mt-4 text-base font-extrabold text-navy-950 transition-colors group-hover:text-blue-700">
                                    Buka Laman Tanpa Login
                                </h3>

                                <!-- Description -->
                                <p class="mt-2 text-xs leading-relaxed text-slate-500 sm:text-[13px]">
                                    Langsung buka web dari HP atau laptop. Anda tidak perlu mendaftar akun dan data diri (Nama/NIK/No HP) tidak diminta.
                                </p>
                            </div>
                        </article>

                        <!-- Connector Desktop Arrow (Step 1 -> 2) -->
                        <div class="pointer-events-none absolute -right-2 top-1/2 -translate-y-1/2 z-10 hidden text-blue-300/80 lg:block xl:-right-2.5" aria-hidden="true">
                            <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- STEP 02: Pilih Masalah & Ceritakan -->
                    <div class="relative flex flex-col">
                        <article class="group relative flex h-full flex-col justify-between rounded-3xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-900/5 sm:p-6">
                            <div>
                                <!-- Step Header: Big Number & Badge -->
                                <div class="flex items-center justify-between">
                                    <span class="font-sans text-2xl font-black text-blue-700 tracking-tight sm:text-3xl">2.</span>
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-blue-700 ring-1 ring-inset ring-blue-700/10">Kronologi</span>
                                </div>

                                <!-- Visual Icon Container -->
                                <div class="mt-4 flex size-13 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-700 ring-1 ring-blue-600/15 transition-all duration-300 group-hover:scale-105 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-md group-hover:shadow-blue-600/25">
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>

                                <!-- Title -->
                                <h3 class="mt-4 text-base font-extrabold text-navy-950 transition-colors group-hover:text-blue-700">
                                    Pilih Masalah & Ceritakan
                                </h3>

                                <!-- Description -->
                                <p class="mt-2 text-xs leading-relaxed text-slate-500 sm:text-[13px]">
                                    Pilih dugaan masalah (tanpa izin, kurs mencurigakan, dll.) lalu tulis cerita kejadian secara ringkas dengan bahasa sehari-hari.
                                </p>
                            </div>
                        </article>

                        <!-- Connector Desktop Arrow (Step 2 -> 3) -->
                        <div class="pointer-events-none absolute -right-2 top-1/2 -translate-y-1/2 z-10 hidden text-blue-300/80 lg:block xl:-right-2.5" aria-hidden="true">
                            <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- STEP 03: Tandai Titik di Peta -->
                    <div class="relative flex flex-col">
                        <article class="group relative flex h-full flex-col justify-between rounded-3xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-900/5 sm:p-6">
                            <div>
                                <!-- Step Header: Big Number & Badge -->
                                <div class="flex items-center justify-between">
                                    <span class="font-sans text-2xl font-black text-blue-700 tracking-tight sm:text-3xl">3.</span>
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-blue-700 ring-1 ring-inset ring-blue-700/10">Lokasi NTB</span>
                                </div>

                                <!-- Visual Icon Container -->
                                <div class="mt-4 flex size-13 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-700 ring-1 ring-blue-600/15 transition-all duration-300 group-hover:scale-105 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-md group-hover:shadow-blue-600/25">
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>

                                <!-- Title -->
                                <h3 class="mt-4 text-base font-extrabold text-navy-950 transition-colors group-hover:text-blue-700">
                                    Tandai Titik di Peta
                                </h3>

                                <!-- Description -->
                                <p class="mt-2 text-xs leading-relaxed text-slate-500 sm:text-[13px]">
                                    Tentukan tempat kejadian di wilayah NTB. Cukup klik tombol deteksi lokasi otomatis atau ketik nama desa/kecamatan.
                                </p>
                            </div>
                        </article>

                        <!-- Connector Desktop Arrow (Step 3 -> 4) -->
                        <div class="pointer-events-none absolute -right-2 top-1/2 -translate-y-1/2 z-10 hidden text-blue-300/80 lg:block xl:-right-2.5" aria-hidden="true">
                            <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- STEP 04: Lampirkan Bukti (Bila Ada) -->
                    <div class="relative flex flex-col">
                        <article class="group relative flex h-full flex-col justify-between rounded-3xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-900/5 sm:p-6">
                            <div>
                                <!-- Step Header: Big Number & Badge -->
                                <div class="flex items-center justify-between">
                                    <span class="font-sans text-2xl font-black text-blue-700 tracking-tight sm:text-3xl">4.</span>
                                    <span class="inline-flex items-center rounded-full bg-teal-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-teal-800 ring-1 ring-inset ring-teal-700/10">Opsional</span>
                                </div>

                                <!-- Visual Icon Container -->
                                <div class="mt-4 flex size-13 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-700 ring-1 ring-blue-600/15 transition-all duration-300 group-hover:scale-105 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-md group-hover:shadow-blue-600/25">
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                </div>

                                <!-- Title -->
                                <h3 class="mt-4 text-base font-extrabold text-navy-950 transition-colors group-hover:text-blue-700">
                                    Lampirkan Bukti (Bila Ada)
                                </h3>

                                <!-- Description -->
                                <p class="mt-2 text-xs leading-relaxed text-slate-500 sm:text-[13px]">
                                    Unggah foto plang toko, struk, atau dokumen pendukung. Bila tidak ada atau tidak aman memotret, Anda tetap bisa melapor.
                                </p>
                            </div>
                        </article>

                        <!-- Connector Desktop Arrow (Step 4 -> 5) -->
                        <div class="pointer-events-none absolute -right-2 top-1/2 -translate-y-1/2 z-10 hidden text-blue-300/80 lg:block xl:-right-2.5" aria-hidden="true">
                            <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- STEP 05: Simpan Kode & Pantau Hasil -->
                    <div class="relative flex flex-col">
                        <article class="group relative flex h-full flex-col justify-between rounded-3xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-xl hover:shadow-blue-900/5 sm:p-6">
                            <div>
                                <!-- Step Header: Big Number & Badge -->
                                <div class="flex items-center justify-between">
                                    <span class="font-sans text-2xl font-black text-blue-700 tracking-tight sm:text-3xl">5.</span>
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-blue-700 ring-1 ring-inset ring-blue-700/10">Kode Akses</span>
                                </div>

                                <!-- Visual Icon Container -->
                                <div class="mt-4 flex size-13 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-700 ring-1 ring-blue-600/15 transition-all duration-300 group-hover:scale-105 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-md group-hover:shadow-blue-600/25">
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        <circle cx="12" cy="16" r="1"></circle>
                                    </svg>
                                </div>

                                <!-- Title -->
                                <h3 class="mt-4 text-base font-extrabold text-navy-950 transition-colors group-hover:text-blue-700">
                                    Simpan Kode & Pantau Hasil
                                </h3>

                                <!-- Description -->
                                <p class="mt-2 text-xs leading-relaxed text-slate-500 sm:text-[13px]">
                                    Dapatkan Kode Laporan dan PIN rahasia 6-digit. Simpan kode ini untuk memantau status penanganan laporan Anda kapan saja.
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>

            <!-- Reassurance Banner & Direct CTA -->
            <div class="mt-8 rounded-3xl border border-blue-100 bg-white p-5 sm:p-7 shadow-xs flex flex-col md:flex-row items-center justify-between gap-5">
                <div class="flex items-center gap-4 text-left">
                    <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-teal-50 text-teal-700 ring-1 ring-teal-600/20">
                        <svg class="size-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm sm:text-base font-extrabold text-navy-950">Laporan Anda Aman & Langsung Diproses</p>
                        <p class="mt-0.5 text-xs sm:text-[13px] text-slate-500 leading-relaxed">Setiap laporan masuk akan ditindaklanjuti secara resmi oleh petugas pengawas Bank Indonesia Provinsi NTB.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                    <a href="{{ route('reports.create') }}" class="button-primary w-full sm:w-auto text-xs sm:text-sm">
                        Mulai Buat Laporan
                        <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69L10.22 5.03a.75.75 0 0 1 1.06-1.06l5.5 5.5a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 1 1-1.06-1.06l4.22-4.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                    </a>
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
