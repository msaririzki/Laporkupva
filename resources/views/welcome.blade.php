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

    <section id="cara-kerja" class="relative overflow-hidden scroll-mt-20 bg-gradient-to-b from-[#eef6ff] via-[#f4f9ff] to-[#e8f2fe] py-12 sm:py-18">
        <!-- Mountain Silhouette Background at Bottom (TAMBORA Atmosphere) -->
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-24 sm:h-36 overflow-hidden opacity-30" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1440 220" fill="none" preserveAspectRatio="none">
                <path d="M0 220L0 130L110 115L230 165L390 90L530 145L680 45L820 135L960 75L1120 125L1270 65L1440 105L1440 220Z" fill="url(#mountains-soft-grad)" />
                <defs>
                    <linearGradient id="mountains-soft-grad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#93c5fd" stop-opacity="0.6"/>
                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.85"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <!-- Subtle Dot Grid Patterns on Sides -->
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute top-10 right-8 size-32 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
            <div class="absolute bottom-10 left-8 size-32 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
        </div>

        <div class="public-container">
            <!-- Header Section (Sesuai Referensi & Minimalis) -->
            <div class="mx-auto max-w-2xl text-center">
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-100/90 px-4 py-1.5 text-xs font-extrabold uppercase tracking-wider text-blue-700">
                    <span class="size-2 rounded-full bg-blue-600"></span>
                    CARA LAPOR
                </div>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-navy-950 sm:text-4xl">
                    Cara lapor di TAMBORA
                </h2>
                <p class="mt-2 text-sm sm:text-base text-slate-500 max-w-xl mx-auto leading-relaxed">
                    Gampang, kok. Cukup ikuti 5 langkah sederhana berikut tanpa perlu login atau registrasi akun.
                </p>
            </div>

            <!-- 5-Step Clean & Minimalist Cards Grid (Tanpa Garis Rumit) -->
            <div class="mt-8 sm:mt-12">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 lg:gap-3 xl:gap-4">

                    <!-- STEP 01: Buka TAMBORA -->
                    <article class="group flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-xs font-black text-white shadow-md shadow-blue-600/30">
                                    01
                                </span>
                            </div>

                            <!-- Illustration: Laptop / Web Browser -->
                            <div class="my-3 flex h-24 items-center justify-center sm:h-28">
                                <svg class="h-22 sm:h-24 w-auto overflow-visible" viewBox="0 0 120 100" fill="none" aria-hidden="true">
                                    <!-- Laptop Base -->
                                    <path d="M 14 80 L 106 80 L 114 86 L 6 86 Z" fill="#94a3b8"/>
                                    <!-- Laptop Screen -->
                                    <rect x="20" y="20" width="80" height="60" rx="5" fill="#1e293b"/>
                                    <!-- Screen Interior -->
                                    <rect x="23" y="24" width="74" height="52" rx="3" fill="#f8fafc"/>
                                    <!-- Browser Header Bar -->
                                    <rect x="23" y="24" width="74" height="12" fill="#e2e8f0"/>
                                    <!-- Browser Action Dots -->
                                    <circle cx="29" cy="30" r="1.5" fill="#ef4444"/>
                                    <circle cx="34" cy="30" r="1.5" fill="#f59e0b"/>
                                    <circle cx="39" cy="30" r="1.5" fill="#10b981"/>
                                    <!-- URL Address Bar -->
                                    <rect x="45" y="27" width="46" height="6" rx="3" fill="#ffffff"/>
                                    <!-- Web Content Banner -->
                                    <rect x="28" y="41" width="64" height="30" rx="3" fill="#dbeafe"/>
                                    <!-- TAMBORA Shield Mark -->
                                    <path d="M 60 46 C 66 46, 69 44, 69 44 C 69 55, 60 62, 60 62 C 60 62, 51 55, 51 44 C 51 44, 54 46, 60 46 Z" fill="#2563eb"/>
                                    <path d="M 57 53 L 59 55 L 64 50" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                </svg>
                            </div>

                            <!-- Title -->
                            <h3 class="text-base font-extrabold text-navy-950">
                                Buka TAMBORA
                            </h3>

                            <!-- Description -->
                            <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                                Buka <a href="https://laporkupva.ikydev.com/" target="_blank" rel="noopener noreferrer" class="font-bold text-blue-700 underline decoration-blue-300 hover:text-blue-900 transition-colors">TAMBORA</a> tanpa perlu login.
                            </p>
                        </div>
                    </article>

                    <!-- STEP 02: Ceritakan -->
                    <article class="group flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-xs font-black text-white shadow-md shadow-blue-600/30">
                                    02
                                </span>
                            </div>

                            <!-- Illustration: Character with Phone & Speech Bubble -->
                            <div class="my-3 flex h-24 items-center justify-center sm:h-28">
                                <svg class="h-22 sm:h-24 w-auto overflow-visible" viewBox="0 0 120 100" fill="none" aria-hidden="true">
                                    <!-- Speech Bubble -->
                                    <g>
                                        <rect x="52" y="12" width="56" height="38" rx="8" fill="#e0f2fe" stroke="#bae6fd" stroke-width="1.2"/>
                                        <path d="M60 50 L56 58 L68 50 Z" fill="#e0f2fe" stroke="#bae6fd" stroke-width="1.2"/>
                                        <rect x="60" y="20" width="34" height="3" rx="1.5" fill="#38bdf8"/>
                                        <rect x="60" y="27" width="40" height="3" rx="1.5" fill="#93c5fd"/>
                                        <rect x="60" y="34" width="24" height="3" rx="1.5" fill="#93c5fd"/>
                                    </g>

                                    <!-- Character -->
                                    <g>
                                        <path d="M 18 48 C 14 30, 38 16, 50 24 C 62 32, 64 50, 60 68 C 56 78, 44 78, 40 70 C 32 78, 20 70, 18 48 Z" fill="#1e293b"/>
                                        <circle cx="38" cy="42" r="13" fill="#fed7aa"/>
                                        <path d="M 28 36 C 32 28, 48 28, 52 36 C 46 32, 36 32, 28 36 Z" fill="#0f172a"/>
                                        <circle cx="44" cy="42" r="1.2" fill="#0f172a"/>
                                        <path d="M 44 46 Q 46 48 48 46" stroke="#0f172a" stroke-width="1" fill="none" stroke-linecap="round"/>
                                        <path d="M 24 74 C 24 62, 54 62, 54 74 L 54 86 L 24 86 Z" fill="#2563eb"/>
                                        <!-- Phone -->
                                        <rect x="52" y="46" width="16" height="28" rx="3" fill="#0f172a"/>
                                        <rect x="54" y="48" width="12" height="22" rx="1.5" fill="#38bdf8"/>
                                    </g>
                                </svg>
                            </div>

                            <!-- Title -->
                            <h3 class="text-base font-extrabold text-navy-950">
                                Ceritakan
                            </h3>

                            <!-- Description -->
                            <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                                Ceritakan apa yang terjadi.
                            </p>
                        </div>
                    </article>

                    <!-- STEP 03: Tentukan lokasi -->
                    <article class="group flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-xs font-black text-white shadow-md shadow-blue-600/30">
                                    03
                                </span>
                            </div>

                            <!-- Illustration: Folded Map & Blue Pin -->
                            <div class="my-3 flex h-24 items-center justify-center sm:h-28">
                                <svg class="h-22 sm:h-24 w-auto overflow-visible" viewBox="0 0 120 100" fill="none" aria-hidden="true">
                                    <!-- Map panels -->
                                    <polygon points="15,40 45,30 45,82 15,92" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1.2"/>
                                    <path d="M 18 68 Q 28 60 36 72 L 45 64 L 45 82 L 15 92 Z" fill="#dcfce7" opacity="0.8"/>
                                    <polygon points="45,30 75,40 75,92 45,82" fill="#e2e8f0" stroke="#cbd5e1" stroke-width="1.2"/>
                                    <polygon points="75,40 105,30 105,82 75,92" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="1.2"/>
                                    <path d="M 75 70 Q 85 54 96 62 L 105 56 L 105 82 L 75 92 Z" fill="#dcfce7" opacity="0.8"/>

                                    <!-- Pin Shadow -->
                                    <ellipse cx="60" cy="78" rx="11" ry="3.5" fill="#64748b" opacity="0.25"/>

                                    <!-- Location Pin -->
                                    <path d="M 60 20 C 47 20, 37 30, 37 42 C 37 57, 60 78, 60 78 C 60 78, 83 57, 83 42 C 83 30, 73 20, 60 20 Z" fill="#2563eb" filter="drop-shadow(0 3px 5px rgb(37 99 235 / 0.35))"/>
                                    <circle cx="60" cy="40" r="7" fill="white"/>
                                </svg>
                            </div>

                            <!-- Title -->
                            <h3 class="text-base font-extrabold text-navy-950">
                                Tentukan lokasi
                            </h3>

                            <!-- Description -->
                            <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                                Tunjukkan di mana kejadiannya.
                            </p>
                        </div>
                    </article>

                    <!-- STEP 04: Tambah foto -->
                    <article class="group flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-xs font-black text-white shadow-md shadow-blue-600/30">
                                    04
                                </span>
                            </div>

                            <!-- Illustration: Polaroid Photos & Plus Badge -->
                            <div class="my-3 flex h-24 items-center justify-center sm:h-28">
                                <svg class="h-22 sm:h-24 w-auto overflow-visible" viewBox="0 0 120 100" fill="none" aria-hidden="true">
                                    <!-- Back Photo -->
                                    <g transform="rotate(-8 45 50)">
                                        <rect x="18" y="20" width="54" height="46" rx="5" fill="white" stroke="#cbd5e1" stroke-width="1.2"/>
                                        <rect x="21" y="23" width="48" height="34" rx="3" fill="#e0f2fe"/>
                                        <circle cx="56" cy="32" r="4" fill="#fde047"/>
                                        <path d="M 21 52 L 34 38 L 45 49 L 55 41 L 69 57 L 21 57 Z" fill="#93c5fd"/>
                                    </g>

                                    <!-- Front Photo -->
                                    <g transform="rotate(5 70 50)">
                                        <rect x="42" y="20" width="58" height="50" rx="5" fill="white" stroke="#94a3b8" stroke-width="1.2" filter="drop-shadow(0 3px 6px rgb(0 0 0 / 0.08))"/>
                                        <rect x="45" y="23" width="52" height="38" rx="3" fill="#dbeafe"/>
                                        <circle cx="84" cy="32" r="4.5" fill="#f59e0b"/>
                                        <path d="M 45 56 L 62 40 L 76 53 L 86 45 L 97 61 L 45 61 Z" fill="#3b82f6"/>
                                    </g>

                                    <!-- Plus Badge -->
                                    <circle cx="92" cy="68" r="11" fill="#2563eb" stroke="white" stroke-width="2" filter="drop-shadow(0 2px 5px rgb(37 99 235 / 0.4))"/>
                                    <path d="M 92 62 L 92 74 M 86 68 L 98 68" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <!-- Title -->
                            <h3 class="text-base font-extrabold text-navy-950">
                                Tambah foto
                            </h3>

                            <!-- Description -->
                            <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                                Ada bukti? Tambahkan jika ada.
                            </p>
                        </div>
                    </article>

                    <!-- STEP 05: Pantau laporan -->
                    <article class="group flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-xs font-black text-white shadow-md shadow-blue-600/30">
                                    05
                                </span>
                            </div>

                            <!-- Illustration: Smartphone with Shield, Lock & Checkmark -->
                            <div class="my-3 flex h-24 items-center justify-center sm:h-28">
                                <svg class="h-22 sm:h-24 w-auto overflow-visible" viewBox="0 0 120 100" fill="none" aria-hidden="true">
                                    <!-- Phone Body -->
                                    <rect x="36" y="14" width="48" height="76" rx="8" fill="#1e293b" filter="drop-shadow(0 4px 8px rgb(0 0 0 / 0.1))"/>
                                    <rect x="39" y="18" width="42" height="68" rx="5" fill="#f8fafc"/>
                                    <rect x="53" y="16" width="14" height="1.5" rx="0.75" fill="#64748b"/>

                                    <!-- Blue Shield -->
                                    <path d="M 60 30 C 69 30, 73 27, 73 27 C 73 45, 60 57, 60 57 C 60 57, 47 45, 47 27 C 47 27, 51 30, 60 30 Z" fill="#2563eb" filter="drop-shadow(0 2px 4px rgb(37 99 235 / 0.3))"/>

                                    <!-- Padlock -->
                                    <rect x="56" y="39" width="8" height="7" rx="1.5" fill="white"/>
                                    <path d="M 57.5 39 V 36 A 2.5 2.5 0 0 1 62.5 36 V 39" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none"/>

                                    <!-- Checkmark Badge -->
                                    <circle cx="88" cy="62" r="11" fill="#22c55e" stroke="white" stroke-width="2" filter="drop-shadow(0 2px 5px rgb(34 197 94 / 0.4))"/>
                                    <path d="M 83 62 L 87 66 L 93 58" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                </svg>
                            </div>

                            <!-- Title -->
                            <h3 class="text-base font-extrabold text-navy-950">
                                Pantau laporan
                            </h3>

                            <!-- Description -->
                            <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                                Simpan kode untuk melihat perkembangan laporan.
                            </p>
                        </div>
                    </article>

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
