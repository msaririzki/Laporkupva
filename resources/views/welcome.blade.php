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

    <section id="cara-kerja" class="relative overflow-hidden scroll-mt-20 bg-gradient-to-b from-[#eef6ff] via-[#f4f9ff] to-[#e8f2fe] py-8 sm:py-16">
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
                <h2 class="text-2xl font-extrabold tracking-tight text-navy-950 sm:text-4xl">
                    Cara lapor di TAMBORA
                </h2>
                <p class="mt-1.5 text-xs sm:text-base text-slate-500 max-w-xl mx-auto leading-relaxed">
                    Gampang, kok. Cukup ikuti 4 langkah sederhana berikut tanpa perlu login atau registrasi akun.
                </p>
            </div>

            <!-- 4-Step Cards Grid: 2x2 on Mobile, 4 Columns on Desktop (Minimal Scroll) -->
            <div class="mt-6 sm:mt-10">
                <div class="grid grid-cols-2 gap-2.5 sm:gap-4 lg:grid-cols-4">

                    <!-- STEP 01: Buka TAMBORA -->
                    <article class="group flex flex-col justify-between rounded-2xl sm:rounded-3xl border border-slate-100 bg-white p-3.5 sm:p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-7 sm:size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-[11px] sm:text-xs font-black text-white shadow-sm sm:shadow-md shadow-blue-600/30">
                                    01
                                </span>
                            </div>

                            <!-- Illustration: Laptop / Web Browser -->
                            <div class="my-2 sm:my-3 flex h-14 sm:h-20 lg:h-24 items-center justify-center">
                                <svg class="h-full w-auto overflow-visible" viewBox="0 0 120 100" fill="none" aria-hidden="true">
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
                            <h3 class="text-xs sm:text-sm lg:text-base font-extrabold text-navy-950">
                                Buka TAMBORA
                            </h3>

                            <!-- Description -->
                            <p class="mt-1 text-[11px] sm:text-xs text-slate-500 leading-snug sm:leading-relaxed">
                                Buka <a href="https://laporkupva.ikydev.com/" target="_blank" rel="noopener noreferrer" class="font-bold text-blue-700 underline decoration-blue-300 hover:text-blue-900 transition-colors">TAMBORA</a> tanpa perlu login.
                            </p>
                        </div>
                    </article>

                    <!-- STEP 02: Ceritakan -->
                    <article class="group flex flex-col justify-between rounded-2xl sm:rounded-3xl border border-slate-100 bg-white p-3.5 sm:p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-7 sm:size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-[11px] sm:text-xs font-black text-white shadow-sm sm:shadow-md shadow-blue-600/30">
                                    02
                                </span>
                            </div>

                            <!-- Illustration: Character with Phone & Speech Bubble -->
                            <div class="my-2 sm:my-3 flex h-14 sm:h-20 lg:h-24 items-center justify-center">
                                <svg class="h-full w-auto overflow-visible" viewBox="0 0 120 100" fill="none" aria-hidden="true">
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
                            <h3 class="text-xs sm:text-sm lg:text-base font-extrabold text-navy-950">
                                Ceritakan
                            </h3>

                            <!-- Description -->
                            <p class="mt-1 text-[11px] sm:text-xs text-slate-500 leading-snug sm:leading-relaxed">
                                Ceritakan apa yang terjadi.
                            </p>
                        </div>
                    </article>

                    <!-- STEP 03: Tentukan lokasi -->
                    <article class="group flex flex-col justify-between rounded-2xl sm:rounded-3xl border border-slate-100 bg-white p-3.5 sm:p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-7 sm:size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-[11px] sm:text-xs font-black text-white shadow-sm sm:shadow-md shadow-blue-600/30">
                                    03
                                </span>
                            </div>

                            <!-- Illustration: Folded Map & Blue Pin -->
                            <div class="my-2 sm:my-3 flex h-14 sm:h-20 lg:h-24 items-center justify-center">
                                <svg class="h-full w-auto overflow-visible" viewBox="0 0 120 100" fill="none" aria-hidden="true">
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
                            <h3 class="text-xs sm:text-sm lg:text-base font-extrabold text-navy-950">
                                Tentukan lokasi
                            </h3>

                            <!-- Description -->
                            <p class="mt-1 text-[11px] sm:text-xs text-slate-500 leading-snug sm:leading-relaxed">
                                Tunjukkan di mana kejadiannya.
                            </p>
                        </div>
                    </article>

                    <!-- STEP 04: Pantau laporan -->
                    <article class="group flex flex-col justify-between rounded-2xl sm:rounded-3xl border border-slate-100 bg-white p-3.5 sm:p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-7 sm:size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-[11px] sm:text-xs font-black text-white shadow-sm sm:shadow-md shadow-blue-600/30">
                                    04
                                </span>
                            </div>

                            <!-- Illustration: Smartphone with Shield, Lock & Checkmark -->
                            <div class="my-2 sm:my-3 flex h-14 sm:h-20 lg:h-24 items-center justify-center">
                                <svg class="h-full w-auto overflow-visible" viewBox="0 0 120 100" fill="none" aria-hidden="true">
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
                            <h3 class="text-xs sm:text-sm lg:text-base font-extrabold text-navy-950">
                                Pantau laporan
                            </h3>

                            <!-- Description -->
                            <p class="mt-1 text-[11px] sm:text-xs text-slate-500 leading-snug sm:leading-relaxed">
                                Simpan kode untuk melihat perkembangan laporan.
                            </p>
                        </div>
                    </article>

                </div>
            </div>
        </div>
    </section>

    <section id="keamanan" class="relative overflow-hidden scroll-mt-20 bg-gradient-to-b from-[#eaf4fe] via-[#f3f8fe] to-[#e4f0fd] py-12 sm:py-20">
        <!-- Mountain Silhouette Background at Bottom (TAMBORA Atmosphere) -->
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-28 sm:h-44 overflow-hidden opacity-35" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1440 240" fill="none" preserveAspectRatio="none">
                <path d="M0 240 L0 150 L140 130 L280 180 L440 100 L590 160 L740 60 L890 150 L1040 85 L1190 140 L1340 75 L1440 120 L1440 240 Z" fill="url(#keamanan-mountains-grad)" />
                <defs>
                    <linearGradient id="keamanan-mountains-grad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#93c5fd" stop-opacity="0.5"/>
                        <stop offset="100%" stop-color="#2563eb" stop-opacity="0.8"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <!-- Subtle Dot Grid Patterns on Sides -->
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute top-10 left-8 size-32 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
            <div class="absolute top-10 right-8 size-32 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
        </div>

        <div class="public-container">
            <!-- Top Row: Heading on Left & Friendly Illustration with Floating Badges on Right -->
            <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-12">
                <!-- Left Content -->
                <div class="text-center lg:col-span-7 lg:text-left">
                    <!-- Badge Pill with Lock Icon -->
                    <div class="inline-flex items-center gap-2 rounded-full bg-blue-100/90 px-3.5 py-1.5 text-xs font-extrabold uppercase tracking-wider text-blue-700">
                        <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                        </svg>
                        KEAMANAN
                    </div>

                    <!-- Main Title -->
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-navy-950 sm:text-4xl lg:text-[2.65rem] lg:leading-[1.18]">
                        Lapor dengan tenang,<br class="hidden sm:inline"> identitas tetap aman.
                    </h2>

                    <!-- Subtitle / Explanation -->
                    <p class="mt-3.5 text-sm sm:text-base text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        TAMBORA tidak meminta data pribadi untuk membuat laporan. Kamu tetap bisa menyampaikan informasi dan memantau perkembangannya dengan kode dan PIN rahasia.
                    </p>
                </div>

                <!-- Right Illustration with Floating Trust Badges -->
                <div class="lg:col-span-5">
                    <div class="relative mx-auto flex w-full max-w-[380px] sm:max-w-[420px] items-center justify-center py-4">
                        <!-- Background Glow Accent -->
                        <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-blue-300/30 to-sky-200/40 blur-2xl -z-10"></div>

                        <!-- Main Graphic: Smartphone with Security Shield & Reporter Character -->
                        <svg class="h-64 sm:h-76 w-auto overflow-visible drop-shadow-md" viewBox="0 0 380 320" fill="none" aria-hidden="true">
                            <!-- Smartphone Body -->
                            <rect x="150" y="24" width="160" height="264" rx="22" fill="#1e293b"/>
                            <!-- Smartphone Screen -->
                            <rect x="157" y="32" width="146" height="248" rx="16" fill="#f8fafc"/>
                            <!-- Smartphone Speaker Notch -->
                            <rect x="205" y="38" width="50" height="4" rx="2" fill="#94a3b8"/>

                            <!-- Smartphone Screen Content: Shield & Padlock -->
                            <g>
                                <!-- Blue Shield -->
                                <path d="M 230 92 C 255 92, 266 82, 266 82 C 266 134, 230 162, 230 162 C 230 162, 194 134, 194 82 C 194 82, 205 92, 230 92 Z" fill="#2563eb" filter="drop-shadow(0 4px 8px rgb(37 99 235 / 0.35))"/>
                                <!-- White Padlock on Shield -->
                                <rect x="222" y="117" width="16" height="13" rx="2.5" fill="white"/>
                                <path d="M 225.5 117 V 110 A 4.5 4.5 0 0 1 234.5 110 V 117" stroke="white" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                                <circle cx="230" cy="123" r="1.5" fill="#2563eb"/>

                                <!-- Checkmark badge on screen -->
                                <circle cx="270" cy="180" r="12" fill="#93c5fd" opacity="0.45"/>
                                <path d="M 265 180 L 268 183 L 275 176" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>

                                <!-- Content lines on screen -->
                                <rect x="194" y="206" width="72" height="3" rx="1.5" fill="#cbd5e1"/>
                                <rect x="194" y="214" width="48" height="3" rx="1.5" fill="#e2e8f0"/>
                            </g>

                            <!-- Friendly Reporter Character (in front of smartphone) -->
                            <g>
                                <!-- Body / Blue Top -->
                                <path d="M 85 195 C 65 195, 45 225, 35 290 L 175 290 C 165 225, 145 195, 125 195 Z" fill="#3b82f6"/>
                                <!-- Neck & Collar -->
                                <path d="M 98 175 L 116 175 L 116 200 L 98 200 Z" fill="#fed7aa"/>
                                <path d="M 95 195 L 107 208 L 119 195" stroke="#1d4ed8" stroke-width="2" fill="none"/>
                                <!-- Head -->
                                <ellipse cx="107" cy="145" rx="28" ry="32" fill="#fed7aa"/>
                                <!-- Ear -->
                                <circle cx="80" cy="146" r="6" fill="#fecaca"/>
                                <!-- Hair -->
                                <path d="M 80 140 C 76 104, 102 95, 128 104 C 146 111, 150 135, 142 162 C 137 140, 124 126, 107 126 C 89 126, 82 135, 80 148 Z" fill="#1e293b"/>
                                <path d="M 79 138 C 70 165, 70 200, 65 225 C 74 216, 79 190, 84 172 Z" fill="#1e293b"/>
                                <!-- Eyes & Friendly Smile -->
                                <ellipse cx="102" cy="145" rx="2.2" ry="3" fill="#0f172a"/>
                                <ellipse cx="122" cy="145" rx="2.2" ry="3" fill="#0f172a"/>
                                <path d="M 108 156 Q 112 161 116 156" stroke="#0f172a" stroke-width="1.8" stroke-linecap="round" fill="none"/>
                                <!-- Cheeks blush -->
                                <circle cx="97" cy="152" r="4" fill="#fca5a5" opacity="0.6"/>
                                <circle cx="127" cy="152" r="4" fill="#fca5a5" opacity="0.6"/>

                                <!-- Hand holding small phone -->
                                <rect x="124" y="172" width="26" height="42" rx="5" fill="#1e293b" transform="rotate(-15 124 172)"/>
                                <rect x="127" y="175" width="20" height="34" rx="3" fill="#38bdf8" transform="rotate(-15 124 172)"/>
                                <circle cx="122" cy="200" r="7" fill="#fed7aa"/>
                                <circle cx="138" cy="208" r="6" fill="#fed7aa"/>
                            </g>

                            <!-- Subtle Sparkles & Rays -->
                            <path d="M 330 65 L 340 60 M 346 72 L 354 75 M 338 84 L 345 92" stroke="#93c5fd" stroke-width="2" stroke-linecap="round"/>
                        </svg>

                        <!-- Floating Badge 1: Top-Left (Anonim tanpa identitas) -->
                        <div class="absolute -top-1 left-0 sm:top-2 sm:-left-4 flex items-center gap-2.5 rounded-2xl border border-white/90 bg-white/95 px-3 py-2 shadow-lg shadow-blue-900/10 backdrop-blur-md">
                            <div class="flex size-7 sm:size-8 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                <svg class="size-4 sm:size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                    <line x1="2" y1="2" x2="22" y2="22" />
                                </svg>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-extrabold text-navy-950 leading-tight">Anonim</div>
                                <div class="text-[10px] text-slate-500">tanpa identitas</div>
                            </div>
                        </div>

                        <!-- Floating Badge 2: Top-Right (Data laporan tetap aman) -->
                        <div class="absolute top-12 right-0 sm:top-14 sm:-right-4 flex items-center gap-2.5 rounded-2xl border border-white/90 bg-white/95 px-3 py-2 shadow-lg shadow-blue-900/10 backdrop-blur-md">
                            <div class="flex size-7 sm:size-8 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                <svg class="size-4 sm:size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <rect x="8" y="13" width="8" height="6" rx="1" fill="currentColor" fill-opacity="0.15" />
                                    <path d="M10 13v-1.5a2 2 0 1 1 4 0V13" />
                                </svg>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-extrabold text-navy-950 leading-tight">Data laporan</div>
                                <div class="text-[10px] text-slate-500">tetap aman</div>
                            </div>
                        </div>

                        <!-- Floating Badge 3: Bottom-Right (Akses dengan kode dan PIN) -->
                        <div class="absolute bottom-5 right-0 sm:bottom-7 sm:-right-2 flex items-center gap-2.5 rounded-2xl border border-white/90 bg-white/95 px-3 py-2 shadow-lg shadow-blue-900/10 backdrop-blur-md">
                            <div class="flex size-7 sm:size-8 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                <svg class="size-4 sm:size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="7.5" cy="15.5" r="4.5" />
                                    <path d="m21 3-9.5 9.5" />
                                    <path d="m15.5 7.5 3 3" />
                                    <path d="m18 5 2 2" />
                                </svg>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-extrabold text-navy-950 leading-tight">Akses dengan</div>
                                <div class="text-[10px] text-slate-500">kode dan PIN</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: 3 Security Cards (Sesuai Referensi Gambar) -->
            <div class="mt-10 sm:mt-14 grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-6">
                <!-- Card 1: Tidak perlu identitas pribadi -->
                <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-7 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                    <div class="flex size-12 sm:size-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                        <svg class="size-6 sm:size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <line x1="2" y1="2" x2="22" y2="22" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-base sm:text-lg font-extrabold text-navy-950">
                        Tidak perlu identitas pribadi
                    </h3>
                    <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Kamu tidak perlu memberikan nama, NIK, nomor telepon, atau alamat untuk melapor.
                    </p>
                    <div class="mt-5 h-1.5 w-7 rounded-full bg-blue-600"></div>
                </article>

                <!-- Card 2: Laporan tetap privat -->
                <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-7 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                    <div class="flex size-12 sm:size-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                        <svg class="size-6 sm:size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <rect x="8" y="13" width="8" height="6" rx="1" fill="currentColor" fill-opacity="0.15" />
                            <path d="M10 13v-1.5a2 2 0 1 1 4 0V13" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-base sm:text-lg font-extrabold text-navy-950">
                        Laporan tetap privat
                    </h3>
                    <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Laporan dan bukti yang kamu kirim tidak ditampilkan kepada masyarakat umum.
                    </p>
                    <div class="mt-5 h-1.5 w-7 rounded-full bg-blue-600"></div>
                </article>

                <!-- Card 3: Simpan kode dan PIN -->
                <article class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-7 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                    <div class="flex size-12 sm:size-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                        <svg class="size-6 sm:size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="7.5" cy="15.5" r="4.5" />
                            <path d="m21 3-9.5 9.5" />
                            <path d="m15.5 7.5 3 3" />
                            <path d="m18 5 2 2" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-base sm:text-lg font-extrabold text-navy-950">
                        Simpan kode dan PIN
                    </h3>
                    <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Setelah laporan dikirim, kamu akan mendapat kode laporan dan PIN untuk memantau perkembangannya.
                    </p>
                    <div class="mt-5 h-1.5 w-7 rounded-full bg-blue-600"></div>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-blue-700 py-14 text-white">
        <div class="public-container flex flex-col items-start justify-between gap-7 md:flex-row md:items-center"><div><p class="text-sm font-bold uppercase tracking-[.18em] text-blue-200">Sudah pernah melapor?</p><h2 class="mt-2 text-2xl font-extrabold sm:text-3xl">Lihat progres penanganan laporan Anda.</h2></div><a href="{{ route('reports.track') }}" class="button-light shrink-0">Cek status sekarang</a></div>
    </section>
</x-layouts.public>
