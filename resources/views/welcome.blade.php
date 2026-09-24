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

    <section id="cara-kerja" class="scroll-mt-24 py-20 sm:py-24">
        <div class="public-container">
            <div class="mx-auto max-w-2xl text-center"><p class="eyebrow">Mudah dan terarah</p><h2 class="section-title">Tiga langkah untuk ikut menjaga NTB</h2><p class="section-lead">Anda cukup menjelaskan kejadian dan menunjukkan lokasinya. Tidak ada proses pendaftaran akun.</p></div>
            <div class="mt-12 grid gap-5 md:grid-cols-3">
                @foreach ([['01', 'Ceritakan kejadian', 'Pilih jenis laporan, waktu kejadian, dan tulis kronologi yang Anda ketahui.'], ['02', 'Tandai lokasinya', 'Gunakan posisi perangkat, cari nama wilayah, atau pilih titik langsung pada peta.'], ['03', 'Simpan kode akses', 'Dapatkan kode laporan dan PIN rahasia untuk memantau setiap perkembangan.']] as [$number, $heading, $copy])
                    <article class="info-card"><span class="step-number">{{ $number }}</span><h3 class="mt-7 text-lg font-extrabold text-navy-950">{{ $heading }}</h3><p class="mt-3 text-sm leading-6 text-slate-600">{{ $copy }}</p></article>
                @endforeach
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
