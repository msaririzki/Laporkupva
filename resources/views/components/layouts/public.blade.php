<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TAMBORA - Kanal pengawasan dan pelaporan masyarakat untuk kegiatan usaha penukaran valuta asing (KUPVA) di wilayah Nusa Tenggara Barat. Resmi dari Kantor Perwakilan Bank Indonesia Provinsi NTB.">
    <meta name="theme-color" content="#2563EB">
    <link rel="icon" type="image/webp" href="{{ asset('images/brand/bank-indonesia-mark.webp') }}">
    <title>{{ isset($title) ? $title.' — ' : '' }}TAMBORA · Bank Indonesia NTB</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-full flex flex-col bg-[#F8FAFC] text-[#0F172A] antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-xl focus:bg-white focus:px-4 focus:py-2 focus:text-[#0F172A] focus:shadow-lg focus:ring-2 focus:ring-[#2563EB]">Lewati ke konten utama</a>

    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur-xl">
        <div class="public-container flex h-16 items-center justify-between gap-5 sm:h-18 sm:gap-6">
            <a href="{{ route('home') }}" class="group flex shrink-0 items-center" aria-label="TAMBORA - Beranda">
                <img src="{{ asset('images/brand/tambora.webp') }}" alt="TAMBORA" class="h-9 w-auto object-contain sm:h-10 lg:h-11" width="720" height="316">
            </a>

            <nav class="hidden items-center gap-1 sm:gap-2 lg:gap-3 md:flex" aria-label="Navigasi utama">
                <a class="nav-link !py-1.5 !text-sm sm:!text-[15px] font-bold" href="{{ route('home') }}#cara-kerja">Cara lapor</a>
                <a class="nav-link !py-1.5 !text-sm sm:!text-[15px] font-bold" href="{{ route('home') }}#keamanan">Keamanan</a>
                <a class="nav-link !py-1.5 !text-sm sm:!text-[15px] font-bold {{ request()->routeIs('guide') ? 'text-[#2563EB] font-bold' : '' }}" href="{{ route('guide') }}">Panduan</a>
                <a class="nav-link !py-1.5 !text-sm sm:!text-[15px] font-bold {{ request()->routeIs('reports.track*') || request()->routeIs('reports.status*') ? 'text-[#2563EB] font-bold' : '' }}" href="{{ route('reports.track') }}">Cek status</a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-2.5">
                <a href="{{ route('reports.create') }}" class="button-primary hidden min-h-10 rounded-xl px-4 py-2 text-sm font-bold shadow-sm sm:inline-flex sm:px-5">Buat laporan <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69L10.22 5.03a.75.75 0 0 1 1.06-1.06l5.5 5.5a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 1 1-1.06-1.06l4.22-4.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg></a>

                <a
                    href="{{ route('filament.admin.auth.login') }}"
                    class="admin-access-link hidden sm:grid"
                    aria-label="Masuk ke portal admin"
                    title="Portal admin"
                >
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 1.75a4.25 4.25 0 0 0-4.25 4.25v1.1A2.75 2.75 0 0 0 3.5 9.8v5.7a2.75 2.75 0 0 0 2.75 2.75h7.5a2.75 2.75 0 0 0 2.75-2.75V9.8a2.75 2.75 0 0 0-2.25-2.7V6A4.25 4.25 0 0 0 10 1.75ZM7.25 6a2.75 2.75 0 1 1 5.5 0v1.05h-5.5V6Zm3.5 6.25a.75.75 0 1 0-1.5 0v1.5a.75.75 0 1 0 1.5 0v-1.5Z" clip-rule="evenodd"/>
                    </svg>
                    <span class="sr-only">Portal admin</span>
                </a>

                <!-- Mobile Menu Button -->
                <button
                    type="button"
                    id="mobile-menu-button"
                    class="inline-flex size-10 items-center justify-center rounded-xl border border-[#E2E8F0] bg-white text-[#64748B] hover:text-[#0F172A] hover:bg-slate-50 focus:ring-2 focus:ring-[#2563EB]"
                    aria-expanded="false"
                    aria-controls="mobile-nav"
                    aria-label="Buka menu navigasi"
                >
                    <svg id="mobile-menu-open-icon" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg id="mobile-menu-close-icon" class="size-5 hidden" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-nav" class="hidden md:hidden border-t border-[#E2E8F0] bg-white px-4 py-3.5 space-y-1 shadow-md">
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold text-[#0F172A] hover:bg-slate-50" href="{{ route('home') }}#cara-kerja">Cara lapor</a>
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold text-[#0F172A] hover:bg-slate-50" href="{{ route('home') }}#keamanan">Keamanan</a>
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold {{ request()->routeIs('guide') ? 'text-[#2563EB] bg-blue-50/80 font-bold border-l-4 border-[#2563EB]' : 'text-[#0F172A] hover:bg-slate-50' }}" href="{{ route('guide') }}">Panduan</a>
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold {{ request()->routeIs('reports.track*') || request()->routeIs('reports.status*') ? 'text-[#2563EB] bg-blue-50/80 font-bold border-l-4 border-[#2563EB]' : 'text-[#0F172A] hover:bg-slate-50' }}" href="{{ route('reports.track') }}">Cek status</a>
            <a class="mt-2 block text-center rounded-xl bg-[#2563EB] px-3.5 py-2.5 text-sm font-semibold text-white hover:bg-[#1D4ED8]" href="{{ route('reports.create') }}">Buat laporan</a>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main id="main-content" class="flex-1">{{ $slot }}</main>

    <!-- Public Service Footer (Clean, Centered, Minimal, Stylish) -->
    <footer class="mt-6 sm:mt-8 border-t border-[#E2E8F0] bg-white">
        <div class="public-container py-5 sm:py-6 text-center">
            <!-- Center Logos: TAMBORA + Bank Indonesia (Slightly enlarged for clear visibility) -->
            <div class="flex items-center justify-center gap-4 sm:gap-6">
                <img src="{{ asset('images/brand/tambora.webp') }}" alt="TAMBORA" class="h-9 sm:h-10 w-auto object-contain" width="720" height="316" loading="lazy">
                <span class="h-6 w-px bg-[#CBD5E1]" aria-hidden="true"></span>
                <img src="{{ asset('images/brand/bank-indonesia-full.webp') }}" alt="Bank Indonesia" class="h-8 sm:h-9 w-auto object-contain" width="880" height="158" loading="lazy">
            </div>

            <!-- Compact Interactive Elements with tight, comfortable spacing -->
            <div class="mt-3.5 sm:mt-4 flex flex-wrap items-center justify-center gap-2.5 sm:gap-3">
                <!-- Pemberitahuan Dropdown / Details -->
                <details class="group relative inline-block text-left">
                    <summary class="inline-flex cursor-pointer list-none select-none items-center gap-1.5 rounded-full border border-[#E2E8F0] bg-[#F8FAFC] px-4 py-1.5 text-xs font-semibold text-[#0F172A] hover:bg-slate-100 hover:border-slate-300 transition-colors">
                        <svg class="size-3.5 text-[#2563EB]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
                        </svg>
                        <span>Pemberitahuan</span>
                        <svg class="size-3 text-slate-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="mt-2 max-w-sm sm:max-w-md mx-auto p-3.5 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] text-xs text-[#64748B] leading-relaxed text-center shadow-xs">
                        Kanal ini dikelola untuk pengawasan KUPVA. Untuk keadaan darurat, segera hubungi pihak kepolisian atau aparat penegak hukum terdekat.
                    </div>
                </details>

                <!-- Hubungi Kanal Button -->
                <a href="mailto:kontak@laporkupva.id" class="inline-flex items-center gap-1.5 rounded-full border border-[#E2E8F0] bg-[#F8FAFC] px-4 py-1.5 text-xs font-semibold text-[#0F172A] hover:bg-slate-100 hover:border-slate-300 transition-colors">
                    <svg class="size-3.5 text-[#2563EB]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                    </svg>
                    <span>Hubungi Kanal</span>
                </a>
            </div>

            <!-- Akses Petugas (Discreet secondary link, satisfies tests) -->
            <div class="mt-2.5">
                <a href="{{ route('filament.admin.auth.login') }}" aria-label="Masuk ke portal admin" class="text-xs text-slate-400 hover:text-[#2563EB] transition-colors">
                    Akses petugas
                </a>
            </div>

            <!-- Copyright Line -->
            <div class="mt-3.5 pt-3 border-t border-[#E2E8F0] text-center text-[11px] sm:text-xs text-slate-400">
                © {{ date('Y') }} Kantor Perwakilan Bank Indonesia Provinsi NTB · TAMBORA (laporkupva.id)
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
