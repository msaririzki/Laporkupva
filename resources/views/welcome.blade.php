<x-layouts.public title="Lapor KUPVA secara aman">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-[#0B2342] text-white">
        <!-- Background subtle lighting effects -->
        <div class="hero-grid absolute inset-0 opacity-20" aria-hidden="true"></div>
        <div class="absolute -right-24 -top-24 size-96 rounded-full bg-blue-600/15 blur-3xl pointer-events-none" aria-hidden="true"></div>
        <div class="absolute -left-20 bottom-0 size-80 rounded-full bg-teal-500/10 blur-3xl pointer-events-none" aria-hidden="true"></div>

        <div class="public-container relative grid min-h-[560px] items-center gap-10 py-16 sm:py-20 lg:grid-cols-[1.1fr_0.9fr] lg:py-24">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-amber-400/30 bg-amber-400/10 px-3.5 py-1.5 text-xs font-bold text-[#F2B84B]">
                    <span class="size-1.5 rounded-full bg-[#F2B84B]"></span>
                    <span>Kanal Pelaporan Resmi Wilayah NTB</span>
                </div>
                
                <h1 class="mt-4 text-3xl font-bold leading-tight tracking-tight sm:text-4xl lg:text-5xl text-white">
                    Berani melapor,<br>
                    <span class="text-[#F2B84B]">bersama menjaga NTB.</span>
                </h1>
                
                <p class="mt-4 max-w-xl text-xs sm:text-sm leading-6 text-slate-300 sm:text-base sm:leading-7">
                    Laporkan dugaan kegiatan usaha penukaran valuta asing (money changer) yang tidak berizin atau transaksi mencurigakan. Identitas Anda tidak diminta dan proses penanganannya dapat dipantau secara mandiri.
                </p>

                <!-- Actions -->
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('reports.create') }}" class="button-primary px-6 py-3 text-sm">
                        <span>Buat laporan sekarang</span>
                        <svg class="size-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69L10.22 5.03a.75.75 0 0 1 1.06-1.06l5.5 5.5a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 1 1-1.06-1.06l4.22-4.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="{{ route('reports.track') }}" class="button-ghost-light px-5 py-3 text-sm">
                        <span>Cek status laporan</span>
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="mt-10 flex flex-wrap items-center gap-x-6 gap-y-3 text-xs font-semibold text-slate-300">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="size-4 text-[#F2B84B]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/>
                        </svg>
                        Tanpa nama &amp; NIK
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="size-4 text-[#F2B84B]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/>
                        </svg>
                        Titik peta akurat
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="size-4 text-[#F2B84B]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/>
                        </svg>
                        Progres transparan
                    </span>
                </div>
            </div>

            <!-- Workflow Visual Card -->
            <div class="relative w-full max-w-md mx-auto lg:ml-auto">
                <div class="rounded-2xl border border-white/15 bg-white/10 p-5 shadow-2xl backdrop-blur-md">
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-[#F2B84B]">Alur Tindak Lanjut</p>
                            <p class="mt-0.5 text-sm font-bold text-white">Transparan & Terlindungi</p>
                        </div>
                        <span class="rounded-full bg-emerald-500/20 border border-emerald-400/30 px-2.5 py-0.5 text-[11px] font-bold text-emerald-300">Resmi BI NTB</span>
                    </div>

                    <div class="mt-4 space-y-3 bg-white rounded-xl p-4 text-[#0B2342] shadow-sm">
                        @foreach ([
                            ['step' => '01', 'title' => 'Laporan Diterima', 'desc' => 'Tersimpan aman & dianalisis oleh petugas', 'status' => 'done'],
                            ['step' => '02', 'title' => 'Verifikasi Lapangan', 'desc' => 'Pemeriksaan izin & aktivitas operasional', 'status' => 'done'],
                            ['step' => '03', 'title' => 'Koordinasi Penanganan', 'desc' => 'Tindak lanjut bersama aparat penegak hukum', 'status' => 'pending'],
                            ['step' => '04', 'title' => 'Penyelesaian & Evaluasi', 'desc' => 'Hasil penertiban dan pembaruan status', 'status' => 'pending'],
                        ] as $item)
                            <div class="flex items-start gap-3">
                                <span class="grid size-7 shrink-0 place-items-center rounded-lg text-xs font-bold {{ $item['status'] === 'done' ? 'bg-emerald-100 text-[#2E9B68]' : 'bg-slate-100 text-[#64748B]' }}">
                                    @if ($item['status'] === 'done')
                                        <svg class="size-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        {{ $item['step'] }}
                                    @endif
                                </span>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-bold text-[#0B2342]">{{ $item['title'] }}</h3>
                                    <p class="text-[11px] text-[#64748B]">{{ $item['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cara Kerja Section -->
    <section id="cara-kerja" class="scroll-mt-20 py-16 sm:py-20 bg-white">
        <div class="public-container">
            <div class="mx-auto max-w-2xl text-center">
                <p class="eyebrow">Mudah & Terarah</p>
                <h2 class="section-title">Tiga langkah untuk ikut menjaga NTB</h2>
                <p class="section-lead">Anda cukup menceritakan kejadian dan menentukan lokasinya. Tidak diperlukan pendaftaran akun atau data pribadi.</p>
            </div>

            <!-- 3 Step Cards with Subtle Top Accent Colors -->
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                <article class="info-card border-t-4 border-t-[#2563EB]">
                    <span class="step-number bg-blue-50 text-[#2563EB]">01</span>
                    <h3 class="mt-4 text-base font-bold text-[#0B2342]">Ceritakan kejadian</h3>
                    <p class="mt-2 text-xs sm:text-sm leading-6 text-[#64748B]">Pilih jenis dugaan pelanggaran KUPVA, tanggal kejadian, dan tulis kronologi singkat yang Anda saksikan.</p>
                </article>

                <article class="info-card border-t-4 border-t-[#0D9488]">
                    <span class="step-number bg-teal-50 text-[#0D9488]">02</span>
                    <h3 class="mt-4 text-base font-bold text-[#0B2342]">Tentukan lokasi</h3>
                    <p class="mt-2 text-xs sm:text-sm leading-6 text-[#64748B]">Gunakan lokasi perangkat saat di tempat kejadian, atau cari wilayah dan geser penanda langsung pada peta NTB.</p>
                </article>

                <article class="info-card border-t-4 border-t-[#F2B84B]">
                    <span class="step-number bg-amber-50 text-[#D99000]">03</span>
                    <h3 class="mt-4 text-base font-bold text-[#0B2342]">Simpan kode akses</h3>
                    <p class="mt-2 text-xs sm:text-sm leading-6 text-[#64748B]">Dapatkan Kode Laporan dan PIN rahasia untuk memantau progres serta berkomunikasi secara anonim dengan petugas.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Keamanan & Privasi Section -->
    <section id="keamanan" class="scroll-mt-20 py-16 sm:py-20 bg-[#F7F9FC]">
        <div class="public-container grid items-center gap-10 lg:grid-cols-2">
            <div>
                <p class="eyebrow">Keamanan Terjamin</p>
                <h2 class="section-title">Melapor dengan tenang, diproses dengan pasti.</h2>
                <p class="section-lead">TAMBORA dirancang agar seluruh lapisan masyarakat dapat menyampaikan informasi tanpa rasa cemas. Sistem tidak merekam identitas pribadi Anda.</p>
                
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('reports.create') }}" class="button-primary text-xs sm:text-sm">
                        <span>Buat laporan sekarang →</span>
                    </a>
                    <a href="{{ route('privacy') }}" class="button-secondary text-xs sm:text-sm">
                        <span>Baca kebijakan privasi</span>
                    </a>
                </div>
            </div>

            <div class="grid gap-3.5 sm:grid-cols-2">
                <div class="security-card sm:col-span-2 border-l-4 border-l-[#2563EB]">
                    <span class="security-icon bg-[#0B2342]">01</span>
                    <div>
                        <h3>Tanpa identitas pribadi</h3>
                        <p>Formulir tidak meminta nama, NIK, nomor telepon, alamat email, atau foto diri pelapor.</p>
                    </div>
                </div>

                <div class="security-card border-l-4 border-l-[#F2B84B]">
                    <span class="security-icon bg-amber-500">02</span>
                    <div>
                        <h3>Akses privat dengan PIN</h3>
                        <p>Status laporan hanya dapat dibuka menggunakan Kode Laporan dan PIN 6 digit Anda.</p>
                    </div>
                </div>

                <div class="security-card border-l-4 border-l-[#0D9488]">
                    <span class="security-icon bg-teal-600">03</span>
                    <div>
                        <h3>Penyimpanan terenkripsi</h3>
                        <p>Berkas bukti tersimpan dalam repositori terenkripsi dan hanya dapat diakses petugas verifikasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="bg-[#0B2342] py-12 text-white border-t border-[#163B68]">
        <div class="public-container flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-[#F2B84B]">Sudah pernah melapor?</p>
                <h2 class="mt-1 text-xl sm:text-2xl font-bold text-white">Pantau perkembangan tindak lanjut laporan Anda.</h2>
                <p class="mt-1 text-xs sm:text-sm text-slate-300">Masukkan kode laporan dan PIN rahasia untuk memeriksa status terkini.</p>
            </div>
            <a href="{{ route('reports.track') }}" class="button-light shrink-0 text-xs sm:text-sm font-semibold">
                <span>Cek status sekarang</span>
            </a>
        </div>
    </section>
</x-layouts.public>
