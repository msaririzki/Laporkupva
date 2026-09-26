<x-layouts.public title="Lapor KUPVA secara aman">
    <section class="relative overflow-hidden bg-[#021b44] text-white bg-cover bg-center lg:bg-[center_top]" style="background-image: url('{{ asset('images/hero-bg.jpg') }}');">
        <!-- Soft Ambient Overlay for Text Contrast -->
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-navy-950/80 via-navy-950/50 to-transparent lg:from-navy-950/60 lg:via-transparent lg:to-transparent" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-0 bg-slate-950/40 lg:hidden" aria-hidden="true"></div>

        <div class="public-container relative grid min-h-[520px] lg:min-h-[560px] items-center gap-8 py-12 sm:py-16 lg:grid-cols-[1.18fr_0.82fr] lg:gap-10 lg:py-16">
            <!-- Left Column Content -->
            <div class="relative z-10">
                <!-- Eyebrow Badge -->
                <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-slate-900/35 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-white shadow-xs backdrop-blur-md">
                    <span class="size-1.5 rounded-full bg-emerald-400"></span>
                    <span>KANAL PELAPORAN RESMI WILAYAH NTB</span>
                </div>

                <!-- Headline (Refined Minimalist Size) -->
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-[2.75rem] lg:leading-[1.14]">
                    Berani melapor,<br>
                    <span class="text-[#2dd4bf]">untuk NTB yang<br>lebih baik.</span>
                </h1>

                <!-- Subtitle -->
                <p class="mt-3.5 max-w-lg text-xs leading-relaxed text-blue-50/85 sm:text-sm lg:text-[14.5px] font-normal">
                    Laporkan dugaan kegiatan penukaran valuta asing (money changer) yang tidak sesuai ketentuan. Identitas Anda tidak diminta dan proses penanganannya dapat dipantau secara aman.
                </p>

                <!-- CTA Buttons (Compact & Sleek) -->
                <div class="mt-6 flex flex-wrap items-center gap-2.5 sm:gap-3.5">
                    <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-white shadow-md shadow-blue-900/25 hover:bg-blue-500 transition-all">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m22 2-7 20-4-9-9-4Z"/>
                            <path d="M22 2 11 13"/>
                        </svg>
                        <span>Buat laporan anonim</span>
                        <span aria-hidden="true" class="text-sm sm:text-base">→</span>
                    </a>
                    <a href="{{ route('reports.track') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-white px-5 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-navy-950 shadow-sm hover:bg-slate-50 transition-all">
                        <svg class="size-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                        <span>Cek status laporan</span>
                    </a>
                </div>

                <!-- 3 Key Features Row (Minimalist Scale) -->
                <div class="mt-8 sm:mt-10 flex flex-wrap items-center gap-5 sm:gap-7 lg:gap-8">
                    <!-- Feature 1 -->
                    <div class="flex items-center gap-2.5">
                        <div class="flex size-8 sm:size-9 shrink-0 items-center justify-center rounded-full bg-blue-600/90 text-white shadow-xs">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs sm:text-[13px] font-bold text-white">Tanpa nama &amp; NIK</p>
                            <p class="text-[10px] sm:text-[11px] text-blue-100/70 mt-0.5">Identitas Anda terlindungi</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-center gap-2.5">
                        <div class="flex size-8 sm:size-9 shrink-0 items-center justify-center rounded-full bg-blue-600/90 text-white shadow-xs">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs sm:text-[13px] font-bold text-white">Lokasi akurat</p>
                            <p class="text-[10px] sm:text-[11px] text-blue-100/70 mt-0.5">Gunakan GPS atau cari lokasi</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-center gap-2.5">
                        <div class="flex size-8 sm:size-9 shrink-0 items-center justify-center rounded-full bg-blue-600/90 text-white shadow-xs">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 20V10"/>
                                <path d="M12 20V4"/>
                                <path d="M6 20v-6"/>
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs sm:text-[13px] font-bold text-white">Proses transparan</p>
                            <p class="text-[10px] sm:text-[11px] text-blue-100/70 mt-0.5">Pantau perkembangan laporan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Alur Penanganan Floating Card (Compact & Minimalist) -->
            <div class="relative z-10 mx-auto w-full max-w-[390px] lg:ml-auto">
                <div class="rounded-2xl sm:rounded-3xl bg-white/95 p-5 sm:p-6 shadow-xl backdrop-blur-md border border-white/70">
                    <!-- Card Header -->
                    <div class="flex items-start justify-between pb-1">
                        <div>
                            <p class="text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider text-blue-600">Alur penanganan</p>
                            <h2 class="mt-0.5 text-sm sm:text-base font-black text-navy-950">Laporan Anda terus bergerak</h2>
                        </div>
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 border border-emerald-200/70">
                            <svg class="size-3 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Terlindungi</span>
                        </span>
                    </div>

                    <!-- Steps Timeline -->
                    <div class="relative mt-4 space-y-3">
                        <div class="absolute left-3 top-2.5 bottom-3.5 w-0.5 bg-slate-100" aria-hidden="true"></div>

                        @php
                            $timelineSteps = [
                                ['title' => 'Laporan dikirim', 'desc' => 'Data tersimpan dengan aman', 'completed' => true, 'step' => 1],
                                ['title' => 'Laporan diterima', 'desc' => 'Pemeriksaan awal oleh petugas', 'completed' => true, 'step' => 2],
                                ['title' => 'Koordinasi dengan APH', 'desc' => 'Koordinasi penanganan', 'completed' => false, 'step' => 3],
                                ['title' => 'Kunjungan lapangan', 'desc' => 'Verifikasi atau penertiban', 'completed' => false, 'step' => 4],
                                ['title' => 'Laporan hasil', 'desc' => 'Hasil penanganan tersedia', 'completed' => false, 'step' => 5],
                                ['title' => 'Selesai', 'desc' => 'Proses telah dituntaskan', 'completed' => false, 'step' => 6],
                            ];
                        @endphp

                        @foreach ($timelineSteps as $index => $step)
                            <div class="relative flex items-start gap-3">
                                @if ($step['completed'])
                                    <span class="relative z-10 flex size-6 sm:size-6.5 shrink-0 items-center justify-center rounded-full bg-[#06b6d4] text-white shadow-2xs">
                                        <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                @else
                                    <span class="relative z-10 flex size-6 sm:size-6.5 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-900 font-extrabold text-[11px]">
                                        {{ $step['step'] }}
                                    </span>
                                @endif

                                <div class="min-w-0 pt-0.5">
                                    <p class="text-xs sm:text-[13px] font-bold text-slate-800 leading-snug">{{ $step['title'] }}</p>
                                    <p class="text-[10px] sm:text-[11px] text-slate-500 leading-tight mt-0.5">{{ $step['desc'] }}</p>
                                </div>
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

    <section id="keamanan" class="relative overflow-hidden scroll-mt-20 bg-gradient-to-b from-[#eaf4fe] via-[#f3f8fe] to-[#e4f0fd] py-6 sm:py-16">
        <!-- Mountain Silhouette Background at Bottom (TAMBORA Atmosphere) -->
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-20 sm:h-36 overflow-hidden opacity-30" aria-hidden="true">
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
            <div class="absolute top-8 left-6 size-24 sm:size-32 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
            <div class="absolute top-8 right-6 size-24 sm:size-32 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
        </div>

        <div class="public-container">
            <div class="grid items-center gap-6 sm:gap-10 lg:grid-cols-12 lg:gap-12">
                <!-- Left Column: Grid Kartu (Compact di Mobile, 2 Kolom di Desktop/Tablet) -->
                <div class="order-2 lg:order-1 lg:col-span-7 w-full max-w-md sm:max-w-none mx-auto lg:mx-0">
                    <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 sm:gap-4">
                        <!-- Card 1: 100% Anonim & Tanpa Akun -->
                        <article class="group flex flex-col justify-between rounded-xl sm:rounded-3xl border border-slate-100 bg-white p-3 sm:p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-md">
                            <div class="flex items-start sm:flex-col sm:items-center gap-3 sm:gap-0 text-left sm:text-center">
                                <!-- Icon: Identitas / Profil Tersembunyi (Anonim) -->
                                <div class="flex size-9 sm:size-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white sm:mx-auto sm:mb-3">
                                    <svg class="size-4.5 sm:size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <line x1="2" y1="2" x2="22" y2="22" />
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs sm:text-base font-extrabold text-navy-950">
                                        100% Anonim & Tanpa Akun
                                    </h3>
                                    <p class="mt-0.5 sm:mt-1.5 text-[11px] sm:text-sm text-slate-500 leading-snug sm:leading-relaxed">
                                        Tidak perlu daftar akun. Kami sama sekali tidak meminta nama, KTP, nomor HP, maupun email Anda.
                                    </p>
                                </div>
                            </div>
                        </article>

                        <!-- Card 2: Pantau Pakai Kode Rahasia -->
                        <article class="group flex flex-col justify-between rounded-xl sm:rounded-3xl border border-slate-100 bg-white p-3 sm:p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-md">
                            <div class="flex items-start sm:flex-col sm:items-center gap-3 sm:gap-0 text-left sm:text-center">
                                <!-- Icon: Kunci Akses / PIN Pelacakan Rahasia -->
                                <div class="flex size-9 sm:size-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white sm:mx-auto sm:mb-3">
                                    <svg class="size-4.5 sm:size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="7.5" cy="15.5" r="4.5" />
                                        <path d="m21 3-9.5 9.5" />
                                        <path d="m15.5 7.5 3 3" />
                                        <path d="m18 5 2 2" />
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs sm:text-base font-extrabold text-navy-950">
                                        Pantau Pakai Kode Rahasia
                                    </h3>
                                    <p class="mt-0.5 sm:mt-1.5 text-[11px] sm:text-sm text-slate-500 leading-snug sm:leading-relaxed">
                                        Anda mendapatkan kode acak dan PIN untuk mengecek status tindak lanjut tanpa meninggalkan jejak.
                                    </p>
                                </div>
                            </div>
                        </article>

                        <!-- Card 3: Hanya Dibaca Petugas Resmi -->
                        <article class="group flex flex-col justify-between rounded-xl sm:rounded-3xl border border-slate-100 bg-white p-3 sm:p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-md">
                            <div class="flex items-start sm:flex-col sm:items-center gap-3 sm:gap-0 text-left sm:text-center">
                                <!-- Icon: Perisai Verifikasi / Otoritas Resmi BI -->
                                <div class="flex size-9 sm:size-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white sm:mx-auto sm:mb-3">
                                    <svg class="size-4.5 sm:size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                        <path d="m9 12 2 2 4-4" />
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs sm:text-base font-extrabold text-navy-950">
                                        Hanya Dibaca Petugas Resmi
                                    </h3>
                                    <p class="mt-0.5 sm:mt-1.5 text-[11px] sm:text-sm text-slate-500 leading-snug sm:leading-relaxed">
                                        Laporan Anda dijaga ketat dan hanya dibuka oleh petugas resmi Bank Indonesia, tidak ke umum.
                                    </p>
                                </div>
                            </div>
                        </article>

                        <!-- Card 4: Mulai Membuat Laporan (Teman Grip 03) -->
                        <article class="group flex flex-col justify-between rounded-xl sm:rounded-3xl border border-blue-200/80 bg-gradient-to-b from-blue-50/60 via-white to-blue-50/30 p-3 sm:p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
                            <div class="flex items-start sm:flex-col sm:items-center gap-3 sm:gap-0 text-left sm:text-center">
                                <!-- Icon: Pesawat Kertas Kirim Laporan -->
                                <div class="flex size-9 sm:size-12 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-600/25 transition-transform group-hover:scale-105 sm:mx-auto sm:mb-3">
                                    <svg class="size-4.5 sm:size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m22 2-7 20-4-9-9-4Z" />
                                        <path d="M22 2 11 13" />
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs sm:text-base font-extrabold text-navy-950">
                                        Mulai Buat Laporan
                                    </h3>
                                    <p class="mt-0.5 sm:mt-1.5 text-[11px] sm:text-sm text-slate-500 leading-snug sm:leading-relaxed">
                                        Sampaikan laporan KUPVA sekarang secara anonim, cepat, dan terlindungi.
                                    </p>
                                </div>
                            </div>

                            <!-- Button Teman Grip 03 -->
                            <div class="mt-2.5 sm:mt-4 w-full">
                                <a href="{{ route('reports.create') }}" class="button-primary w-full inline-flex items-center justify-center gap-1.5 py-1.5 sm:py-2 px-3 text-xs sm:text-sm font-bold shadow-sm">
                                    Mulai membuat laporan <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    </div>
                </div>

                <!-- Right Column: Gambar di Atas & Judul di Bawah Gambar -->
                <div class="order-1 lg:order-2 lg:col-span-5 flex flex-col items-center lg:items-start text-center lg:text-left">
                    <!-- Gambar Ilustrasi Keamanan (Kompak di Mobile) -->
                    <div class="relative mx-auto lg:mx-0 flex w-full max-w-[140px] sm:max-w-[240px] lg:max-w-[320px] items-center justify-center">
                        <!-- Glow Accent Behind Image -->
                        <div class="absolute inset-0 -z-10 rounded-full bg-gradient-to-tr from-blue-300/25 via-sky-200/30 to-blue-100/20 blur-xl sm:blur-2xl"></div>
                        <img
                            src="{{ asset('images/illustrations/security-shield.png') }}"
                            alt="Ilustrasi Keamanan dan Privasi Pelapor TAMBORA"
                            class="h-auto w-full object-contain drop-shadow-md transition-transform duration-500 hover:scale-[1.02]"
                            width="680"
                            height="382"
                            loading="lazy"
                        >
                    </div>

                    <!-- Teks di Bawah Gambar -->
                    <div class="mt-2.5 sm:mt-5">
                        <h2 class="text-xl font-extrabold tracking-tight text-navy-950 sm:text-3xl lg:text-[2.25rem] lg:leading-tight">
                            Lapor dengan tenang,<br class="hidden sm:inline"> identitas tetap aman.
                        </h2>
                        <p class="mt-1.5 sm:mt-2.5 text-xs sm:text-base text-slate-600 leading-relaxed max-w-md mx-auto lg:mx-0">
                            TAMBORA dirancang agar masyarakat dapat berpartisipasi mengawasi KUPVA tidak berizin di NTB tanpa rasa khawatir. Sistem hanya mengumpulkan data kejadian yang diperlukan tanpa melacak identitas pelapor.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-blue-700 py-14 text-white">
        <div class="public-container flex flex-col items-start justify-between gap-7 md:flex-row md:items-center"><div><p class="text-sm font-bold uppercase tracking-[.18em] text-blue-200">Sudah pernah melapor?</p><h2 class="mt-2 text-2xl font-extrabold sm:text-3xl">Lihat progres penanganan laporan Anda.</h2></div><a href="{{ route('reports.track') }}" class="button-light shrink-0">Cek status sekarang</a></div>
    </section>
</x-layouts.public>
