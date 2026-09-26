<x-layouts.public title="Lapor KUPVA secara aman">
    <section class="relative overflow-hidden bg-[#071D3B] text-white">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_78%_18%,rgba(37,99,235,0.24),transparent_34%)]" aria-hidden="true"></div>

        <div class="public-container relative grid items-center gap-10 py-14 sm:py-18 lg:min-h-[590px] lg:grid-cols-[1.06fr_0.94fr] lg:gap-16 lg:py-20">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-200">Kanal resmi Bank Indonesia NTB</p>
                <h1 class="mt-4 text-4xl font-extrabold leading-[1.12] tracking-tight text-white sm:text-5xl lg:text-[3.4rem]">
                    Laporkan KUPVA tidak berizin dengan aman.
                </h1>
                <p class="mt-5 max-w-xl text-sm leading-7 text-slate-200 sm:text-base">
                    Sampaikan informasi money changer yang diduga tidak berizin. Anda tidak perlu membuat akun atau memberikan identitas pribadi.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('reports.create') }}" class="button-primary sm:min-w-44">
                        Buat laporan
                        <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69L10.22 5.03a.75.75 0 0 1 1.06-1.06l5.5 5.5a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 1 1-1.06-1.06l4.22-4.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="{{ route('reports.track') }}" class="button-ghost-light sm:min-w-44">Cek status laporan</a>
                </div>

                <div class="mt-8 flex items-start gap-3 border-t border-white/15 pt-5 text-xs leading-5 text-slate-300 sm:text-sm">
                    <svg class="mt-0.5 size-4 shrink-0 text-emerald-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 0 1 3.172 4.33 12.02 12.02 0 0 0 3 6.36c0 5.103 3.182 9.463 7 11.197 3.818-1.734 7-6.094 7-11.197 0-.69-.058-1.366-.172-2.03A11.954 11.954 0 0 1 10 1.944Zm3.03 6.086a.75.75 0 0 0-1.06-1.06L9 9.94 8.03 8.97a.75.75 0 0 0-1.06 1.06l1.5 1.5a.75.75 0 0 0 1.06 0l3.5-3.5Z" clip-rule="evenodd"/>
                    </svg>
                    <p>Tanpa nama, NIK, email, atau nomor telepon. Simpan kode akses untuk memantau laporan secara mandiri.</p>
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-white p-5 text-[#0B2342] shadow-2xl shadow-black/20 sm:p-7">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2563EB]">Contoh alur penanganan</p>
                <h2 class="mt-2 text-xl font-bold tracking-tight sm:text-2xl">Enam tahap yang mudah dipantau</h2>

                @php
                    $handlingSteps = [
                        ['Laporan dikirim', 'Informasi tersimpan'],
                        ['Laporan diterima', 'Pemeriksaan awal'],
                        ['Koordinasi dengan APH', 'Koordinasi penanganan'],
                        ['Kunjungan lapangan', 'Verifikasi atau penertiban'],
                        ['Laporan hasil', 'Hasil tindak lanjut tersedia'],
                        ['Selesai', 'Penanganan dituntaskan'],
                    ];
                @endphp

                <ol class="mt-6 grid gap-x-6 gap-y-1 sm:grid-cols-2" aria-label="Contoh alur penanganan laporan">
                    @foreach ($handlingSteps as [$title, $description])
                        <li class="flex gap-3 border-t border-slate-100 py-3.5 first:border-t-0 sm:first:border-t">
                            <span class="grid size-7 shrink-0 place-items-center rounded-full bg-blue-50 text-xs font-bold text-[#2563EB]">{{ $loop->iteration }}</span>
                            <div>
                                <h3 class="text-sm font-semibold text-[#0B2342]">{{ $title }}</h3>
                                <p class="mt-0.5 text-xs leading-5 text-slate-500">{{ $description }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="scroll-mt-20 bg-white py-14 sm:py-18">
        <div class="public-container">
            <div class="max-w-2xl">
                <p class="eyebrow">Cara lapor</p>
                <h2 class="section-title">Empat langkah, tanpa registrasi</h2>
                <p class="section-lead">Siapkan informasi yang relevan. Seluruh proses dirancang singkat agar laporan dapat dikirim dari ponsel.</p>
            </div>

            <ol class="mt-10 grid gap-x-8 gap-y-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Ceritakan kejadian', 'Tuliskan apa yang terjadi dan perkiraan waktunya.'],
                    ['Tentukan lokasi', 'Cari wilayah atau gunakan titik lokasi perangkat.'],
                    ['Lampirkan bukti', 'Tambahkan minimal satu foto atau PDF yang relevan.'],
                    ['Simpan akses', 'Unduh QR atau simpan kode laporan dan PIN.'],
                ] as [$title, $description])
                    <li class="border-t-2 border-slate-200 pt-5">
                        <span class="text-sm font-bold text-[#2563EB]">0{{ $loop->iteration }}</span>
                        <h3 class="mt-3 text-base font-bold text-[#0B2342]">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section id="keamanan" class="scroll-mt-20 border-y border-slate-200 bg-[#F7F9FC] py-14 sm:py-18">
        <div class="public-container grid items-center gap-10 lg:grid-cols-[1fr_0.8fr] lg:gap-16">
            <div>
                <p class="eyebrow">Privasi pelapor</p>
                <h2 class="section-title">Informasi kejadian yang dibutuhkan, bukan identitas Anda.</h2>
                <p class="section-lead max-w-2xl">TAMBORA tidak meminta akun, nama, NIK, email, atau nomor telepon. Akses laporan hanya menggunakan kode dan PIN yang Anda simpan sendiri.</p>

                <ul class="mt-8 divide-y divide-slate-200 border-y border-slate-200">
                    @foreach ([
                        ['Anonim sejak awal', 'Formulir tidak meminta identitas pribadi.'],
                        ['Bukti tersimpan privat', 'Foto dan dokumen hanya dapat dibuka petugas berwenang.'],
                        ['Progres dapat dipantau', 'Gunakan akses rahasia tanpa membuat akun.'],
                    ] as [$title, $description])
                        <li class="flex gap-3 py-4">
                            <svg class="mt-0.5 size-5 shrink-0 text-emerald-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.051l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.142Z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-sm font-bold text-[#0B2342]">{{ $title }}</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-500">{{ $description }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="border-y border-slate-300 py-8 lg:py-10">
                <p class="text-6xl font-extrabold tracking-tight text-[#0B2342] sm:text-7xl">0</p>
                <p class="mt-3 text-lg font-bold text-[#0B2342]">identitas pribadi wajib diisi</p>
                <p class="mt-2 max-w-md text-sm leading-6 text-slate-500">Fokus laporan hanya pada kejadian, lokasi, dan bukti yang membantu proses verifikasi.</p>
            </div>
        </div>
    </section>

    <section class="bg-white py-10 sm:py-12">
        <div class="public-container flex flex-col items-start justify-between gap-5 sm:flex-row sm:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Sudah pernah melapor?</p>
                <h2 class="mt-2 text-xl font-bold tracking-tight text-[#0B2342] sm:text-2xl">Lihat perkembangan laporan Anda.</h2>
                <p class="mt-1 text-sm text-slate-500">Gunakan QR akses atau masukkan kode laporan dan PIN.</p>
            </div>
            <a href="{{ route('reports.track') }}" class="button-secondary shrink-0">Cek status laporan</a>
        </div>
    </section>
</x-layouts.public>
