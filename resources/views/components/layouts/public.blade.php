<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TAMBORA - Kanal pelaporan masyarakat untuk pengawasan kegiatan usaha penukaran valuta asing di Nusa Tenggara Barat.">
    <meta name="theme-color" content="#092a57">
    <link rel="icon" type="image/webp" href="{{ asset('images/brand/bank-indonesia-mark.webp') }}">
    <title>{{ isset($title) ? $title.' — ' : '' }}TAMBORA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-navy-900 focus:shadow-lg">Lewati ke konten utama</a>

    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur-xl">
        <div class="public-container flex h-20 sm:h-24 items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="group flex shrink-0 items-center" aria-label="TAMBORA - Beranda">
                <img src="{{ asset('images/brand/tambora.webp') }}" alt="TAMBORA" class="h-13 w-auto object-contain sm:h-16 lg:h-17" width="720" height="316">
            </a>

            <nav class="hidden items-center gap-2 lg:gap-4 md:flex" aria-label="Navigasi utama">
                <a class="nav-link !text-base lg:!text-lg font-bold" href="{{ route('home') }}#cara-kerja">Cara lapor</a>
                <a class="nav-link !text-base lg:!text-lg font-bold" href="{{ route('home') }}#keamanan">Keamanan</a>
                <a class="nav-link !text-base lg:!text-lg font-bold" href="{{ route('guide') }}">Panduan</a>
                <a class="nav-link !text-base lg:!text-lg font-bold" href="{{ route('reports.track') }}">Cek status</a>
            </nav>

            <a href="{{ route('reports.create') }}" class="button-primary hidden sm:inline-flex min-h-12 px-6 sm:px-7 py-3 text-base lg:text-lg font-extrabold rounded-2xl shadow-md">Buat laporan <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69L10.22 5.03a.75.75 0 0 1 1.06-1.06l5.5 5.5a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 1 1-1.06-1.06l4.22-4.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg></a>
        </div>
    </header>

    <main id="main-content">{{ $slot }}</main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="public-container grid gap-8 py-10 md:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                <div class="flex flex-wrap items-center gap-5">
                    <img src="{{ asset('images/brand/tambora.webp') }}" alt="TAMBORA" class="h-16 w-auto object-contain" width="720" height="316" loading="lazy">
                    <span class="hidden h-10 w-px bg-slate-200 sm:block" aria-hidden="true"></span>
                    <img src="{{ asset('images/brand/bank-indonesia-full.webp') }}" alt="Bank Indonesia — Bank Sentral Republik Indonesia" class="h-9 w-auto object-contain" width="880" height="158" loading="lazy">
                </div>
                <p class="mt-4 max-w-md text-sm leading-6 text-slate-500">Kanal partisipasi masyarakat untuk membantu pengawasan kegiatan usaha penukaran valuta asing di wilayah Nusa Tenggara Barat.</p>
            </div>
            <div><p class="footer-heading">Layanan</p><div class="mt-3 grid gap-2 text-sm text-slate-600"><a class="footer-link" href="{{ route('reports.create') }}">Buat laporan</a><a class="footer-link" href="{{ route('reports.track') }}">Cek status laporan</a><a class="footer-link" href="{{ route('guide') }}">Panduan & FAQ</a><a class="footer-link" href="{{ route('privacy') }}">Informasi privasi</a></div></div>
            <div><p class="footer-heading">Perhatian</p><p class="mt-3 text-sm leading-6 text-slate-500">Untuk keadaan darurat atau tindak pidana yang sedang berlangsung, segera hubungi aparat berwenang.</p></div>
        </div>
        <div class="border-t border-slate-100 py-5 text-center text-xs text-slate-400">© {{ date('Y') }} TAMBORA · laporkupva.id</div>
    </footer>

    @stack('scripts')
</body>
</html>
