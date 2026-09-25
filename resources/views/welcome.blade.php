<x-layouts.public title="Lapor KUPVA secara aman">
    <!-- Hero Section (Fresh, Modern Light Palette, No Heavy Navy) -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[#F8FAFC] via-[#F1F5F9] to-white text-[#0F172A] border-b border-[#E2E8F0]">
        <!-- Subtle ambient radial lighting -->
        <div class="pointer-events-none absolute -right-24 -top-24 size-96 rounded-full bg-blue-500/10 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-20 bottom-0 size-80 rounded-full bg-teal-500/5 blur-3xl" aria-hidden="true"></div>

        <div class="public-container relative py-16 sm:py-20 lg:py-24 text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 rounded-full border border-blue-200/80 bg-blue-50/80 px-3.5 py-1.5 text-xs font-bold text-[#2563EB]">
                <span class="size-1.5 rounded-full bg-[#2563EB]"></span>
                <span>Kanal Pelaporan Resmi Wilayah NTB</span>
            </div>

            <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl lg:text-5xl text-[#0F172A]">
                Berani melapor,<br>
                <span class="text-[#2563EB]">bersama menjaga NTB.</span>
            </h1>

            <p class="mt-4 text-xs sm:text-sm lg:text-base leading-relaxed text-[#64748B] max-w-2xl mx-auto">
                Laporkan dugaan money changer (KUPVA) tidak berizin atau transaksi mencurigakan di NTB secara cepat, aman, dan 100% anonim.
            </p>

            <!-- Actions (No arrows) -->
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('reports.create') }}" class="button-primary px-6 py-3 text-xs sm:text-sm font-semibold rounded-xl bg-[#2563EB] text-white hover:bg-[#1D4ED8] transition-colors shadow-xs">
                    <span>Buat laporan</span>
                </a>
                <a href="{{ route('reports.track') }}" class="button-secondary px-5 py-3 text-xs sm:text-sm font-semibold rounded-xl border border-[#CBD5E1] bg-white text-[#0F172A] hover:bg-slate-50 transition-colors shadow-2xs">
                    <span>Cek status</span>
                </a>
            </div>

            <!-- Trust Line -->
            <p class="mt-8 text-xs font-semibold text-[#64748B]">
                Tanpa nama &amp; NIK &nbsp;·&nbsp; Titik peta akurat &nbsp;·&nbsp; Progres transparan
            </p>
        </div>
    </section>

    <!-- Cara Kerja Section -->
    <section id="cara-kerja" class="scroll-mt-20 py-16 sm:py-24 bg-white">
        <div class="public-container">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl sm:text-4xl lg:text-[40px] font-extrabold tracking-tight text-[#0F172A]">Cara melapor di TAMBORA</h2>
                <p class="mt-3 text-sm sm:text-base leading-relaxed text-[#64748B]">Gampang, kok. Cukup buka web, ceritakan, tunjukkan lokasinya, tambahkan foto (jika ada), lalu pantau perkembangannya.</p>
            </div>

            <!-- 5 Step Cards (Desktop 5-col row, Mobile 2-col grid) -->
            <div class="mt-14 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-5">
                <!-- Card 1 -->
                <article class="relative flex flex-col items-center justify-between rounded-3xl border border-[#E2E8F0] bg-white p-5 pt-8 text-center shadow-xs transition-all hover:shadow-md hover:border-[#2563EB]/40">
                    <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 flex size-7 items-center justify-center rounded-full bg-[#2563EB] text-xs font-bold text-white shadow-sm ring-4 ring-white">01</span>
                    <div class="flex h-24 items-center justify-center">
                        <svg class="h-20 w-auto" viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="20" y="8" width="80" height="52" rx="6" fill="#0F172A" stroke="#CBD5E1" stroke-width="2"/>
                            <rect x="25" y="13" width="70" height="42" rx="3" fill="#F8FAFC"/>
                            <path d="M60 22L70 26V35C70 41 65.5 45.5 60 47.5C54.5 45.5 50 41 50 35V26L60 22Z" fill="#2563EB"/>
                            <path d="M60 22V47.5C65.5 45.5 70 41 70 35V26L60 22Z" fill="#1D4ED8"/>
                            <path d="M10 63H110L103 68H17L10 63Z" fill="#CBD5E1"/>
                            <rect x="50" y="63" width="20" height="2" rx="1" fill="#94A3B8"/>
                        </svg>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-sm sm:text-base font-bold text-[#0F172A]">Buka websitenya</h3>
                        <p class="mt-2 text-xs sm:text-[13px] leading-relaxed text-[#64748B]">Buka laporkupva.ikydev.com lewat HP atau laptop tanpa perlu login.</p>
                    </div>
                </article>

                <!-- Card 2 -->
                <article class="relative flex flex-col items-center justify-between rounded-3xl border border-[#E2E8F0] bg-white p-5 pt-8 text-center shadow-xs transition-all hover:shadow-md hover:border-[#2563EB]/40">
                    <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 flex size-7 items-center justify-center rounded-full bg-[#2563EB] text-xs font-bold text-white shadow-sm ring-4 ring-white">02</span>
                    <div class="flex h-24 items-center justify-center">
                        <svg class="h-20 w-auto" viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="52" y="10" width="56" height="34" rx="8" fill="#E0F2FE"/>
                            <rect x="60" y="18" width="40" height="4" rx="2" fill="#38BDF8"/>
                            <rect x="60" y="26" width="28" height="4" rx="2" fill="#7DD3FC"/>
                            <path d="M52 32L46 36L52 38V32Z" fill="#E0F2FE"/>
                            <circle cx="38" cy="38" r="16" fill="#0F172A"/>
                            <circle cx="43" cy="36" r="8" fill="#FED7AA"/>
                            <path d="M30 40C30 30 38 24 46 26C52 28 54 36 52 42C50 48 42 52 36 50C32 48 30 44 30 40Z" fill="#0F172A"/>
                            <circle cx="46" cy="36" r="1.5" fill="#0F172A"/>
                            <path d="M44 40Q47 43 49 40" stroke="#0F172A" stroke-width="1.2" stroke-linecap="round"/>
                            <path d="M22 66C22 56 30 52 40 52C50 52 58 56 58 66V68H22V66Z" fill="#2563EB"/>
                            <rect x="50" y="48" width="12" height="20" rx="3" fill="#0284C7"/>
                        </svg>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-sm sm:text-base font-bold text-[#0F172A]">Ceritakan kejadiannya</h3>
                        <p class="mt-2 text-xs sm:text-[13px] leading-relaxed text-[#64748B]">Pilih masalahnya dan ceritakan apa yang terjadi secara santai dan jujur.</p>
                    </div>
                </article>

                <!-- Card 3 -->
                <article class="relative flex flex-col items-center justify-between rounded-3xl border border-[#E2E8F0] bg-white p-5 pt-8 text-center shadow-xs transition-all hover:shadow-md hover:border-[#2563EB]/40">
                    <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 flex size-7 items-center justify-center rounded-full bg-[#2563EB] text-xs font-bold text-white shadow-sm ring-4 ring-white">03</span>
                    <div class="flex h-24 items-center justify-center">
                        <svg class="h-20 w-auto" viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25 24L48 16L72 24L95 16V56L72 64L48 56L25 64V24Z" fill="#F1F5F9" stroke="#CBD5E1" stroke-width="1.5"/>
                            <path d="M25 24L48 16V56L25 64V24Z" fill="#E2E8F0"/>
                            <path d="M48 16L72 24V64L48 56V16Z" fill="#F8FAFC"/>
                            <path d="M72 24L95 16V56L72 64V24Z" fill="#E2E8F0"/>
                            <path d="M30 38C36 34 42 40 48 36" stroke="#94A3B8" stroke-width="2" stroke-linecap="round"/>
                            <path d="M52 46C58 42 66 48 72 44" stroke="#94A3B8" stroke-width="2" stroke-linecap="round"/>
                            <path d="M78 30C86 28 92 34 92 42C92 48 84 52 78 50V30Z" fill="#DCFCE7" opacity="0.7"/>
                            <ellipse cx="60" cy="58" rx="14" ry="4" fill="#64748B" opacity="0.2"/>
                            <path d="M60 22C52 22 46 28 46 36C46 47 60 60 60 60C60 60 74 47 74 36C74 28 68 22 60 22Z" fill="#2563EB"/>
                            <circle cx="60" cy="35" r="5" fill="white"/>
                        </svg>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-sm sm:text-base font-bold text-[#0F172A]">Tentukan lokasinya</h3>
                        <p class="mt-2 text-xs sm:text-[13px] leading-relaxed text-[#64748B]">Tunjukkan titik tempat kejadiannya langsung di peta wilayah NTB.</p>
                    </div>
                </article>

                <!-- Card 4 -->
                <article class="relative flex flex-col items-center justify-between rounded-3xl border border-[#E2E8F0] bg-white p-5 pt-8 text-center shadow-xs transition-all hover:shadow-md hover:border-[#2563EB]/40">
                    <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 flex size-7 items-center justify-center rounded-full bg-[#2563EB] text-xs font-bold text-white shadow-sm ring-4 ring-white">04</span>
                    <div class="flex h-24 items-center justify-center">
                        <svg class="h-20 w-auto" viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g transform="rotate(-6 50 40)">
                                <rect x="25" y="14" width="46" height="42" rx="4" fill="white" stroke="#CBD5E1" stroke-width="1.5"/>
                                <rect x="28" y="17" width="40" height="30" rx="2" fill="#E0F2FE"/>
                                <path d="M34 38L42 28L50 36L56 30L64 38H34Z" fill="#60A5FA"/>
                            </g>
                            <g transform="rotate(4 65 42)">
                                <rect x="42" y="16" width="48" height="44" rx="4" fill="white" stroke="#CBD5E1" stroke-width="1.5"/>
                                <rect x="45" y="19" width="42" height="32" rx="2" fill="#EFF6FF"/>
                                <circle cx="56" cy="27" r="3.5" fill="#F59E0B"/>
                                <path d="M48 44L58 32L68 42L74 36L84 44H48Z" fill="#3B82F6"/>
                            </g>
                            <circle cx="82" cy="56" r="10" fill="#2563EB" stroke="white" stroke-width="2"/>
                            <path d="M82 51V61M77 56H87" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-sm sm:text-base font-bold text-[#0F172A]">Tambahkan foto</h3>
                        <p class="mt-2 text-xs sm:text-[13px] leading-relaxed text-[#64748B]">Ada foto plang atau bukti nota? Boleh dikirim, atau lewati jika tidak ada.</p>
                    </div>
                </article>

                <!-- Card 5 -->
                <article class="col-span-2 sm:col-span-1 lg:col-span-1 relative flex flex-col items-center justify-between rounded-3xl border border-[#E2E8F0] bg-white p-5 pt-8 text-center shadow-xs transition-all hover:shadow-md hover:border-[#2563EB]/40">
                    <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 flex size-7 items-center justify-center rounded-full bg-[#2563EB] text-xs font-bold text-white shadow-sm ring-4 ring-white">05</span>
                    <div class="flex h-24 items-center justify-center">
                        <svg class="h-20 w-auto" viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="44" y="8" width="36" height="64" rx="7" fill="#0F172A" stroke="#CBD5E1" stroke-width="2"/>
                            <rect x="47" y="13" width="30" height="50" rx="4" fill="#F8FAFC"/>
                            <rect x="58" y="10" width="8" height="2" rx="1" fill="#64748B"/>
                            <path d="M62 26L69 29V36C69 41 65.5 44 62 45.5C58.5 44 55 41 55 36V29L62 26Z" fill="#2563EB"/>
                            <circle cx="62" cy="34" r="2" fill="white"/>
                            <rect x="60.5" y="34" width="3" height="3.5" rx="0.8" fill="white"/>
                            <circle cx="76" cy="48" r="9" fill="#10B981" stroke="white" stroke-width="2"/>
                            <path d="M72.5 48L75 50.5L79.5 45.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-sm sm:text-base font-bold text-[#0F172A]">Pantau laporanmu</h3>
                        <p class="mt-2 text-xs sm:text-[13px] leading-relaxed text-[#64748B]">Simpan Kode Laporan dan PIN rahasiamu untuk melihat perkembangan laporanmu.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Keamanan & Privasi Section -->
    <section id="keamanan" class="scroll-mt-20 py-16 sm:py-24 bg-[#F8FAFC]">
        <div class="public-container grid items-center gap-10 lg:grid-cols-2">
            <!-- Left Side: 2x2 Grid of Rounded White Cards -->
            <div class="grid gap-4 sm:gap-5 sm:grid-cols-2 order-2 lg:order-1">
                <!-- Card 1 -->
                <article class="flex flex-col justify-between rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs transition-all hover:shadow-md">
                    <div>
                        <div class="grid size-12 place-items-center rounded-2xl bg-blue-50 text-[#2563EB]">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-[#0F172A]">100% Anonim &amp; Tanpa Akun</h3>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-[#64748B]">Tidak perlu daftar akun. Kami sama sekali tidak meminta nama, KTP, nomor HP, maupun email Anda.</p>
                    </div>
                </article>

                <!-- Card 2 -->
                <article class="flex flex-col justify-between rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs transition-all hover:shadow-md">
                    <div>
                        <div class="grid size-12 place-items-center rounded-2xl bg-blue-50 text-[#2563EB]">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="7.5" cy="15.5" r="5.5"/>
                                <path d="m21 2-9.6 9.6"/>
                                <path d="m15.5 7.5 3 3L22 7l-3-3"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-[#0F172A]">Pantau Pakai Kode Rahasia</h3>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-[#64748B]">Anda mendapatkan kode acak dan PIN untuk mengecek status tindak lanjut tanpa meninggalkan jejak.</p>
                    </div>
                </article>

                <!-- Card 3 -->
                <article class="flex flex-col justify-between rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs transition-all hover:shadow-md">
                    <div>
                        <div class="grid size-12 place-items-center rounded-2xl bg-blue-50 text-[#2563EB]">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-[#0F172A]">Hanya Dibaca Petugas Resmi</h3>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-[#64748B]">Laporan Anda dijaga ketat dan hanya dibuka oleh petugas resmi Bank Indonesia, tidak ke umum.</p>
                    </div>
                </article>

                <!-- Card 4: Action Card -->
                <article class="flex flex-col justify-between rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs transition-all hover:shadow-md">
                    <div>
                        <div class="grid size-12 place-items-center rounded-2xl bg-[#2563EB] text-white">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m22 2-7 20-4-9-9-4Z"/>
                                <path d="M22 2 11 13"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-[#0F172A]">Mulai Buat Laporan</h3>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-[#64748B]">Sampaikan laporan KUPVA sekarang secara anonim, cepat, dan terlindungi.</p>
                    </div>
                    <div class="mt-5">
                        <a href="{{ route('reports.create') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-[#2563EB] px-4 py-2.5 text-xs sm:text-sm font-semibold text-white hover:bg-[#1D4ED8] transition-colors shadow-2xs">
                            <span>Mulai membuat laporan</span>
                        </a>
                    </div>
                </article>
            </div>

            <!-- Right Side: Artwork & Messaging -->
            <div class="order-1 lg:order-2 flex flex-col items-center lg:items-start text-center lg:text-left">
                <!-- Rich Security Illustration -->
                <div class="mb-6 flex justify-center w-full max-w-sm">
                    <svg class="h-44 sm:h-52 w-auto" viewBox="0 0 240 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background glow circle -->
                        <circle cx="120" cy="90" r="70" fill="#EFF6FF"/>
                        <!-- Floating security badges -->
                        <g transform="translate(30, 40)">
                            <rect width="32" height="32" rx="8" fill="white" stroke="#E2E8F0" stroke-width="1.5"/>
                            <path d="M16 8L22 11V16C22 20 19 22.5 16 24C13 22.5 10 20 10 16V11L16 8Z" fill="#3B82F6"/>
                        </g>
                        <g transform="translate(180, 50)">
                            <rect width="30" height="30" rx="8" fill="white" stroke="#E2E8F0" stroke-width="1.5"/>
                            <circle cx="13" cy="17" r="4" fill="#60A5FA"/>
                            <path d="M16 14L21 9M18 12L20 10" stroke="#60A5FA" stroke-width="1.5" stroke-linecap="round"/>
                        </g>
                        <!-- Big Smartphone -->
                        <rect x="85" y="20" width="70" height="140" rx="14" fill="#0F172A" stroke="#CBD5E1" stroke-width="3"/>
                        <rect x="90" y="28" width="60" height="124" rx="8" fill="#F8FAFC"/>
                        <rect x="110" y="24" width="20" height="3" rx="1.5" fill="#64748B"/>
                        <!-- Shield on phone screen -->
                        <path d="M120 50L138 58V76C138 88 129 97 120 101C111 97 102 88 102 76V58L120 50Z" fill="#2563EB"/>
                        <circle cx="120" cy="72" r="5" fill="white"/>
                        <rect x="117.5" y="72" width="5" height="7" rx="1.5" fill="white"/>
                        <!-- Green verified circle on phone -->
                        <circle cx="138" cy="94" r="10" fill="#10B981" stroke="white" stroke-width="2"/>
                        <path d="M134 94L137 97L142 91" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <!-- Blue Padlock Floating -->
                        <rect x="135" y="115" width="28" height="24" rx="5" fill="#1D4ED8"/>
                        <path d="M141 115V110C141 106 145 103 149 103C153 103 157 106 157 110V115" stroke="#1D4ED8" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="149" cy="126" r="2.5" fill="white"/>
                    </svg>
                </div>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#0F172A] leading-tight">
                    Lapor dengan tenang, identitas tetap aman.
                </h2>
                <p class="mt-4 text-xs sm:text-sm lg:text-base leading-relaxed text-[#64748B]">
                    TAMBORA dirancang agar masyarakat dapat berpartisipasi mengawasi KUPVA tidak berizin di NTB tanpa rasa khawatir. Sistem hanya mengumpulkan data kejadian yang diperlukan tanpa melacak identitas pelapor.
                </p>
            </div>
        </div>
    </section>

    <!-- Modern Bottom CTA Banner (No Oppressive Navy Block) -->
    <section class="py-12 bg-white border-t border-[#E2E8F0]">
        <div class="public-container">
            <div class="rounded-3xl bg-gradient-to-r from-blue-600 to-[#1D4ED8] p-8 sm:p-10 text-white shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <span class="inline-block rounded-full bg-white/15 px-3 py-1 text-xs font-bold text-amber-300 backdrop-blur-xs">
                        Sudah pernah melapor?
                    </span>
                    <h2 class="mt-2.5 text-xl sm:text-2xl font-bold text-white">
                        Pantau perkembangan tindak lanjut laporan Anda.
                    </h2>
                    <p class="mt-1 text-xs sm:text-sm text-blue-100">
                        Masukkan kode laporan dan PIN rahasia untuk memeriksa status terkini.
                    </p>
                </div>
                <a href="{{ route('reports.track') }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-white px-6 py-3 text-xs sm:text-sm font-bold text-[#1D4ED8] hover:bg-slate-100 transition-colors shadow-xs">
                    <span>Cek status</span>
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
