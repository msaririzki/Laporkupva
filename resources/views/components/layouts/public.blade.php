<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TAMBORA - Kanal pelaporan masyarakat untuk pengawasan kegiatan usaha penukaran valuta asing di Nusa Tenggara Barat.">
    <meta name="theme-color" content="#092a57">
    <title>{{ isset($title) ? $title.' — ' : '' }}TAMBORA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-navy-900 focus:shadow-lg">Lewati ke konten utama</a>

    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
        <div class="public-container flex h-18 items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="group flex items-center gap-3" aria-label="TAMBORA - Beranda">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 48 48" fill="none"><path d="M24 4 41 11v11c0 10.8-6.5 18.2-17 22C13.5 40.2 7 32.8 7 22V11l17-7Z" fill="currentColor"/><path d="M15 30.5 22.4 18l4.1 6.3 2.5-3.5 5 9.7H15Z" fill="white" opacity=".96"/><circle cx="22.5" cy="15" r="2.4" fill="#50c8b6"/></svg>
                </span>
                <span><span class="block text-lg font-extrabold leading-none tracking-tight text-navy-950">TAMBORA</span><span class="mt-1 block text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Bank Indonesia • NTB</span></span>
            </a>

            <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
                <a class="nav-link" href="{{ route('home') }}#cara-kerja">Cara kerja</a>
                <a class="nav-link" href="{{ route('home') }}#keamanan">Keamanan</a>
                <a class="nav-link" href="{{ route('reports.track') }}">Cek status</a>
            </nav>

            <a href="{{ route('reports.create') }}" class="button-primary hidden sm:inline-flex">Buat laporan <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69L10.22 5.03a.75.75 0 0 1 1.06-1.06l5.5 5.5a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 1 1-1.06-1.06l4.22-4.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg></a>
        </div>
    </header>

    <main id="main-content">{{ $slot }}</main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="public-container grid gap-8 py-10 md:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                <div class="flex items-center gap-3">
                    <span class="brand-mark brand-mark-sm" aria-hidden="true"><svg viewBox="0 0 48 48" fill="none"><path d="M24 4 41 11v11c0 10.8-6.5 18.2-17 22C13.5 40.2 7 32.8 7 22V11l17-7Z" fill="currentColor"/><path d="M15 30.5 22.4 18l4.1 6.3 2.5-3.5 5 9.7H15Z" fill="white"/><circle cx="22.5" cy="15" r="2.4" fill="#50c8b6"/></svg></span>
                    <span class="font-extrabold text-navy-950">TAMBORA</span>
                </div>
                <p class="mt-4 max-w-md text-sm leading-6 text-slate-500">Kanal partisipasi masyarakat untuk membantu pengawasan kegiatan usaha penukaran valuta asing di wilayah Nusa Tenggara Barat.</p>
            </div>
            <div><p class="footer-heading">Layanan</p><div class="mt-3 grid gap-2 text-sm text-slate-600"><a class="footer-link" href="{{ route('reports.create') }}">Buat laporan</a><a class="footer-link" href="{{ route('reports.track') }}">Cek status laporan</a></div></div>
            <div><p class="footer-heading">Perhatian</p><p class="mt-3 text-sm leading-6 text-slate-500">Untuk keadaan darurat atau tindak pidana yang sedang berlangsung, segera hubungi aparat berwenang.</p></div>
        </div>
        <div class="border-t border-slate-100 py-5 text-center text-xs text-slate-400">© {{ date('Y') }} TAMBORA · laporkupva.id</div>
    </footer>

    @stack('scripts')
</body>
</html>
