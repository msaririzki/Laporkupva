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

    <section id="cara-kerja" class="relative overflow-hidden bg-gradient-to-b from-slate-100/90 via-slate-50/70 to-white py-24 sm:py-32 scroll-mt-20">
        <!-- Ambient Metallic Atmosphere -->
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 h-80 w-[48rem] rounded-full bg-gradient-to-b from-blue-200/30 via-teal-100/20 to-transparent blur-3xl"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] [background-size:28px_28px] opacity-25"></div>
        </div>

        <div class="public-container relative">
            <div class="mx-auto max-w-3xl text-center">
                <div class="inline-flex items-center gap-2.5 rounded-full border border-slate-300/80 bg-white/90 px-4 py-1.5 shadow-xs backdrop-blur-md">
                    <span class="relative flex size-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-teal-400 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-teal-500"></span>
                    </span>
                    <span class="text-[11px] font-black uppercase tracking-[0.22em] text-slate-700">Alur Mudah & Transparan</span>
                </div>

                <h2 class="mt-5 text-3xl font-black tracking-tight text-navy-950 sm:text-4xl lg:text-5xl">
                    Tiga Langkah untuk <span class="bg-gradient-to-r from-blue-700 via-blue-800 to-teal-600 bg-clip-text text-transparent">Ikut Menjaga NTB</span>
                </h2>

                <p class="mt-4 text-base leading-relaxed text-slate-600 sm:text-lg">
                    Sistem dirancang cepat, aman, dan 100% anonim. Anda cukup menginformasikan kronologi kejadian dan lokasinya tanpa perlu mendaftar akun.
                </p>
            </div>

            <!-- Step Cards Grid with Connecting Pipeline -->
            <div class="relative mt-16 sm:mt-20">
                <!-- Desktop Connecting Track -->
                <div class="pointer-events-none absolute top-1/2 left-10 right-10 hidden h-[2px] -translate-y-10 bg-gradient-to-r from-transparent via-slate-200 to-transparent md:block" aria-hidden="true"></div>

                <div class="grid gap-6 md:grid-cols-3">
                    <!-- LANGKAH 01 -->
                    <article class="group relative flex flex-col justify-between overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-b from-white via-slate-50/80 to-slate-100/70 p-7 shadow-lg shadow-slate-200/60 transition-all duration-300 hover:-translate-y-1.5 hover:border-blue-400 hover:shadow-2xl hover:shadow-blue-500/10">
                        <div class="pointer-events-none absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-blue-500/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <span class="pointer-events-none absolute -right-2 -top-4 select-none font-mono text-8xl font-black text-slate-200/40 transition-colors duration-300 group-hover:text-blue-100/50">01</span>

                        <div class="relative">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 rounded-full border border-slate-300/80 bg-white/95 px-3 py-1 shadow-xs backdrop-blur-sm">
                                    <span class="size-1.5 rounded-full bg-blue-600"></span>
                                    <span class="font-mono text-xs font-black tracking-widest text-navy-950">LANGKAH 01</span>
                                </div>

                                <div class="flex size-13 items-center justify-center rounded-2xl border border-slate-200 bg-gradient-to-br from-white via-slate-50 to-slate-200 text-blue-700 shadow-sm transition-transform duration-300 group-hover:scale-105 group-hover:border-blue-300 group-hover:text-blue-800">
                                    <svg class="size-6.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>
                            </div>

                            <h3 class="mt-6 text-xl font-black text-navy-950 transition-colors group-hover:text-blue-700">Ceritakan Kejadian</h3>
                            <p class="mt-3 text-sm leading-relaxed text-slate-600">Pilih jenis dugaan pelanggaran, waktu kejadian, dan sampaikan kronologi peristiwa yang Anda ketahui atau alami.</p>
                        </div>

                        <div class="relative mt-8 border-t border-slate-200/80 pt-4">
                            <div class="flex flex-wrap gap-2 text-[11px] font-bold text-slate-600">
                                <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1 shadow-2xs">
                                    <svg class="size-3.5 text-teal-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/></svg>
                                    Tanpa Identitas Pribadi
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1 shadow-2xs">
                                    <svg class="size-3.5 text-teal-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/></svg>
                                    5 Jenis Kasus
                                </span>
                            </div>
                        </div>
                    </article>

                    <!-- LANGKAH 02 -->
                    <article class="group relative flex flex-col justify-between overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-b from-white via-slate-50/80 to-slate-100/70 p-7 shadow-lg shadow-slate-200/60 transition-all duration-300 hover:-translate-y-1.5 hover:border-teal-400 hover:shadow-2xl hover:shadow-teal-500/10">
                        <div class="pointer-events-none absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-teal-500/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <span class="pointer-events-none absolute -right-2 -top-4 select-none font-mono text-8xl font-black text-slate-200/40 transition-colors duration-300 group-hover:text-teal-100/50">02</span>

                        <div class="relative">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 rounded-full border border-slate-300/80 bg-white/95 px-3 py-1 shadow-xs backdrop-blur-sm">
                                    <span class="size-1.5 rounded-full bg-teal-600"></span>
                                    <span class="font-mono text-xs font-black tracking-widest text-navy-950">LANGKAH 02</span>
                                </div>

                                <div class="flex size-13 items-center justify-center rounded-2xl border border-slate-200 bg-gradient-to-br from-white via-slate-50 to-slate-200 text-teal-700 shadow-sm transition-transform duration-300 group-hover:scale-105 group-hover:border-teal-300 group-hover:text-teal-800">
                                    <svg class="size-6.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"></polygon>
                                        <line x1="9" y1="3" x2="9" y2="18"></line>
                                        <line x1="15" y1="6" x2="15" y2="21"></line>
                                    </svg>
                                </div>
                            </div>

                            <h3 class="mt-6 text-xl font-black text-navy-950 transition-colors group-hover:text-teal-700">Tandai Lokasinya</h3>
                            <p class="mt-3 text-sm leading-relaxed text-slate-600">Gunakan posisi GPS perangkat secara otomatis, cari nama wilayah/patokan di NTB, atau geser penanda titik kejadian secara akurat.</p>
                        </div>

                        <div class="relative mt-8 border-t border-slate-200/80 pt-4">
                            <div class="flex flex-wrap gap-2 text-[11px] font-bold text-slate-600">
                                <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1 shadow-2xs">
                                    <svg class="size-3.5 text-teal-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/></svg>
                                    Peta Presisi Wilayah NTB
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1 shadow-2xs">
                                    <svg class="size-3.5 text-teal-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/></svg>
                                    Deteksi GPS & Pin Geser
                                </span>
                            </div>
                        </div>
                    </article>

                    <!-- LANGKAH 03 -->
                    <article class="group relative flex flex-col justify-between overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-b from-white via-slate-50/80 to-slate-100/70 p-7 shadow-lg shadow-slate-200/60 transition-all duration-300 hover:-translate-y-1.5 hover:border-indigo-400 hover:shadow-2xl hover:shadow-indigo-500/10">
                        <div class="pointer-events-none absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <span class="pointer-events-none absolute -right-2 -top-4 select-none font-mono text-8xl font-black text-slate-200/40 transition-colors duration-300 group-hover:text-indigo-100/50">03</span>

                        <div class="relative">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 rounded-full border border-slate-300/80 bg-white/95 px-3 py-1 shadow-xs backdrop-blur-sm">
                                    <span class="size-1.5 rounded-full bg-indigo-600"></span>
                                    <span class="font-mono text-xs font-black tracking-widest text-navy-950">LANGKAH 03</span>
                                </div>

                                <div class="flex size-13 items-center justify-center rounded-2xl border border-slate-200 bg-gradient-to-br from-white via-slate-50 to-slate-200 text-indigo-700 shadow-sm transition-transform duration-300 group-hover:scale-105 group-hover:border-indigo-300 group-hover:text-indigo-800">
                                    <svg class="size-6.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </div>
                            </div>

                            <h3 class="mt-6 text-xl font-black text-navy-950 transition-colors group-hover:text-indigo-700">Simpan Kode Akses</h3>
                            <p class="mt-3 text-sm leading-relaxed text-slate-600">Dapatkan kode laporan unik (LKP-XXXX-XXXX) dan PIN rahasia 6-digit untuk memantau status serta berkomunikasi dengan aman.</p>
                        </div>

                        <div class="relative mt-8 border-t border-slate-200/80 pt-4">
                            <div class="flex flex-wrap gap-2 text-[11px] font-bold text-slate-600">
                                <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1 shadow-2xs">
                                    <svg class="size-3.5 text-teal-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/></svg>
                                    Kode Unik & PIN Rahasia
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1 shadow-2xs">
                                    <svg class="size-3.5 text-teal-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/></svg>
                                    QR Akses Pelacakan
                                </span>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <!-- Bottom Interactive Action Banner -->
            <div class="mt-14 flex justify-center">
                <div class="inline-flex flex-wrap items-center justify-center gap-4 rounded-2xl border border-slate-200/90 bg-white/90 p-3 shadow-md backdrop-blur-md sm:gap-6 sm:px-7 sm:py-3.5">
                    <span class="flex items-center gap-2 text-xs font-bold text-slate-700">
                        <span class="size-2 rounded-full bg-teal-500 animate-pulse"></span> Siap menyampaikan informasi kejadian?
                    </span>
                    <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2 text-xs font-extrabold text-white shadow-sm transition hover:bg-blue-800 hover:shadow-md hover:-translate-y-0.5">
                        Mulai Buat Laporan <span aria-hidden="true">→</span>
                    </a>
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
