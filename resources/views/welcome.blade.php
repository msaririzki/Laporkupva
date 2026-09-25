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

    <section id="cara-kerja" class="relative overflow-hidden scroll-mt-20 bg-gradient-to-b from-[#eef6ff] via-[#f4f9ff] to-[#e8f2fe] py-14 sm:py-20">
        <!-- Mountain Silhouette Background at Bottom (TAMBORA Atmosphere) -->
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-28 sm:h-40 overflow-hidden opacity-35" aria-hidden="true">
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
            <div class="absolute top-10 right-8 size-36 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
            <div class="absolute bottom-12 left-8 size-36 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
        </div>

        <div class="public-container">
            <!-- Header Section (Sesuai Referensi Gambar) -->
            <div class="mx-auto max-w-2xl text-center">
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-100/90 px-4 py-1.5 text-xs font-extrabold uppercase tracking-wider text-blue-700">
                    <span class="size-2 rounded-full bg-blue-600"></span>
                    CARA MELAPOR YANG BAIK
                </div>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-navy-950 sm:text-4xl lg:text-[42px]">
                    Cara melapor di TAMBORA
                </h2>
                <p class="mt-3 text-sm sm:text-base text-slate-500 max-w-xl mx-auto leading-relaxed">
                    Gampang, kok. Cukup ceritakan, tunjukkan lokasinya, tambahkan foto (jika ada), lalu pantau perkembangan laporanmu.
                </p>
            </div>

            <!-- 4-Step Flow Layout Sesuai Gambar Referensi -->
            <div class="relative mt-10 sm:mt-14">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-5 xl:gap-7">

                    <!-- STEP 01: Ceritakan kejadiannya -->
                    <div class="relative flex flex-col">
                        <!-- Top Illustration Area with Number Badge -->
                        <div class="relative flex h-36 items-center justify-center sm:h-40">
                            <!-- Number Badge 01 on Top Left -->
                            <span class="absolute left-2 top-1 z-10 flex size-9 sm:size-10 items-center justify-center rounded-full bg-blue-600 font-sans text-xs sm:text-sm font-black text-white shadow-md shadow-blue-600/30">
                                01
                            </span>

                            <!-- Character with Speech Bubble Illustration -->
                            <svg class="h-32 sm:h-36 w-auto overflow-visible" viewBox="0 0 160 140" fill="none" aria-hidden="true">
                                <!-- Floating Speech Bubble -->
                                <g>
                                    <rect x="80" y="16" width="62" height="44" rx="10" fill="#e0f2fe" stroke="#bae6fd" stroke-width="1.5"/>
                                    <path d="M88 60 L83 70 L98 60 Z" fill="#e0f2fe" stroke="#bae6fd" stroke-width="1.5"/>
                                    <rect x="90" y="26" width="38" height="3.5" rx="1.75" fill="#38bdf8"/>
                                    <rect x="90" y="34" width="44" height="3.5" rx="1.75" fill="#93c5fd"/>
                                    <rect x="90" y="42" width="28" height="3.5" rx="1.75" fill="#93c5fd"/>
                                </g>

                                <!-- Character Drawing -->
                                <g>
                                    <!-- Long Hair -->
                                    <path d="M 28 58 C 24 36, 52 18, 68 28 C 82 38, 84 62, 80 82 C 76 94, 62 94, 56 84 C 46 94, 30 84, 28 58 Z" fill="#1e293b"/>
                                    <!-- Face -->
                                    <circle cx="56" cy="50" r="16" fill="#fed7aa"/>
                                    <!-- Bangs -->
                                    <path d="M 40 44 C 46 34, 66 34, 72 44 C 64 40, 50 40, 40 44 Z" fill="#0f172a"/>
                                    <!-- Eye & Smile -->
                                    <circle cx="63" cy="50" r="1.5" fill="#0f172a"/>
                                    <path d="M 64 54 Q 66 57 69 54" stroke="#0f172a" stroke-width="1.2" fill="none" stroke-linecap="round"/>
                                    <!-- Body / Shirt -->
                                    <path d="M 36 90 C 36 74, 74 74, 74 90 L 74 105 L 36 105 Z" fill="#2563eb"/>
                                    <!-- Arm holding phone -->
                                    <path d="M 58 82 C 66 80, 76 82, 80 72" stroke="#fed7aa" stroke-width="6" stroke-linecap="round" fill="none"/>
                                    <!-- Smartphone -->
                                    <rect x="75" y="54" width="20" height="36" rx="4" fill="#0f172a"/>
                                    <rect x="77" y="57" width="16" height="28" rx="2" fill="#38bdf8"/>
                                    <circle cx="85" cy="88" r="1.5" fill="#64748b"/>
                                </g>

                                <!-- Sparkle rays -->
                                <path d="M 148 20 L 152 16 M 150 30 L 156 30 M 148 40 L 153 43" stroke="#93c5fd" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <!-- Connector Desktop: 01 -> 02 -->
                        <div class="pointer-events-none absolute -right-6 top-18 z-20 hidden w-12 lg:block xl:-right-7 xl:w-14" aria-hidden="true">
                            <svg class="w-full overflow-visible" viewBox="0 0 60 30" fill="none">
                                <defs>
                                    <marker id="arrow-1-2" viewBox="0 0 10 10" refX="6" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                        <path d="M 1 2 L 7 5 L 1 8" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </marker>
                                </defs>
                                <path d="M 4 8 C 24 0, 40 8, 54 22" stroke="#2563eb" stroke-width="2" stroke-dasharray="4 4" marker-end="url(#arrow-1-2)" />
                            </svg>
                        </div>

                        <!-- Card Step 01 -->
                        <article class="mt-4 flex flex-1 flex-col justify-between rounded-3xl border border-slate-100 bg-white p-6 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg sm:p-7">
                            <div>
                                <h3 class="text-base sm:text-lg font-extrabold text-navy-950">
                                    Ceritakan kejadiannya
                                </h3>
                                <p class="mt-1.5 text-xs sm:text-sm text-slate-500 leading-relaxed">
                                    Ceritakan apa yang terjadi.
                                </p>
                            </div>
                            <!-- Blue Bottom Bar Accent -->
                            <div class="mx-auto mt-5 h-1 w-8 rounded-full bg-blue-600"></div>
                        </article>
                    </div>

                    <!-- STEP 02: Tentukan lokasinya -->
                    <div class="relative flex flex-col">
                        <!-- Top Illustration Area with Number Badge -->
                        <div class="relative flex h-36 items-center justify-center sm:h-40">
                            <!-- Number Badge 02 on Top Left -->
                            <span class="absolute left-2 top-1 z-10 flex size-9 sm:size-10 items-center justify-center rounded-full bg-blue-600 font-sans text-xs sm:text-sm font-black text-white shadow-md shadow-blue-600/30">
                                02
                            </span>

                            <!-- Folded Map with 3D Location Pin Illustration -->
                            <svg class="h-32 sm:h-36 w-auto overflow-visible" viewBox="0 0 160 140" fill="none" aria-hidden="true">
                                <!-- Folded 3D Paper Map -->
                                <g>
                                    <!-- Left panel -->
                                    <polygon points="18,48 58,36 58,102 18,114" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1.5"/>
                                    <!-- Green Terrain on left -->
                                    <path d="M 22 80 Q 35 70 45 85 L 58 75 L 58 102 L 18 114 Z" fill="#dcfce7" opacity="0.8"/>

                                    <!-- Center panel -->
                                    <polygon points="58,36 102,48 102,114 58,102" fill="#e2e8f0" stroke="#cbd5e1" stroke-width="1.5"/>
                                    <!-- River on center -->
                                    <path d="M 64 103 Q 75 75 85 80 Q 95 85 102 65 L 102 75 Q 92 95 82 90 Q 72 85 64 103 Z" fill="#bae6fd"/>

                                    <!-- Right panel -->
                                    <polygon points="102,48 142,36 142,102 102,114" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="1.5"/>
                                    <!-- Green Terrain on right -->
                                    <path d="M 102 85 Q 115 65 130 75 L 142 68 L 142 102 L 102 114 Z" fill="#dcfce7" opacity="0.8"/>
                                </g>

                                <!-- Location Pin Shadow -->
                                <ellipse cx="80" cy="98" rx="14" ry="4" fill="#64748b" opacity="0.3"/>

                                <!-- Large Blue Location Pin -->
                                <g>
                                    <path d="M 80 26 C 65 26, 53 38, 53 53 C 53 72, 80 97, 80 97 C 80 97, 107 72, 107 53 C 107 38, 95 26, 80 26 Z" fill="#2563eb" filter="drop-shadow(0 4px 6px rgb(37 99 235 / 0.3))"/>
                                    <!-- Inner White Circle -->
                                    <circle cx="80" cy="51" r="9" fill="white"/>
                                </g>

                                <!-- Sparkle / Motion Rays -->
                                <path d="M 52 22 L 46 16 M 108 22 L 114 16 M 80 14 L 80 8" stroke="#93c5fd" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <!-- Connector Desktop: 02 -> 03 -->
                        <div class="pointer-events-none absolute -right-6 top-18 z-20 hidden w-12 lg:block xl:-right-7 xl:w-14" aria-hidden="true">
                            <svg class="w-full overflow-visible" viewBox="0 0 60 30" fill="none">
                                <defs>
                                    <marker id="arrow-2-3" viewBox="0 0 10 10" refX="6" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                        <path d="M 1 2 L 7 5 L 1 8" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </marker>
                                </defs>
                                <path d="M 4 8 C 24 0, 40 8, 54 22" stroke="#2563eb" stroke-width="2" stroke-dasharray="4 4" marker-end="url(#arrow-2-3)" />
                            </svg>
                        </div>

                        <!-- Card Step 02 -->
                        <article class="mt-4 flex flex-1 flex-col justify-between rounded-3xl border border-slate-100 bg-white p-6 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg sm:p-7">
                            <div>
                                <h3 class="text-base sm:text-lg font-extrabold text-navy-950">
                                    Tentukan lokasinya
                                </h3>
                                <p class="mt-1.5 text-xs sm:text-sm text-slate-500 leading-relaxed">
                                    Tunjukkan di mana kejadiannya.
                                </p>
                            </div>
                            <!-- Blue Bottom Bar Accent -->
                            <div class="mx-auto mt-5 h-1 w-8 rounded-full bg-blue-600"></div>
                        </article>
                    </div>

                    <!-- STEP 03: Tambahkan foto -->
                    <div class="relative flex flex-col">
                        <!-- Top Illustration Area with Number Badge -->
                        <div class="relative flex h-36 items-center justify-center sm:h-40">
                            <!-- Number Badge 03 on Top Left -->
                            <span class="absolute left-2 top-1 z-10 flex size-9 sm:size-10 items-center justify-center rounded-full bg-blue-600 font-sans text-xs sm:text-sm font-black text-white shadow-md shadow-blue-600/30">
                                03
                            </span>

                            <!-- Two Photo Polaroid Frames with Plus Badge Illustration -->
                            <svg class="h-32 sm:h-36 w-auto overflow-visible" viewBox="0 0 160 140" fill="none" aria-hidden="true">
                                <!-- Back Photo (Tilted Left) -->
                                <g transform="rotate(-9 55 65)">
                                    <rect x="22" y="25" width="68" height="58" rx="7" fill="white" stroke="#cbd5e1" stroke-width="1.5" filter="drop-shadow(0 2px 4px rgb(0 0 0 / 0.06))"/>
                                    <!-- Photo Content -->
                                    <rect x="26" y="29" width="60" height="42" rx="4" fill="#e0f2fe"/>
                                    <!-- Sun -->
                                    <circle cx="72" cy="40" r="5" fill="#fde047"/>
                                    <!-- Mountains -->
                                    <path d="M 26 65 L 42 48 L 56 62 L 68 52 L 86 71 L 26 71 Z" fill="#93c5fd"/>
                                </g>

                                <!-- Front Photo (Tilted Right) -->
                                <g transform="rotate(5 90 65)">
                                    <rect x="52" y="24" width="74" height="64" rx="7" fill="white" stroke="#94a3b8" stroke-width="1.5" filter="drop-shadow(0 4px 8px rgb(0 0 0 / 0.1))"/>
                                    <!-- Photo Content -->
                                    <rect x="56" y="28" width="66" height="48" rx="4" fill="#dbeafe"/>
                                    <!-- Sun -->
                                    <circle cx="106" cy="40" r="6" fill="#f59e0b"/>
                                    <!-- Mountains & Hills -->
                                    <path d="M 56 70 L 78 50 L 96 66 L 108 56 L 122 76 L 56 76 Z" fill="#3b82f6"/>
                                    <path d="M 56 74 Q 85 64 122 74 L 122 76 L 56 76 Z" fill="#2563eb" opacity="0.6"/>
                                </g>

                                <!-- Plus Badge Button on Bottom Right -->
                                <g>
                                    <circle cx="116" cy="85" r="14" fill="#2563eb" stroke="white" stroke-width="2.5" filter="drop-shadow(0 2px 6px rgb(37 99 235 / 0.4))"/>
                                    <path d="M 116 78 L 116 92 M 109 85 L 123 85" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                                </g>

                                <!-- Sparkle rays -->
                                <path d="M 132 30 L 138 24 M 140 42 L 147 42 M 134 54 L 140 58" stroke="#93c5fd" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <!-- Connector Desktop: 03 -> 04 -->
                        <div class="pointer-events-none absolute -right-6 top-18 z-20 hidden w-12 lg:block xl:-right-7 xl:w-14" aria-hidden="true">
                            <svg class="w-full overflow-visible" viewBox="0 0 60 30" fill="none">
                                <defs>
                                    <marker id="arrow-3-4" viewBox="0 0 10 10" refX="6" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                                        <path d="M 1 2 L 7 5 L 1 8" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </marker>
                                </defs>
                                <path d="M 4 8 C 24 0, 40 8, 54 22" stroke="#2563eb" stroke-width="2" stroke-dasharray="4 4" marker-end="url(#arrow-3-4)" />
                            </svg>
                        </div>

                        <!-- Card Step 03 -->
                        <article class="mt-4 flex flex-1 flex-col justify-between rounded-3xl border border-slate-100 bg-white p-6 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg sm:p-7">
                            <div>
                                <h3 class="text-base sm:text-lg font-extrabold text-navy-950">
                                    Tambahkan foto
                                </h3>
                                <p class="mt-1.5 text-xs sm:text-sm text-slate-500 leading-relaxed">
                                    Ada foto? Boleh ikut dikirim.
                                </p>
                            </div>
                            <!-- Blue Bottom Bar Accent -->
                            <div class="mx-auto mt-5 h-1 w-8 rounded-full bg-blue-600"></div>
                        </article>
                    </div>

                    <!-- STEP 04: Pantau laporanmu -->
                    <div class="relative flex flex-col">
                        <!-- Top Illustration Area with Number Badge -->
                        <div class="relative flex h-36 items-center justify-center sm:h-40">
                            <!-- Number Badge 04 on Top Left -->
                            <span class="absolute left-2 top-1 z-10 flex size-9 sm:size-10 items-center justify-center rounded-full bg-blue-600 font-sans text-xs sm:text-sm font-black text-white shadow-md shadow-blue-600/30">
                                04
                            </span>

                            <!-- Smartphone with Security Shield, Padlock & Green Checkmark Illustration -->
                            <svg class="h-32 sm:h-36 w-auto overflow-visible" viewBox="0 0 160 140" fill="none" aria-hidden="true">
                                <!-- Smartphone Body -->
                                <g>
                                    <rect x="52" y="16" width="58" height="96" rx="10" fill="#1e293b" filter="drop-shadow(0 6px 12px rgb(0 0 0 / 0.12))"/>
                                    <!-- Screen -->
                                    <rect x="56" y="22" width="50" height="84" rx="7" fill="#f8fafc"/>
                                    <!-- Top Speaker Notch -->
                                    <rect x="73" y="19" width="16" height="2" rx="1" fill="#64748b"/>

                                    <!-- Blue Security Shield in Center -->
                                    <path d="M 81 38 C 92 38, 97 34, 97 34 C 97 56, 81 70, 81 70 C 81 70, 65 56, 65 34 C 65 34, 70 38, 81 38 Z" fill="#2563eb" filter="drop-shadow(0 3px 5px rgb(37 99 235 / 0.35))"/>

                                    <!-- White Padlock inside Shield -->
                                    <rect x="76" y="49" width="10" height="9" rx="2" fill="white"/>
                                    <path d="M 78 49 V 45 A 3 3 0 0 1 84 45 V 49" stroke="white" stroke-width="1.8" stroke-linecap="round" fill="none"/>
                                    <circle cx="81" cy="53.5" r="1.2" fill="#2563eb"/>

                                    <!-- Progress Lines on Screen -->
                                    <rect x="65" y="78" width="32" height="3" rx="1.5" fill="#cbd5e1"/>
                                    <rect x="65" y="85" width="22" height="3" rx="1.5" fill="#e2e8f0"/>
                                </g>

                                <!-- Green Checkmark Badge on Right -->
                                <g>
                                    <circle cx="116" cy="74" r="14" fill="#22c55e" stroke="white" stroke-width="2.5" filter="drop-shadow(0 3px 6px rgb(34 197 94 / 0.4))"/>
                                    <path d="M 110 74 L 114 78 L 122 69" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                </g>

                                <!-- Sparkle rays -->
                                <path d="M 40 28 L 34 22 M 32 40 L 25 40 M 36 52 L 30 56" stroke="#93c5fd" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <!-- Card Step 04 -->
                        <article class="mt-4 flex flex-1 flex-col justify-between rounded-3xl border border-slate-100 bg-white p-6 shadow-sm text-center transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg sm:p-7">
                            <div>
                                <h3 class="text-base sm:text-lg font-extrabold text-navy-950">
                                    Pantau laporanmu
                                </h3>
                                <p class="mt-1.5 text-xs sm:text-sm text-slate-500 leading-relaxed">
                                    Simpan kode laporan untuk melihat perkembangannya.
                                </p>
                            </div>
                            <!-- Blue Bottom Bar Accent -->
                            <div class="mx-auto mt-5 h-1 w-8 rounded-full bg-blue-600"></div>
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
