<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    @php
        $pageTitle = isset($title) ? $title.' — TAMBORA' : 'TAMBORA · Bank Indonesia NTB';
        $pageDescription = 'TAMBORA - Kanal informasi dan pelaporan masyarakat untuk Money Changer (KUPVA BB) di wilayah Nusa Tenggara Barat.';
        $shareImage = asset('images/brand/tambora.webp');
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="theme-color" content="#0B2342">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TAMBORA">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta property="og:image:type" content="image/webp">
    <meta property="og:image:width" content="720">
    <meta property="og:image:height" content="316">
    <meta property="og:image:alt" content="Logo TAMBORA">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $shareImage }}">
    <link rel="icon" type="image/webp" href="{{ asset('images/brand/tambora.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/tambora.webp') }}">
    <title>{{ $pageTitle }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-full flex flex-col bg-[#F8FAFC] text-[#0F172A] antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-xl focus:bg-white focus:px-4 focus:py-2 focus:text-[#0F172A] focus:shadow-lg focus:ring-2 focus:ring-[#2563EB]">Lewati ke konten utama</a>

    <header class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/88 shadow-[0_8px_30px_-24px_rgba(11,35,66,0.45)] backdrop-blur-xl">
        <div class="public-container flex h-14 items-center justify-between gap-4 sm:h-16 sm:gap-6">
            <a href="{{ route('home') }}" class="group flex shrink-0 items-center" aria-label="TAMBORA - Beranda">
                <img src="{{ asset('images/brand/tambora.webp') }}" alt="TAMBORA" class="h-8 w-auto object-contain sm:h-9 lg:h-10" width="720" height="316">
            </a>

            <nav class="hidden items-center gap-1 sm:gap-2 lg:flex lg:gap-3" aria-label="Navigasi utama">
                <a class="nav-link !py-1.5 !text-sm sm:!text-[15px] font-bold" href="{{ route('home') }}#cara-kerja">Cara lapor</a>
                <a class="nav-link !py-1.5 !text-sm sm:!text-[15px] font-bold {{ request()->routeIs('kupvas.*') ? 'text-[#2563EB] font-bold' : '' }}" href="{{ route('kupvas.index') }}">Money Changer berizin</a>
                <a class="nav-link !py-1.5 !text-sm sm:!text-[15px] font-bold {{ request()->routeIs('guide') ? 'text-[#2563EB] font-bold' : '' }}" href="{{ route('guide') }}">Panduan</a>
                <a class="nav-link !py-1.5 !text-sm sm:!text-[15px] font-bold {{ request()->routeIs('reports.track*') || request()->routeIs('reports.status*') ? 'text-[#2563EB] font-bold' : '' }}" href="{{ route('reports.track') }}">Cek status laporan</a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-2.5">
                @unless (request()->routeIs('reports.create'))
                <a href="{{ route('reports.create') }}" class="inline-flex min-h-9 items-center justify-center gap-1.5 rounded-lg bg-[#2563EB] px-3 text-xs font-bold text-white shadow-sm transition hover:bg-[#1D4ED8] sm:hidden" data-nav-action="create-report">
                    <svg class="size-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" data-nav-icon="create-report">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 2.75h5.5l3 3v4.5M5.75 2.75a1.5 1.5 0 0 0-1.5 1.5v11.5a1.5 1.5 0 0 0 1.5 1.5h4.5m1.25-3h5m-2.5-2.5v5M11.25 2.75v3h3"/>
                    </svg>
                    <span>Lapor</span>
                </a>
                <a href="{{ route('reports.create') }}" class="button-primary hidden min-h-10 rounded-xl px-4 py-2 text-sm font-bold shadow-sm sm:inline-flex sm:px-5" data-nav-action="create-report">
                    <svg class="size-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" data-nav-icon="create-report">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 2.75h5.5l3 3v4.5M5.75 2.75a1.5 1.5 0 0 0-1.5 1.5v11.5a1.5 1.5 0 0 0 1.5 1.5h4.5m1.25-3h5m-2.5-2.5v5M11.25 2.75v3h3"/>
                    </svg>
                    <span>Buat laporan</span>
                </a>
                @endunless

                <a
                    href="{{ route('filament.admin.auth.login') }}"
                    class="admin-access-link hidden sm:inline-flex"
                    aria-label="Masuk ke portal admin"
                    title="Login admin"
                    data-nav-action="admin-login"
                >
                    <span class="admin-access-icon" aria-hidden="true">
                        <svg class="size-4.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7" data-nav-icon="admin-login">
                            <circle cx="10" cy="6.25" r="2.75"/>
                            <path stroke-linecap="round" d="M4.75 16.25c.45-3.1 2.25-4.65 5.25-4.65s4.8 1.55 5.25 4.65"/>
                        </svg>
                    </span>
                    <span class="hidden min-[1180px]:inline">Login admin</span>
                    <span class="sr-only min-[1180px]:hidden">Login admin</span>
                </a>

                <!-- Mobile Menu Button -->
                <button
                    type="button"
                    id="mobile-menu-button"
                    class="inline-flex size-10 items-center justify-center rounded-xl border border-[#E2E8F0] bg-white text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A] focus:ring-2 focus:ring-[#2563EB] lg:hidden"
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
        <div id="mobile-nav" class="hidden space-y-1 border-t border-[#E2E8F0] bg-white px-4 py-3.5 shadow-md lg:hidden">
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold text-[#0B2342] hover:bg-slate-50" href="{{ route('home') }}#cara-kerja">Cara lapor</a>
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold {{ request()->routeIs('kupvas.*') ? 'text-[#2563EB] bg-blue-50/80 font-bold border-l-4 border-[#2563EB]' : 'text-[#0B2342] hover:bg-slate-50' }}" href="{{ route('kupvas.index') }}">Money Changer berizin</a>
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold {{ request()->routeIs('guide') ? 'text-[#2563EB] bg-blue-50/80 font-bold border-l-4 border-[#2563EB]' : 'text-[#0B2342] hover:bg-slate-50' }}" href="{{ route('guide') }}">Panduan</a>
            <a class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold {{ request()->routeIs('reports.track*') || request()->routeIs('reports.status*') ? 'text-[#2563EB] bg-blue-50/80 font-bold border-l-4 border-[#2563EB]' : 'text-[#0B2342] hover:bg-slate-50' }}" href="{{ route('reports.track') }}">Cek status laporan</a>
            <a class="mt-2 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-[#0B2342] transition hover:border-blue-200 hover:bg-blue-50/70" href="{{ route('filament.admin.auth.login') }}">
                <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-white text-[#2563EB] shadow-xs ring-1 ring-slate-200">
                    <svg class="size-4.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <circle cx="10" cy="6.25" r="2.75"/>
                        <path stroke-linecap="round" d="M4.75 16.25c.45-3.1 2.25-4.65 5.25-4.65s4.8 1.55 5.25 4.65"/>
                    </svg>
                </span>
                <span class="min-w-0">
                    <strong class="block text-sm font-bold">Login admin</strong>
                    <small class="mt-0.5 block text-[11px] text-slate-500">Khusus petugas TAMBORA</small>
                </span>
                <span class="ml-auto rounded-full bg-white px-2.5 py-1 text-[10px] font-bold text-[#2563EB] ring-1 ring-blue-100" aria-hidden="true">Masuk</span>
            </a>
            @unless (request()->routeIs('reports.create'))
            <a class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#2563EB] px-3.5 py-2.5 text-sm font-semibold text-white hover:bg-[#1D4ED8]" href="{{ route('reports.create') }}">
                <svg class="size-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.75 2.75h5.5l3 3v4.5M5.75 2.75a1.5 1.5 0 0 0-1.5 1.5v11.5a1.5 1.5 0 0 0 1.5 1.5h4.5m1.25-3h5m-2.5-2.5v5M11.25 2.75v3h3"/>
                </svg>
                <span>Buat laporan</span>
            </a>
            @endunless
        </div>
    </header>

    <!-- Main Content Slot -->
    <main id="main-content" class="flex-1">{{ $slot }}</main>

    @if (request()->routeIs('reports.create'))
    <!-- Minimalist Bottom Line for Report Creation Flow -->
    <footer class="py-3 sm:py-4 text-center text-[10px] sm:text-[11px] text-slate-400 border-t border-[#E2E8F0] bg-white">
        © {{ date('Y') }} TAMBORA (laporkupva.id)
    </footer>
    @else
    <!-- Public Service Footer (Compact, Secondary, Perfectly Balanced) -->
    <footer class="{{ request()->routeIs('home') ? 'mt-0' : 'mt-12 sm:mt-16' }} border-t border-[#E2E8F0] bg-white">
        <div class="public-container py-8 sm:py-10">
            <div class="grid gap-6 sm:gap-8 md:grid-cols-12 md:items-start">
                <!-- Brand & Short Description -->
                <div class="md:col-span-6 lg:col-span-5">
                    <img src="{{ asset('images/brand/tambora.webp') }}" alt="TAMBORA" class="h-8 w-auto object-contain sm:h-9" width="720" height="316" loading="lazy">
                    <p class="mt-2.5 max-w-sm text-xs sm:text-[13px] leading-relaxed text-[#64748B]">Kanal informasi dan pelaporan masyarakat untuk Money Changer (KUPVA BB) di wilayah Provinsi Nusa Tenggara Barat.</p>
                </div>

                <!-- Service Links (Compact) -->
                <div class="md:col-span-3 lg:col-span-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#0B2342]">Menu Layanan</p>
                    <nav class="mt-2.5 grid grid-cols-2 sm:grid-cols-1 gap-1.5 sm:gap-2 text-xs sm:text-[13px]" aria-label="Menu layanan footer">
                        <a class="text-[#64748B] hover:text-[#2563EB] transition-colors" href="{{ route('reports.create') }}">Buat laporan anonim</a>
                        <a class="text-[#64748B] hover:text-[#2563EB] transition-colors" href="{{ route('reports.track') }}">Cek status laporan</a>
                        <a class="text-[#64748B] hover:text-[#2563EB] transition-colors" href="{{ route('kupvas.index') }}">Daftar Money Changer berizin</a>
                        <a class="text-[#64748B] hover:text-[#2563EB] transition-colors" href="{{ route('guide') }}">Panduan penggunaan</a>
                        <a class="text-[#64748B] hover:text-[#2563EB] transition-colors" href="{{ route('privacy') }}">Informasi privasi</a>
                    </nav>
                </div>

                <!-- Notice / Disclaimer -->
                <div class="md:col-span-3 lg:col-span-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#0B2342]">Pemberitahuan</p>
                    <p class="mt-2.5 text-xs sm:text-[13px] leading-relaxed text-[#64748B]">Kanal ini digunakan untuk pengawasan Money Changer (KUPVA BB). Untuk keadaan darurat, segera hubungi pihak kepolisian atau aparat penegak hukum terdekat.</p>
                </div>
            </div>
        </div>

        <!-- Copyright Line -->
        <div class="border-t border-[#E2E8F0] py-3.5 text-center text-[11px] sm:text-xs text-slate-400">
            © {{ date('Y') }} TAMBORA (laporkupva.id)
        </div>
    </footer>
    @endif

    @stack('scripts')
</body>
</html>
