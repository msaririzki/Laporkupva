<x-layouts.public title="Lapor KUPVA secara aman">
    <section class="relative overflow-hidden bg-gradient-to-b from-[#f0f7ff] via-[#f8fbff] to-[#eaf4fe] pt-4 pb-8 sm:pt-6 sm:pb-12 lg:pt-6 lg:pb-12 text-navy-950">
        <!-- Ambient Decorative Glows & Dot Patterns (Consistent with Cara Lapor & Keamanan) -->
        <div class="pointer-events-none absolute -top-24 -left-20 size-96 rounded-full bg-blue-400/15 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute top-1/3 -right-20 size-96 rounded-full bg-sky-300/20 blur-3xl" aria-hidden="true"></div>
        
        <!-- Subtle Dot Grid Patterns on Sides -->
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute top-8 right-10 size-28 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
            <div class="absolute bottom-8 left-10 size-28 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
        </div>

        <div class="public-container relative grid items-center gap-8 lg:grid-cols-2 lg:gap-10 max-w-6xl mx-auto">
            <!-- Left Column: Typography & Actions -->
            <div class="relative z-10 flex flex-col items-start text-left">
                <!-- Headline with Harmonious Navy and Blue Accents -->
                <h1 class="text-2xl sm:text-3xl lg:text-4xl xl:text-[2.65rem] font-black tracking-tight text-navy-950 leading-[1.18]">
                    Berani melapor,<br>
                    <span class="text-blue-600">untuk NTB yang lebih baik.</span>
                </h1>

                <!-- Subtitle -->
                <p class="mt-3 sm:mt-3.5 max-w-xl text-xs sm:text-sm lg:text-[14.5px] leading-relaxed text-slate-600 font-normal">
                    Bantu menjaga aktivitas penukaran valuta asing di NTB tetap aman dan sesuai aturan. Sampaikan laporan Anda tanpa mengungkap identitas.
                </p>

                <!-- CTA Buttons -->
                <div class="mt-5 sm:mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 w-full sm:w-auto">
                    <a href="{{ route('reports.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-[#2563EB] px-5 py-3 text-xs sm:text-sm font-bold text-white shadow-md shadow-blue-600/25 hover:bg-[#1D4ED8] hover:shadow-lg hover:shadow-blue-600/35 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.99] transition-all">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m22 2-7 20-4-9-9-4Z"/>
                            <path d="M22 2 11 13"/>
                        </svg>
                        <span>Buat laporan</span>
                        <span aria-hidden="true" class="text-base sm:text-lg">→</span>
                    </a>
                    <a href="{{ route('reports.track') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200/90 bg-white px-5 py-3 text-xs sm:text-sm font-bold text-navy-950 shadow-2xs hover:bg-blue-50/70 hover:border-blue-300 hover:text-blue-700 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.99] transition-all">
                        <svg class="size-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                        <span>Cek status laporan</span>
                    </a>
                </div>
            </div>

            <!-- Right Column: Alur Penanganan Floating Card -->
            <div class="relative z-10 mx-auto w-full max-w-[390px]">
                <div class="relative rounded-2xl sm:rounded-3xl bg-white p-4 sm:p-5 shadow-lg shadow-blue-900/5 backdrop-blur-md border border-slate-200/90 ring-1 ring-slate-900/5">
                    <!-- Subtle Corner Ambient Light -->
                    <div class="pointer-events-none absolute -top-8 -right-8 size-28 rounded-full bg-blue-500/10 blur-xl" aria-hidden="true"></div>

                    <!-- Card Header -->
                    <div class="relative flex items-start justify-between pb-2 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="relative flex size-2">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex size-2 rounded-full bg-blue-600"></span>
                                </span>
                                <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#2563EB]">Contoh alur penanganan</p>
                            </div>
                            <h2 class="mt-0.5 text-xs sm:text-[13px] font-black text-navy-950">Laporan Anda terus bergerak</h2>
                        </div>
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-[#2E9B68] border border-emerald-200/80 shadow-2xs">
                            <svg class="size-3 text-[#2E9B68]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            <span>Terlindungi</span>
                        </span>
                    </div>

                    <!-- Mini Progress Summary -->
                    <div class="mt-2.5 rounded-lg bg-slate-50/90 px-2.5 py-1.5 border border-slate-100/90">
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="font-medium text-slate-500">Transparansi Penanganan</span>
                            <span class="font-bold text-blue-600">Contoh: tahap 2 dari 6 selesai</span>
                        </div>
                        <div class="mt-1 h-1.5 w-full bg-slate-200/80 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-500 to-blue-600 rounded-full w-2/6"></div>
                        </div>
                    </div>

                    <!-- Steps Micro-Tiles List (Compact without description) -->
                    <div class="relative mt-2.5 space-y-1.5">
                        @php
                            $timelineSteps = [
                                [
                                    'step' => 1,
                                    'title' => 'Laporan dikirim',
                                    'status' => 'completed',
                                    'statusLabel' => 'Selesai',
                                ],
                                [
                                    'step' => 2,
                                    'title' => 'Laporan diterima',
                                    'status' => 'completed',
                                    'statusLabel' => 'Selesai',
                                ],
                                [
                                    'step' => 3,
                                    'title' => 'Koordinasi dengan APH',
                                    'status' => 'active',
                                    'statusLabel' => 'Proses',
                                ],
                                [
                                    'step' => 4,
                                    'title' => 'Kunjungan lapangan',
                                    'status' => 'upcoming',
                                    'statusLabel' => 'Tahap 4',
                                ],
                                [
                                    'step' => 5,
                                    'title' => 'Laporan hasil',
                                    'status' => 'upcoming',
                                    'statusLabel' => 'Tahap 5',
                                ],
                                [
                                    'step' => 6,
                                    'title' => 'Selesai',
                                    'status' => 'upcoming',
                                    'statusLabel' => 'Tahap 6',
                                ],
                            ];
                        @endphp

                        @foreach ($timelineSteps as $step)
                            @if ($step['status'] === 'completed')
                                <div class="group relative flex items-center justify-between gap-2.5 rounded-lg border border-emerald-100 bg-emerald-50/40 px-2.5 py-1.5 transition-all duration-200 hover:bg-emerald-50/70 hover:shadow-2xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="flex size-5 shrink-0 items-center justify-center rounded-md bg-emerald-600 text-white shadow-xs">
                                            <svg class="size-2.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                        <p class="text-xs font-bold text-slate-800 leading-normal">{{ $step['title'] }}</p>
                                    </div>
                                    <span class="shrink-0 rounded bg-emerald-100/90 px-1.5 py-0.5 text-[9px] font-bold text-emerald-800">
                                        {{ $step['statusLabel'] }}
                                    </span>
                                </div>
                            @elseif ($step['status'] === 'active')
                                <div class="group relative flex items-center justify-between gap-2.5 rounded-lg border border-blue-200 bg-blue-50/60 px-2.5 py-1.5 shadow-2xs ring-1 ring-blue-500/20 transition-all duration-200 hover:bg-blue-50/90">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="relative flex size-5 shrink-0 items-center justify-center rounded-md bg-blue-600 text-white font-extrabold text-[10px] shadow-xs shadow-blue-500/30">
                                            <span class="absolute -top-0.5 -right-0.5 size-1.5 rounded-full bg-amber-400 ring-1 ring-white animate-pulse"></span>
                                            {{ $step['step'] }}
                                        </span>
                                        <p class="text-xs font-extrabold text-navy-950 leading-normal">{{ $step['title'] }}</p>
                                    </div>
                                    <span class="inline-flex shrink-0 items-center gap-1 rounded bg-blue-600 px-1.5 py-0.5 text-[9px] font-extrabold text-white shadow-2xs">
                                        <span class="size-1 rounded-full bg-white animate-ping"></span>
                                        {{ $step['statusLabel'] }}
                                    </span>
                                </div>
                            @else
                                <div class="group relative flex items-center justify-between gap-2.5 rounded-lg border border-transparent px-2.5 py-1.5 transition-all duration-200 hover:border-slate-100 hover:bg-slate-50/70">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="flex size-5 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-500 font-bold text-[10px] border border-slate-200/60">
                                            {{ $step['step'] }}
                                        </span>
                                        <p class="text-xs font-semibold text-slate-600 leading-normal">{{ $step['title'] }}</p>
                                    </div>
                                    <span class="shrink-0 text-[9px] font-medium text-slate-400">
                                        {{ $step['statusLabel'] }}
                                    </span>
                                </div>
                            @endif
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

                    <!-- STEP 01: Ceritakan -->
                    <article class="group flex flex-col justify-between rounded-2xl sm:rounded-3xl border border-slate-100 bg-white p-3.5 sm:p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-7 sm:size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-[11px] sm:text-xs font-black text-white shadow-sm sm:shadow-md shadow-blue-600/30">
                                    01
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

                    <!-- STEP 02: Tentukan lokasi -->
                    <article class="group flex flex-col justify-between rounded-2xl sm:rounded-3xl border border-slate-100 bg-white p-3.5 sm:p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-7 sm:size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-[11px] sm:text-xs font-black text-white shadow-sm sm:shadow-md shadow-blue-600/30">
                                    02
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

                    <!-- STEP 03: Masukkan gambar -->
                    <article class="group flex flex-col justify-between rounded-2xl sm:rounded-3xl border border-slate-100 bg-white p-3.5 sm:p-5 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div>
                            <!-- Number Badge -->
                            <div class="flex justify-center">
                                <span class="flex size-7 sm:size-9 items-center justify-center rounded-full bg-blue-600 font-sans text-[11px] sm:text-xs font-black text-white shadow-sm sm:shadow-md shadow-blue-600/30">
                                    03
                                </span>
                            </div>

                            <!-- Illustration: Photo Card & Add Badge -->
                            <div class="my-2 sm:my-3 flex h-14 sm:h-20 lg:h-24 items-center justify-center">
                                <svg class="h-full w-auto overflow-visible" viewBox="0 0 120 100" fill="none" aria-hidden="true">
                                    <!-- Back Card Angle -->
                                    <rect x="36" y="16" width="56" height="52" rx="7" transform="rotate(7 36 16)" fill="#e2e8f0" opacity="0.6"/>

                                    <!-- Main Photo Card -->
                                    <rect x="24" y="15" width="60" height="54" rx="8" fill="#ffffff" stroke="#cbd5e1" stroke-width="1.2" filter="drop-shadow(0 4px 6px rgb(0 0 0 / 0.06))"/>

                                    <!-- Photo Area -->
                                    <rect x="29" y="20" width="50" height="34" rx="5" fill="#f0f9ff"/>

                                    <!-- Sun in Photo -->
                                    <circle cx="41" cy="30" r="4.5" fill="#f59e0b"/>

                                    <!-- Mountains in Photo -->
                                    <path d="M 29 48 L 46 35 L 59 45 L 68 38 L 79 47 L 79 54 L 29 54 Z" fill="#93c5fd" opacity="0.5"/>
                                    <path d="M 40 54 L 54 41 L 70 52 L 79 46 L 79 54 Z" fill="#3b82f6" opacity="0.8"/>

                                    <!-- Caption Lines -->
                                    <rect x="32" y="58" width="24" height="2.5" rx="1.25" fill="#cbd5e1"/>
                                    <rect x="32" y="63" width="14" height="2" rx="1" fill="#e2e8f0"/>

                                    <!-- Add Badge -->
                                    <circle cx="85" cy="67" r="12" fill="#2563eb" stroke="white" stroke-width="2.5" filter="drop-shadow(0 2px 4px rgb(37 99 235 / 0.35))"/>
                                    <path d="M 85 61 V 73 M 79 67 H 91" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>

                            <!-- Title -->
                            <h3 class="text-xs sm:text-sm lg:text-base font-extrabold text-navy-950">
                                Masukkan gambar
                            </h3>

                            <!-- Description -->
                            <p class="mt-1 text-[11px] sm:text-xs text-slate-500 leading-snug sm:leading-relaxed">
                                Lampirkan foto bukti pendukung.
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
                        <!-- Card 1: Tidak Perlu Membuat Akun -->
                        <article class="group flex flex-col justify-between rounded-xl sm:rounded-3xl border border-slate-100 bg-white p-3.5 sm:p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-md">
                            <div>
                                <div class="flex items-center justify-between">
                                    <!-- Icon: Identitas / Profil Tersembunyi -->
                                    <div class="flex size-9 sm:size-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                                        <svg class="size-4.5 sm:size-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <line x1="2" y1="2" x2="22" y2="22" />
                                        </svg>
                                    </div>
                                    <span class="inline-flex items-center rounded-md bg-blue-50/80 px-2 py-0.5 text-[11px] font-extrabold text-blue-600 border border-blue-100">
                                        01
                                    </span>
                                </div>

                                <div class="mt-2.5 sm:mt-3">
                                    <h3 class="text-xs sm:text-base font-extrabold text-navy-950">
                                        Tidak Perlu Membuat Akun
                                    </h3>
                                    <p class="mt-1 text-[11px] sm:text-sm text-slate-500 leading-snug sm:leading-relaxed">
                                        Sampaikan informasi tanpa mendaftar atau mengisi data pribadi.
                                    </p>
                                </div>
                            </div>
                        </article>

                        <!-- Card 2: Pantau dengan Kode Rahasia -->
                        <article class="group flex flex-col justify-between rounded-xl sm:rounded-3xl border border-slate-100 bg-white p-3.5 sm:p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-md">
                            <div>
                                <div class="flex items-center justify-between">
                                    <!-- Icon: Kunci Akses / PIN Pelacakan Rahasia -->
                                    <div class="flex size-9 sm:size-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                                        <svg class="size-4.5 sm:size-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="7.5" cy="15.5" r="4.5" />
                                            <path d="m21 3-9.5 9.5" />
                                            <path d="m15.5 7.5 3 3" />
                                            <path d="m18 5 2 2" />
                                        </svg>
                                    </div>
                                    <span class="inline-flex items-center rounded-md bg-blue-50/80 px-2 py-0.5 text-[11px] font-extrabold text-blue-600 border border-blue-100">
                                        02
                                    </span>
                                </div>

                                <div class="mt-2.5 sm:mt-3">
                                    <h3 class="text-xs sm:text-base font-extrabold text-navy-950">
                                        Pantau dengan Kode Rahasia
                                    </h3>
                                    <p class="mt-1 text-[11px] sm:text-sm text-slate-500 leading-snug sm:leading-relaxed">
                                        Gunakan kode tiket dan PIN untuk melihat perkembangan penanganan.
                                    </p>
                                </div>
                            </div>
                        </article>

                        <!-- Card 3: Diakses Petugas Berwenang -->
                        <article class="group flex flex-col justify-between rounded-xl sm:rounded-3xl border border-slate-100 bg-white p-3.5 sm:p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-md">
                            <div>
                                <div class="flex items-center justify-between">
                                    <!-- Icon: Perisai Verifikasi / Otoritas Resmi BI -->
                                    <div class="flex size-9 sm:size-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                                        <svg class="size-4.5 sm:size-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                            <path d="m9 12 2 2 4-4" />
                                        </svg>
                                    </div>
                                    <span class="inline-flex items-center rounded-md bg-blue-50/80 px-2 py-0.5 text-[11px] font-extrabold text-blue-600 border border-blue-100">
                                        03
                                    </span>
                                </div>

                                <div class="mt-2.5 sm:mt-3">
                                    <h3 class="text-xs sm:text-base font-extrabold text-navy-950">
                                        Diakses Petugas Berwenang
                                    </h3>
                                    <p class="mt-1 text-[11px] sm:text-sm text-slate-500 leading-snug sm:leading-relaxed">
                                        Informasi hanya dapat dilihat oleh petugas yang memiliki kewenangan.
                                    </p>
                                </div>
                            </div>
                        </article>

                        <!-- Card 4: Sampaikan dengan Mudah -->
                        <article class="group flex flex-col justify-between rounded-xl sm:rounded-3xl border border-blue-200/80 bg-gradient-to-b from-blue-50/60 via-white to-blue-50/30 p-3.5 sm:p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
                            <div>
                                <div class="flex items-center justify-between">
                                    <!-- Icon: Pesawat Kertas Kirim Laporan -->
                                    <div class="flex size-9 sm:size-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-600/25 transition-transform group-hover:scale-105">
                                        <svg class="size-4.5 sm:size-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m22 2-7 20-4-9-9-4Z" />
                                            <path d="M22 2 11 13" />
                                        </svg>
                                    </div>
                                    <span class="inline-flex items-center rounded-md bg-blue-100/90 px-2 py-0.5 text-[11px] font-extrabold text-blue-700 border border-blue-200">
                                        04
                                    </span>
                                </div>

                                <div class="mt-2.5 sm:mt-3">
                                    <h3 class="text-xs sm:text-base font-extrabold text-navy-950">
                                        Sampaikan dengan Mudah
                                    </h3>
                                    <p class="mt-1 text-[11px] sm:text-sm text-slate-500 leading-snug sm:leading-relaxed">
                                        Ceritakan kejadian, lokasi, dan informasi pendukung yang Anda ketahui.
                                    </p>
                                </div>
                            </div>

                            <!-- Button Teman Grip 03 -->
                            <div class="mt-3 sm:mt-4 w-full">
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
                            src="{{ asset('images/illustrations/security-shield.webp') }}"
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
                            Laporkan dengan tenang,<br class="hidden sm:inline"> privasi tetap terjaga.
                        </h2>
                        <p class="mt-1.5 sm:mt-2.5 text-xs sm:text-base text-slate-600 leading-relaxed max-w-md mx-auto lg:mx-0">
                            TAMBORA membantu masyarakat menyampaikan informasi mengenai dugaan KUPVA tidak berizin di NTB tanpa perlu memberikan data pribadi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
