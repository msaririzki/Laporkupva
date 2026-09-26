<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TAMBORA - Kanal pengawasan dan pelaporan masyarakat untuk kegiatan usaha penukaran valuta asing (KUPVA) di wilayah Nusa Tenggara Barat. Resmi dari Kantor Perwakilan Bank Indonesia Provinsi NTB.">
    <meta name="theme-color" content="#0B2342">
    <link rel="icon" type="image/webp" href="{{ asset('images/brand/bank-indonesia-mark.webp') }}">
    <title>{{ isset($title) ? $title.' — ' : '' }}TAMBORA · Bank Indonesia NTB</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-full flex flex-col bg-[#F7F9FC] text-[#0B2342] antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-xl focus:bg-white focus:px-4 focus:py-2 focus:text-[#0B2342] focus:shadow-lg focus:ring-2 focus:ring-[#2563EB]">Lewati ke konten utama</a>

    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur-xl">
        <div class="public-container flex h-19 sm:h-20 items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="group flex shrink-0 items-center" aria-label="TAMBORA - Beranda">
                <img src="{{ asset('images/brand/tambora.webp') }}" alt="TAMBORA" class="h-11 w-auto object-contain sm:h-13 lg:h-14" width="720" height="316">
            </a>

            <nav class="hidden items-center gap-1 sm:gap-2 lg:gap-3 md:flex" aria-label="Navigasi utama">
                <a class="nav-link !text-sm sm:!text-[15px] font-bold" href="{{ route('home') }}#cara-kerja">Cara lapor</a>
                <a class="nav-link !text-sm sm:!text-[15px] font-bold" href="{{ route('home') }}#keamanan">Keamanan</a>
                <a class="nav-link !text-sm sm:!text-[15px] font-bold {{ request()->routeIs('guide') ? 'text-[#2563EB] font-bold' : '' }}" href="{{ route('guide') }}">Panduan</a>
                <a class="nav-link !text-sm sm:!text-[15px] font-bold {{ request()->routeIs('reports.track*') || request()->routeIs('reports.status*') ? 'text-[#2563EB] font-bold' : '' }}" href="{{ route('reports.track') }}">Cek status</a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-2.5">
                <a href="{{ route('reports.create') }}" class="button-primary hidden sm:inline-flex min-h-11 px-5 sm:px-6 py-2.5 text-sm sm:text-[15px] font-bold rounded-xl shadow-sm">Buat laporan <svg class="size-4.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69L10.22 5.03a.75.75 0 0 1 1.06-1.06l5.5 5.5a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 1 1-1.06-1.06l4.22-4.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg></a>

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
                    class="md:hidden inline-flex size-10 items-center justify-center rounded-xl border border-[#E2E8F0] bg-white text-[#64748B] hover:text-[#0B2342] hover:bg-slate-50 focus:ring-2 focus:ring-[#2563EB]"
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
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold text-[#0B2342] hover:bg-slate-50" href="{{ route('home') }}#cara-kerja">Cara lapor</a>
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold text-[#0B2342] hover:bg-slate-50" href="{{ route('home') }}#keamanan">Keamanan</a>
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold {{ request()->routeIs('guide') ? 'text-[#2563EB] bg-blue-50/80 font-bold border-l-4 border-[#2563EB]' : 'text-[#0B2342] hover:bg-slate-50' }}" href="{{ route('guide') }}">Panduan</a>
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold {{ request()->routeIs('reports.track*') || request()->routeIs('reports.status*') ? 'text-[#2563EB] bg-blue-50/80 font-bold border-l-4 border-[#2563EB]' : 'text-[#0B2342] hover:bg-slate-50' }}" href="{{ route('reports.track') }}">Cek status</a>
            <a class="mt-2 block text-center rounded-xl bg-[#2563EB] px-3.5 py-2.5 text-sm font-semibold text-white hover:bg-[#1D4ED8]" href="{{ route('reports.create') }}">Buat laporan</a>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main id="main-content" class="flex-1">{{ $slot }}</main>

    <!-- Public Service Footer (Compact, Secondary, Perfectly Balanced) -->
    <footer class="mt-16 sm:mt-20 border-t border-[#E2E8F0] bg-white">
        <div class="public-container py-8 sm:py-10">
            <div class="grid gap-6 sm:gap-8 md:grid-cols-12 md:items-start">
                <!-- Brand & Short Description -->
                <div class="md:col-span-6 lg:col-span-5">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <img src="{{ asset('images/brand/tambora.webp') }}" alt="TAMBORA" class="h-7 sm:h-8 w-auto object-contain" width="720" height="316" loading="lazy">
                        <span class="h-5 w-px bg-[#E2E8F0]" aria-hidden="true"></span>
                        <img src="{{ asset('images/brand/bank-indonesia-full.webp') }}" alt="Bank Indonesia" class="h-6 sm:h-7 w-auto object-contain" width="880" height="158" loading="lazy">
                    </div>
                    <p class="mt-2.5 max-w-sm text-xs sm:text-[13px] leading-relaxed text-[#64748B]">Kanal pengawasan dan partisipasi masyarakat untuk pengawasan kegiatan usaha penukaran valuta asing (KUPVA) di wilayah Provinsi Nusa Tenggara Barat.</p>
                </div>

                <!-- Service Links (Compact) -->
                <div class="md:col-span-3 lg:col-span-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#0B2342]">Menu Layanan</p>
                    <nav class="mt-2.5 grid grid-cols-2 sm:grid-cols-1 gap-1.5 sm:gap-2 text-xs sm:text-[13px]" aria-label="Menu layanan footer">
                        <a class="text-[#64748B] hover:text-[#2563EB] transition-colors" href="{{ route('reports.create') }}">Buat laporan anonim</a>
                        <a class="text-[#64748B] hover:text-[#2563EB] transition-colors" href="{{ route('reports.track') }}">Cek status laporan</a>
                        <a class="text-[#64748B] hover:text-[#2563EB] transition-colors" href="{{ route('guide') }}">Panduan penggunaan</a>
                        <a class="text-[#64748B] hover:text-[#2563EB] transition-colors" href="{{ route('privacy') }}">Informasi privasi</a>
                    </nav>
                </div>

                <!-- Notice / Disclaimer -->
                <div class="md:col-span-3 lg:col-span-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#0B2342]">Pemberitahuan</p>
                    <p class="mt-2.5 text-xs sm:text-[13px] leading-relaxed text-[#64748B]">Kanal ini dikelola untuk pengawasan KUPVA. Untuk keadaan darurat, segera hubungi pihak kepolisian atau aparat penegak hukum terdekat.</p>
                </div>
            </div>
        </div>

        <!-- Copyright Line -->
        <div class="border-t border-[#E2E8F0] py-3.5 text-center text-[11px] sm:text-xs text-slate-400">
            © {{ date('Y') }} Kantor Perwakilan Bank Indonesia Provinsi NTB · TAMBORA (laporkupva.id)
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
