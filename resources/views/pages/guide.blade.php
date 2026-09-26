<x-layouts.public title="Panduan dan FAQ">
    <!-- Hero Section: Panduan Masyarakat -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[#eef6ff] via-[#f5f9fe] to-[#e8f2fe] pt-5 pb-7 sm:pt-7 sm:pb-9 lg:pt-8 lg:pb-10">
        <!-- Mountain Silhouette Background at Bottom (TAMBORA Atmosphere) -->
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-16 sm:h-24 overflow-hidden opacity-25" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1440 240" fill="none" preserveAspectRatio="none">
                <path d="M0 240 L0 150 L140 130 L280 180 L440 100 L590 160 L740 60 L890 150 L1040 85 L1190 140 L1340 75 L1440 120 L1440 240 Z" fill="url(#guide-mountains-grad)" />
                <defs>
                    <linearGradient id="guide-mountains-grad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#93c5fd" stop-opacity="0.5"/>
                        <stop offset="100%" stop-color="#2563eb" stop-opacity="0.8"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <!-- Subtle Dot Grid Patterns on Sides -->
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute top-6 right-10 size-28 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
            <div class="absolute bottom-6 left-10 size-28 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
        </div>

        <div class="public-container">
            <div class="grid items-center gap-6 sm:gap-8 lg:grid-cols-12 lg:gap-10">
                <!-- Left Content: Panduan Masyarakat -->
                <div class="text-center lg:col-span-7 lg:text-left">
                    <!-- Eyebrow Badge -->
                    <div class="inline-flex items-center gap-1.5 rounded-full border border-blue-200/90 bg-blue-50/90 px-3 py-0.5 text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider text-blue-700 shadow-2xs backdrop-blur-xs">
                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z" />
                            <path d="M6 6h10" />
                            <path d="M6 10h10" />
                        </svg>
                        <span>PANDUAN</span>
                    </div>

                    <!-- Main Title -->
                    <h1 class="mt-2.5 text-2xl sm:text-3xl lg:text-[2.5rem] font-black tracking-tight text-navy-950 leading-tight lg:leading-[1.16]">
                        Panduan lengkap untuk melapor di TAMBORA
                    </h1>

                    <!-- Subtitle -->
                    <p class="mt-2 text-xs sm:text-sm lg:text-[14.5px] text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Melapor dengan aman dan mudah. Temukan langkah-langkah membuat laporan, informasi keamanan, dan jawaban untuk pertanyaan yang sering ditanyakan.
                    </p>
                </div>

                <!-- Right Illustration -->
                <div class="lg:col-span-5 flex items-center justify-center">
                    <div class="relative mx-auto flex w-full max-w-[280px] sm:max-w-[340px] lg:max-w-[400px] items-center justify-center">
                        <!-- Glow Accent Behind Image -->
                        <div class="absolute inset-0 -z-10 rounded-full bg-gradient-to-tr from-blue-300/30 via-sky-200/40 to-blue-100/30 blur-2xl"></div>
                        <img
                            src="{{ asset('images/illustrations/guide-hero.webp') }}"
                            alt="Ilustrasi Panduan Masyarakat TAMBORA"
                            class="h-auto w-full object-contain drop-shadow-lg transition-transform duration-500 hover:scale-[1.02]"
                            width="960"
                            height="720"
                            loading="eager"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Panduan Cepat Section: 3 Grip Cards Minimalist -->
    <section class="py-5 sm:py-7 bg-slate-50/70 border-y border-slate-100" id="panduan-cepat">
        <div class="public-container">
            <!-- Header of Panduan Cepat (Without button) -->
            <div class="mb-3.5 sm:mb-4">
                <h2 class="text-lg sm:text-xl font-black tracking-tight text-navy-950">Panduan cepat</h2>
                <p class="mt-0.5 text-xs text-slate-500">Ikuti langkah-langkah singkat ini untuk membuat laporan di TAMBORA.</p>
            </div>

            <!-- 3 Grip Cards with Flow Arrows -->
            <div class="flex flex-col lg:flex-row items-stretch gap-3 lg:gap-3">
                <!-- Card 01: Siapkan informasi -->
                <article class="flex-1 flex flex-col justify-center rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-4.5 shadow-xs hover:border-blue-200 hover:shadow-sm transition-all duration-200">
                    <div class="flex items-center gap-3.5 sm:gap-4">
                        <div class="relative shrink-0 flex items-center justify-center size-13 sm:size-14 rounded-2xl bg-gradient-to-br from-blue-50/80 to-sky-50/40 p-1 border border-blue-100/60">
                            <!-- SVG: Document & Pencil Illustration -->
                            <svg class="size-10 sm:size-11" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="14" y="14" width="44" height="52" rx="8" fill="#93c5fd" fill-opacity="0.25" />
                                <rect x="14" y="12" width="44" height="52" rx="8" fill="url(#card1-paper-grad)" stroke="#bae6fd" stroke-width="1.5" />
                                <path d="M44 12V22C44 23.1046 44.8954 24 46 24H58" fill="#e0f2fe" stroke="#bae6fd" stroke-width="1.5" />
                                <rect x="22" y="27" width="16" height="3" rx="1.5" fill="#38bdf8" />
                                <rect x="22" y="35" width="28" height="3" rx="1.5" fill="#bae6fd" />
                                <rect x="22" y="43" width="22" height="3" rx="1.5" fill="#bae6fd" />
                                <rect x="22" y="51" width="14" height="3" rx="1.5" fill="#e0f2fe" />
                                <g transform="rotate(-35 50 46)">
                                    <rect x="47" y="24" width="8" height="30" rx="4" fill="url(#card1-pen-grad)" />
                                    <path d="M47 54L51 62L55 54H47Z" fill="#facc15" />
                                    <path d="M50 60L51 62L52 60H50Z" fill="#0f172a" />
                                    <rect x="46" y="28" width="10" height="2" rx="1" fill="#e2e8f0" />
                                </g>
                                <defs>
                                    <linearGradient id="card1-paper-grad" x1="14" y1="12" x2="58" y2="64" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#ffffff" />
                                        <stop offset="100%" stop-color="#f0f9ff" />
                                    </linearGradient>
                                    <linearGradient id="card1-pen-grad" x1="47" y1="24" x2="55" y2="54" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#3b82f6" />
                                        <stop offset="100%" stop-color="#1d4ed8" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="inline-flex items-center justify-center rounded-md bg-blue-50 px-2.5 py-0.5 text-xs font-extrabold text-blue-600">01</span>
                            <h3 class="mt-1 text-sm sm:text-base font-extrabold text-navy-950">Siapkan informasi</h3>
                            <p class="mt-1 text-xs sm:text-[13px] leading-relaxed text-slate-600">Ceritakan apa yang terjadi, waktu kejadian, dan nama tempat jika tahu.</p>
                        </div>
                    </div>
                </article>

                <!-- Arrow Connector 1 (Desktop) -->
                <div class="hidden lg:flex size-7 shrink-0 items-center justify-center self-center rounded-full bg-blue-50/90 text-blue-500 border border-blue-100 shadow-xs" aria-hidden="true">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </div>

                <!-- Card 02: Tentukan titik lokasi -->
                <article class="flex-1 flex flex-col justify-center rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-4.5 shadow-xs hover:border-blue-200 hover:shadow-sm transition-all duration-200">
                    <div class="flex items-center gap-3.5 sm:gap-4">
                        <div class="relative shrink-0 flex items-center justify-center size-13 sm:size-14 rounded-2xl bg-gradient-to-br from-blue-50/80 to-emerald-50/40 p-1 border border-blue-100/60">
                            <!-- SVG: Folded Map & Location Pin Illustration -->
                            <svg class="size-10 sm:size-11" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <ellipse cx="40" cy="62" rx="25" ry="5" fill="#93c5fd" fill-opacity="0.3" />
                                <polygon points="13,29 29,23 29,55 13,61" fill="url(#card2-panel-1)" stroke="#93c5fd" stroke-width="1.2" stroke-linejoin="round" />
                                <polygon points="29,23 51,29 51,61 29,55" fill="url(#card2-panel-2)" stroke="#93c5fd" stroke-width="1.2" stroke-linejoin="round" />
                                <polygon points="51,29 67,23 67,55 51,61" fill="url(#card2-panel-3)" stroke="#93c5fd" stroke-width="1.2" stroke-linejoin="round" />
                                <path d="M16 41Q25 39 33 43T49 41Q58 45 64 37" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-opacity="0.85" />
                                <ellipse cx="40" cy="48" rx="6" ry="2.5" fill="#0f172a" fill-opacity="0.2" />
                                <path d="M40 48C40 48 49 37 49 28C49 23.0294 44.9706 19 40 19C35.0294 19 31 23.0294 31 28C31 37 40 48 40 48Z" fill="url(#card2-pin-grad)" />
                                <circle cx="40" cy="28" r="4" fill="#ffffff" />
                                <defs>
                                    <linearGradient id="card2-panel-1" x1="13" y1="23" x2="29" y2="61" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#e0f2fe" />
                                        <stop offset="100%" stop-color="#bae6fd" />
                                    </linearGradient>
                                    <linearGradient id="card2-panel-2" x1="29" y1="23" x2="51" y2="61" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#d1fae5" />
                                        <stop offset="100%" stop-color="#a7f3d0" />
                                    </linearGradient>
                                    <linearGradient id="card2-panel-3" x1="51" y1="23" x2="67" y2="61" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#e0f2fe" />
                                        <stop offset="100%" stop-color="#bae6fd" />
                                    </linearGradient>
                                    <linearGradient id="card2-pin-grad" x1="31" y1="19" x2="49" y2="48" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#ef4444" />
                                        <stop offset="100%" stop-color="#b91c1c" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="inline-flex items-center justify-center rounded-md bg-blue-50 px-2.5 py-0.5 text-xs font-extrabold text-blue-600">02</span>
                            <h3 class="mt-1 text-sm sm:text-base font-extrabold text-navy-950">Tentukan titik lokasi</h3>
                            <p class="mt-1 text-xs sm:text-[13px] leading-relaxed text-slate-600">Tunjukkan lokasi tempatnya di peta atau gunakan titik lokasi HP kamu.</p>
                        </div>
                    </div>
                </article>

                <!-- Arrow Connector 2 (Desktop) -->
                <div class="hidden lg:flex size-7 shrink-0 items-center justify-center self-center rounded-full bg-blue-50/90 text-blue-500 border border-blue-100 shadow-xs" aria-hidden="true">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </div>

                <!-- Card 03: Simpan kode akses -->
                <article class="flex-1 flex flex-col justify-center rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-4.5 shadow-xs hover:border-blue-200 hover:shadow-sm transition-all duration-200">
                    <div class="flex items-center gap-3.5 sm:gap-4">
                        <div class="relative shrink-0 flex items-center justify-center size-13 sm:size-14 rounded-2xl bg-gradient-to-br from-amber-50/80 to-blue-50/40 p-1 border border-amber-100/60">
                            <!-- SVG: Vault Folder & Checkmark Illustration -->
                            <svg class="size-10 sm:size-11" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="14" y="21" width="44" height="40" rx="8" fill="#f59e0b" fill-opacity="0.2" />
                                <path d="M14 23C14 19.6863 16.6863 17 20 17H31L35 21H52C55.3137 21 58 23.6863 58 27V33H14V23Z" fill="#d97706" />
                                <rect x="17" y="21" width="37" height="11" rx="3" fill="#ffffff" fill-opacity="0.9" />
                                <rect x="14" y="26" width="44" height="36" rx="7" fill="url(#card3-folder-grad)" stroke="#fbbf24" stroke-width="1.2" />
                                <rect x="32" y="41" width="10" height="9" rx="2" fill="#ffffff" fill-opacity="0.5" />
                                <path d="M34 41V38C34 36.3431 35.3431 35 37 35C38.6569 35 40 36.3431 40 38V41" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round" fill="none" />
                                <g>
                                    <circle cx="52" cy="52" r="12" fill="url(#card3-check-grad)" stroke="#ffffff" stroke-width="2" />
                                    <path d="M47 52L51 56L57 48" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                                <defs>
                                    <linearGradient id="card3-folder-grad" x1="14" y1="26" x2="58" y2="62" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#fbbf24" />
                                        <stop offset="100%" stop-color="#f59e0b" />
                                    </linearGradient>
                                    <linearGradient id="card3-check-grad" x1="40" y1="40" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#3b82f6" />
                                        <stop offset="100%" stop-color="#1d4ed8" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="inline-flex items-center justify-center rounded-md bg-blue-50 px-2.5 py-0.5 text-xs font-extrabold text-blue-600">03</span>
                            <h3 class="mt-1 text-sm sm:text-base font-extrabold text-navy-950">Simpan kode akses</h3>
                            <p class="mt-1 text-xs sm:text-[13px] leading-relaxed text-slate-600">Simpan kode dan PIN rahasiamu untuk mengecek hasil laporan kapan saja.</p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Pertanyaan Umum Section -->
    <section class="py-8 sm:py-12" id="pertanyaan-umum">
        <div class="public-container">
            <div class="grid items-start gap-8 lg:grid-cols-12 lg:gap-8 xl:gap-10">
                <!-- Left Column: FAQ Accordion Cards with Clean Unified Badges -->
                <div class="lg:col-span-7 xl:col-span-8">
                    <!-- Header Pertanyaan Umum -->
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black tracking-tight text-navy-950">Pertanyaan Umum</h2>
                        <p class="mt-1 text-xs sm:text-sm text-slate-500">Temukan jawaban untuk pertanyaan yang paling sering ditanyakan.</p>
                    </div>

                    <!-- FAQ List -->
                    <div id="faq-list" class="mt-5 flex flex-col gap-2.5 sm:gap-3">
                        <!-- Q1: Akun & Identitas -->
                        <details class="faq-item group rounded-xl border border-slate-200/80 bg-white p-3.5 sm:p-4 shadow-2xs transition-all duration-200 hover:border-blue-200 hover:shadow-xs open:border-blue-200 open:shadow-xs open:bg-blue-50/15">
                            <summary class="flex cursor-pointer list-none items-center gap-3 sm:gap-3.5 select-none">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100/70 group-hover:bg-blue-100/80 group-open:bg-blue-600 group-open:text-white group-open:border-blue-600 transition-colors">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-bold text-navy-950 text-xs sm:text-sm group-open:text-blue-700 transition-colors">Apakah saya harus membuat akun?</span>
                                <span class="grid size-6 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-2.5 text-xs sm:text-[13px] text-slate-600 leading-relaxed pr-2 pt-2.5 border-t border-slate-100 pl-11">
                                Tidak. TAMBORA tidak meminta akun, nama, NIK, email, atau nomor telepon pelapor.
                            </div>
                        </details>

                        <!-- Q2: Lokasi & Peta -->
                        <details class="faq-item group rounded-xl border border-slate-200/80 bg-white p-3.5 sm:p-4 shadow-2xs transition-all duration-200 hover:border-blue-200 hover:shadow-xs open:border-blue-200 open:shadow-xs open:bg-blue-50/15">
                            <summary class="flex cursor-pointer list-none items-center gap-3 sm:gap-3.5 select-none">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100/70 group-hover:bg-blue-100/80 group-open:bg-blue-600 group-open:text-white group-open:border-blue-600 transition-colors">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-bold text-navy-950 text-xs sm:text-sm group-open:text-blue-700 transition-colors">Bagaimana jika saya sudah meninggalkan lokasi?</span>
                                <span class="grid size-6 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-2.5 text-xs sm:text-[13px] text-slate-600 leading-relaxed pr-2 pt-2.5 border-t border-slate-100 pl-11">
                                Cari nama wilayah, jalan, desa, kecamatan, atau patokan. Setelah hasil tampil, geser pin ke lokasi kejadian yang paling akurat.
                            </div>
                        </details>

                        <!-- Q3: Bukti Laporan -->
                        <details class="faq-item group rounded-xl border border-slate-200/80 bg-white p-3.5 sm:p-4 shadow-2xs transition-all duration-200 hover:border-blue-200 hover:shadow-xs open:border-blue-200 open:shadow-xs open:bg-blue-50/15">
                            <summary class="flex cursor-pointer list-none items-center gap-3 sm:gap-3.5 select-none">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100/70 group-hover:bg-blue-100/80 group-open:bg-blue-600 group-open:text-white group-open:border-blue-600 transition-colors">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-bold text-navy-950 text-xs sm:text-sm group-open:text-blue-700 transition-colors">Apakah bukti wajib dilampirkan?</span>
                                <span class="grid size-6 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-2.5 text-xs sm:text-[13px] text-slate-600 leading-relaxed pr-2 pt-2.5 border-t border-slate-100 pl-11">
                                Ya. Setiap laporan wajib menyertakan minimal satu foto atau PDF sebagai dasar verifikasi. Utamakan keselamatan dan jangan mengambil bukti langsung jika situasinya berisiko.
                            </div>
                        </details>

                        <!-- Q4: Cek Status -->
                        <details class="faq-item group rounded-xl border border-slate-200/80 bg-white p-3.5 sm:p-4 shadow-2xs transition-all duration-200 hover:border-blue-200 hover:shadow-xs open:border-blue-200 open:shadow-xs open:bg-blue-50/15">
                            <summary class="flex cursor-pointer list-none items-center gap-3 sm:gap-3.5 select-none">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100/70 group-hover:bg-blue-100/80 group-open:bg-blue-600 group-open:text-white group-open:border-blue-600 transition-colors">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M4 9h4v11H4zm6-5h4v16h-4zm6 8h4v8h-4z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-bold text-navy-950 text-xs sm:text-sm group-open:text-blue-700 transition-colors">Bagaimana saya mengetahui perkembangan laporan?</span>
                                <span class="grid size-6 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-2.5 text-xs sm:text-[13px] text-slate-600 leading-relaxed pr-2 pt-2.5 border-t border-slate-100 pl-11">
                                Buka halaman Cek status, lalu masukkan kode laporan dan PIN enam digit yang ditampilkan setelah laporan dikirim.
                            </div>
                        </details>

                        <!-- Q5: Tanya Jawab Petugas -->
                        <details class="faq-item group rounded-xl border border-slate-200/80 bg-white p-3.5 sm:p-4 shadow-2xs transition-all duration-200 hover:border-blue-200 hover:shadow-xs open:border-blue-200 open:shadow-xs open:bg-blue-50/15">
                            <summary class="flex cursor-pointer list-none items-center gap-3 sm:gap-3.5 select-none">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100/70 group-hover:bg-blue-100/80 group-open:bg-blue-600 group-open:text-white group-open:border-blue-600 transition-colors">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-bold text-navy-950 text-xs sm:text-sm group-open:text-blue-700 transition-colors">Bisakah saya menjawab pertanyaan petugas?</span>
                                <span class="grid size-6 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-2.5 text-xs sm:text-[13px] text-slate-600 leading-relaxed pr-2 pt-2.5 border-t border-slate-100 pl-11">
                                Bisa. Setelah membuka progres laporan, gunakan kotak komunikasi anonim untuk membaca dan membalas pesan petugas.
                            </div>
                        </details>

                        <!-- Q6: Keamanan & PIN -->
                        <details class="faq-item group rounded-xl border border-slate-200/80 bg-white p-3.5 sm:p-4 shadow-2xs transition-all duration-200 hover:border-blue-200 hover:shadow-xs open:border-blue-200 open:shadow-xs open:bg-blue-50/15">
                            <summary class="flex cursor-pointer list-none items-center gap-3 sm:gap-3.5 select-none">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100/70 group-hover:bg-blue-100/80 group-open:bg-blue-600 group-open:text-white group-open:border-blue-600 transition-colors">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-bold text-navy-950 text-xs sm:text-sm group-open:text-blue-700 transition-colors">Apa yang harus dilakukan jika kode atau PIN hilang?</span>
                                <span class="grid size-6 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-2.5 text-xs sm:text-[13px] text-slate-600 leading-relaxed pr-2 pt-2.5 border-t border-slate-100 pl-11">
                                Akses tidak dapat dipulihkan karena sistem tidak menyimpan identitas atau kontak pelapor. Simpan kode dan PIN di tempat yang aman.
                            </div>
                        </details>
                    </div>
                </div>

                <!-- Right Column: 2 Clean Minimalist Action Cards -->
                <div class="lg:col-span-5 xl:col-span-4 flex flex-col gap-3.5 sm:gap-4 lg:sticky lg:top-24">
                    <!-- Card 1: Sudah pernah melapor? (Cek Status) -->
                    <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-2xs transition-all duration-200 hover:border-blue-200 hover:shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <div class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100/80">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.3-4.3"/>
                                </svg>
                            </div>
                            <h3 class="text-sm sm:text-base font-extrabold text-navy-950">Sudah pernah melapor?</h3>
                        </div>
                        <p class="mt-2 text-xs sm:text-[13px] leading-relaxed text-slate-500">
                            Gunakan kode tiket dan PIN rahasia untuk memantau perkembangan tindak lanjut laporan Anda.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('reports.track') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200/90 bg-blue-50/70 py-2.5 px-4 text-xs sm:text-sm font-bold text-blue-700 shadow-2xs hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-200">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.3-4.3"/>
                                </svg>
                                <span>Cek status sekarang</span>
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Siap menyampaikan laporan? -->
                    <div class="rounded-2xl bg-[#2563EB] p-5 shadow-md shadow-blue-600/20 text-white transition-all duration-200 hover:bg-[#1D4ED8]">
                        <div class="flex items-center gap-2.5">
                            <div class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-white/15 text-white border border-white/20 backdrop-blur-xs">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m22 2-7 20-4-9-9-4Z"/>
                                    <path d="M22 2 11 13"/>
                                </svg>
                            </div>
                            <h3 class="text-sm sm:text-base font-extrabold text-white">Siap menyampaikan laporan?</h3>
                        </div>
                        <p class="mt-2 text-xs sm:text-[13px] leading-relaxed text-blue-100/90">
                            Sampaikan dugaan pelanggaran secara aman tanpa mencantumkan identitas pribadi Anda.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('reports.create') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-white py-2.5 px-4 text-xs sm:text-sm font-bold text-blue-700 shadow-xs hover:bg-blue-50 transition-all duration-200">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m22 2-7 20-4-9-9-4Z"/>
                                    <path d="M22 2 11 13"/>
                                </svg>
                                <span>Buat laporan anonim</span>
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
