<x-layouts.public title="Panduan dan FAQ">
    <!-- Hero Section: Panduan Masyarakat -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[#eef6ff] via-[#f5f9fe] to-[#e8f2fe] pt-6 pb-8 sm:pt-8 sm:pb-10 lg:pt-9 lg:pb-12">
        <!-- Mountain Silhouette Background at Bottom (TAMBORA Atmosphere) -->
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-20 sm:h-28 overflow-hidden opacity-25" aria-hidden="true">
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
            <div class="grid items-center gap-8 lg:grid-cols-12 lg:gap-10">
                <!-- Left Content: Panduan Masyarakat -->
                <div class="text-center lg:col-span-7 lg:text-left">
                    <!-- Eyebrow Badge -->
                    <div class="inline-flex items-center gap-2 rounded-full bg-blue-100/90 px-3.5 py-1 text-xs font-extrabold uppercase tracking-wider text-blue-700 shadow-xs">
                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z" />
                            <path d="M6 6h10" />
                            <path d="M6 10h10" />
                        </svg>
                        PANDUAN
                    </div>

                    <!-- Main Title -->
                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-navy-950 sm:text-4xl lg:text-[2.65rem] lg:leading-[1.18]">
                        Panduan lengkap untuk melapor di TAMBORA
                    </h1>

                    <!-- Subtitle -->
                    <p class="mt-2.5 text-sm sm:text-base text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Melapor dengan aman dan mudah. Temukan langkah-langkah membuat laporan, informasi keamanan, dan jawaban untuk pertanyaan yang sering ditanyakan.
                    </p>

                </div>

                <!-- Right Illustration -->
                <div class="lg:col-span-5 flex items-center justify-center">
                    <div class="relative mx-auto flex w-full max-w-[320px] sm:max-w-[400px] lg:max-w-[460px] items-center justify-center">
                        <!-- Glow Accent Behind Image -->
                        <div class="absolute inset-0 -z-10 rounded-full bg-gradient-to-tr from-blue-300/30 via-sky-200/40 to-blue-100/30 blur-2xl"></div>
                        <img
                            src="{{ asset('images/illustrations/guide-hero.webp') }}"
                            alt="Ilustrasi Panduan Masyarakat TAMBORA"
                            class="h-auto w-full object-contain drop-shadow-xl transition-transform duration-500 hover:scale-[1.02]"
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
    <section class="py-6 sm:py-8 bg-slate-50/60" id="panduan-cepat">
        <div class="public-container">
            <!-- Header of Panduan Cepat (Without button) -->
            <div class="mb-4 sm:mb-5">
                <h2 class="text-xl font-extrabold tracking-tight text-navy-950 sm:text-2xl">Panduan cepat</h2>
                <p class="mt-0.5 text-xs sm:text-sm text-slate-500">Ikuti langkah-langkah singkat ini untuk membuat laporan di TAMBORA.</p>
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

    <!-- Pertanyaan Umum Section (2-Column Layout matching reference mockup) -->
    <section class="py-10 sm:py-14" id="pertanyaan-umum">
        <div class="public-container">
            <div class="grid items-start gap-8 lg:grid-cols-12 lg:gap-8 xl:gap-10">
                <!-- Left Column: FAQ Accordion Cards with Colored Icons -->
                <div class="lg:col-span-7 xl:col-span-8">
                    <!-- Header Pertanyaan Umum -->
                    <div>
                        <h2 class="text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">Pertanyaan Umum</h2>
                        <p class="mt-1.5 text-sm text-slate-500">Temukan jawaban untuk pertanyaan yang paling sering ditanyakan.</p>
                    </div>

                    <!-- FAQ List with Individual Rounded Cards & Category Icons -->
                    <div class="mt-6 flex flex-col gap-3 sm:gap-3.5">
                        <!-- Q1: Akun & Identitas (Blue User Icon) -->
                        <details class="group rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-4.5 shadow-xs transition-all duration-200 hover:border-blue-200 open:border-blue-200">
                            <summary class="flex cursor-pointer list-none items-center gap-3.5 sm:gap-4 select-none">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                    <svg class="size-4.5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-extrabold text-navy-950 text-sm sm:text-base">Apakah saya harus membuat akun?</span>
                                <span class="grid size-7 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4.5 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed pr-6 pt-3 border-t border-slate-100 pl-12.5">
                                Tidak. TAMBORA tidak meminta akun, nama, NIK, email, atau nomor telepon pelapor.
                            </div>
                        </details>

                        <!-- Q2: Lokasi & Peta (Rose Pin Icon) -->
                        <details class="group rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-4.5 shadow-xs transition-all duration-200 hover:border-blue-200 open:border-blue-200">
                            <summary class="flex cursor-pointer list-none items-center gap-3.5 sm:gap-4 select-none">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-500">
                                    <svg class="size-4.5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-extrabold text-navy-950 text-sm sm:text-base">Bagaimana jika saya sudah meninggalkan lokasi?</span>
                                <span class="grid size-7 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4.5 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed pr-6 pt-3 border-t border-slate-100 pl-12.5">
                                Cari nama wilayah, jalan, desa, kecamatan, atau patokan. Setelah hasil tampil, geser pin ke lokasi kejadian yang paling akurat.
                            </div>
                        </details>

                        <!-- Q3: Bukti Laporan (Emerald Photo Icon) -->
                        <details class="group rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-4.5 shadow-xs transition-all duration-200 hover:border-blue-200 open:border-blue-200">
                            <summary class="flex cursor-pointer list-none items-center gap-3.5 sm:gap-4 select-none">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                    <svg class="size-4.5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-extrabold text-navy-950 text-sm sm:text-base">Apakah bukti wajib dilampirkan?</span>
                                <span class="grid size-7 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4.5 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed pr-6 pt-3 border-t border-slate-100 pl-12.5">
                                Ya. Setiap laporan wajib menyertakan minimal satu foto atau PDF sebagai dasar verifikasi. Utamakan keselamatan dan jangan mengambil bukti langsung jika situasinya berisiko.
                            </div>
                        </details>

                        <!-- Q4: Cek Status (Purple Chart Icon) -->
                        <details class="group rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-4.5 shadow-xs transition-all duration-200 hover:border-blue-200 open:border-blue-200">
                            <summary class="flex cursor-pointer list-none items-center gap-3.5 sm:gap-4 select-none">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                                    <svg class="size-4.5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M4 9h4v11H4zm6-5h4v16h-4zm6 8h4v8h-4z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-extrabold text-navy-950 text-sm sm:text-base">Bagaimana saya mengetahui perkembangan laporan?</span>
                                <span class="grid size-7 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4.5 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed pr-6 pt-3 border-t border-slate-100 pl-12.5">
                                Buka halaman Cek status, lalu masukkan kode laporan dan PIN enam digit yang ditampilkan setelah laporan dikirim.
                            </div>
                        </details>

                        <!-- Q5: Tanya Jawab Petugas (Amber Chat Icon) -->
                        <details class="group rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-4.5 shadow-xs transition-all duration-200 hover:border-blue-200 open:border-blue-200">
                            <summary class="flex cursor-pointer list-none items-center gap-3.5 sm:gap-4 select-none">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                    <svg class="size-4.5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-extrabold text-navy-950 text-sm sm:text-base">Bisakah saya menjawab pertanyaan petugas?</span>
                                <span class="grid size-7 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4.5 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed pr-6 pt-3 border-t border-slate-100 pl-12.5">
                                Bisa. Setelah membuka progres laporan, gunakan kotak komunikasi anonim untuk membaca dan membalas pesan petugas.
                            </div>
                        </details>

                        <!-- Q6: Keamanan & PIN (Pink Lock Icon) -->
                        <details class="group rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-4.5 shadow-xs transition-all duration-200 hover:border-blue-200 open:border-blue-200">
                            <summary class="flex cursor-pointer list-none items-center gap-3.5 sm:gap-4 select-none">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-pink-100 text-pink-600">
                                    <svg class="size-4.5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 font-extrabold text-navy-950 text-sm sm:text-base">Apa yang harus dilakukan jika kode atau PIN hilang?</span>
                                <span class="grid size-7 shrink-0 place-items-center rounded-full text-slate-400 group-hover:text-blue-600 transition-colors">
                                    <svg class="size-4.5 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed pr-6 pt-3 border-t border-slate-100 pl-12.5">
                                Akses tidak dapat dipulihkan karena sistem tidak menyimpan identitas atau kontak pelapor. Simpan kode dan PIN di tempat yang aman.
                            </div>
                        </details>
                    </div>
                </div>

                <!-- Right Column: 2 Stacked Cards (Masih Bingung & Siap Menyampaikan Laporan) -->
                <div class="lg:col-span-5 xl:col-span-4 flex flex-col gap-4 sm:gap-5">
                    <!-- Card 1: Masih bingung? -->
                    <div class="relative overflow-hidden rounded-3xl border border-blue-100/90 bg-gradient-to-br from-[#eff6ff] via-[#f6faff] to-[#eaf3fe] p-6 shadow-sm">
                        <!-- Decorative Guide Tablet Illustration on Right -->
                        <div class="absolute -right-2 top-1/2 -translate-y-1/2 pointer-events-none w-28 sm:w-32 h-28 sm:h-32 opacity-95">
                            <svg viewBox="0 0 120 120" fill="none" class="w-full h-full">
                                <circle cx="60" cy="60" r="45" fill="#93c5fd" fill-opacity="0.3" filter="blur(8px)"/>
                                <rect x="25" y="20" width="65" height="80" rx="14" fill="#ffffff" stroke="#bfdbfe" stroke-width="2"/>
                                <rect x="38" y="36" width="38" height="34" rx="8" fill="#2563eb"/>
                                <path d="M47 48C49 46 52 46 54 47C56 46 59 46 61 48V58C59 56 56 56 54 57C52 56 49 56 47 58V48Z" fill="#ffffff"/>
                                <path d="M88 20L91 26M97 28L92 31M99 38L93 37" stroke="#60a5fa" stroke-width="2.5" stroke-linecap="round"/>
                                <path d="M76 68L88 88L82 90L77 80L71 85L76 68Z" fill="#2563eb" stroke="#ffffff" stroke-width="2" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <!-- Card 1 Content -->
                        <div class="relative z-10">
                            <div class="flex items-center gap-2">
                                <div class="flex size-7 items-center justify-center rounded-full bg-blue-100 text-blue-700 shrink-0">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 18v-6a9 9 0 0 1 18 0v6"/>
                                        <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-navy-950">Masih bingung?</h3>
                            </div>
                            <p class="mt-2 text-xs sm:text-[13px] leading-relaxed text-slate-600 max-w-[210px] sm:max-w-[240px]">
                                Lihat panduan langkah demi langkah untuk membuat laporan di TAMBORA.
                            </p>
                            <div class="mt-4">
                                <a href="#panduan-cepat" class="inline-flex items-center gap-1.5 rounded-full bg-white px-4 py-2 text-xs sm:text-sm font-bold text-blue-700 shadow-xs border border-blue-100 hover:bg-blue-50 transition-colors">
                                    <span>Lihat panduan lengkap</span>
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Siap menyampaikan laporan? -->
                    <div class="relative overflow-hidden rounded-3xl bg-blue-700 p-6 shadow-md text-white">
                        <!-- Decorative Document Illustration on Right -->
                        <div class="hidden sm:block absolute right-3 top-3 pointer-events-none w-24 h-24 opacity-80">
                            <svg viewBox="0 0 100 100" fill="none" class="w-full h-full">
                                <rect x="20" y="16" width="52" height="66" rx="10" fill="#ffffff" fill-opacity="0.2" stroke="#ffffff" stroke-width="1.5" stroke-opacity="0.4"/>
                                <rect x="30" y="28" width="32" height="4" rx="2" fill="#ffffff" fill-opacity="0.6"/>
                                <rect x="30" y="38" width="24" height="4" rx="2" fill="#ffffff" fill-opacity="0.4"/>
                                <circle cx="62" cy="64" r="16" fill="#2563eb" stroke="#ffffff" stroke-width="2.5"/>
                                <path d="M56 64L60 68L68 59" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <!-- Card 2 Content -->
                        <div class="relative z-10">
                            <div class="flex items-start gap-3">
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-blue-600 border border-blue-500/80 text-white shadow-xs">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m22 2-7 20-4-9-9-4Z"/>
                                        <path d="M22 2 11 13"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-extrabold text-white">Siap menyampaikan laporan?</h3>
                                    <p class="mt-1 text-xs text-blue-100/90 leading-relaxed max-w-[210px] sm:max-w-none">Pastikan informasi disampaikan dengan itikad baik.</p>
                                </div>
                            </div>
                            <div class="mt-5 w-full">
                                <a href="{{ route('reports.create') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-white py-3 px-5 text-sm sm:text-base font-extrabold text-blue-700 shadow-sm hover:bg-blue-50 transition-all">
                                    <span>Buat laporan anonim</span>
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
