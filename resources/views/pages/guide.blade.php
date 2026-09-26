<x-layouts.public title="Panduan dan FAQ">
    <!-- Hero Section (Clean Light Palette) -->
    <section class="relative bg-white py-8 sm:py-10 lg:py-12 border-b border-[#E2E8F0]">
        <div class="public-container max-w-[1240px] px-4 sm:px-6 lg:px-10 text-center">
            <div class="inline-flex items-center gap-2 rounded-full border border-blue-200/80 bg-blue-50/80 px-3.5 py-1 text-xs font-semibold text-[#2563EB]">
                <span class="size-1.5 rounded-full bg-[#2563EB]"></span>
                <span>Panduan Masyarakat</span>
            </div>

            <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-[#0F172A] sm:text-3xl lg:text-[34px] lg:leading-tight">
                Melapor dengan aman dan mudah
            </h1>

            <p class="mt-2.5 max-w-[760px] mx-auto text-xs sm:text-sm lg:text-[15px] leading-relaxed text-[#64748B]">
                Ikuti panduan ringkas ini untuk menyampaikan informasi dugaan pelanggaran tempat penukaran valuta asing atau money changer di Nusa Tenggara Barat.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-10 sm:py-16 bg-[#F7F9FC]">
        <div class="public-container max-w-4xl">
            <!-- 3 Steps Grid -->
            <div class="grid gap-5 md:grid-cols-3">
                @foreach ([
                    ['01', 'Siapkan informasi', 'Catat jenis dugaan kejadian, perkiraan waktu, nama atau ciri fisik tempat, serta patokan wilayah yang Anda amati.'],
                    ['02', 'Tentukan titik', 'Gunakan fitur GPS saat berada di tempat kejadian atau cari nama wilayah lalu sesuaikan penanda pada peta interaktif NTB.'],
                    ['03', 'Simpan akses', 'Setelah laporan terkirim, catat Kode Laporan dan PIN 6 digit karena akses tidak dapat dipulihkan demi privasi.'],
                ] as [$number, $title, $description])
                    <article class="flex flex-col justify-between rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs transition-all hover:shadow-md hover:border-[#2563EB]/40">
                        <div>
                            <span class="inline-flex size-8 items-center justify-center rounded-full bg-[#2563EB] text-xs font-bold text-white shadow-2xs">{{ $number }}</span>
                            <h2 class="mt-4 text-base font-bold text-[#0B2342]">{{ $title }}</h2>
                            <p class="mt-2 text-xs sm:text-sm leading-relaxed text-[#64748B]">{{ $description }}</p>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- FAQ Accordion -->
            <div class="mt-10 rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-9 shadow-xs">
                <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 border border-blue-200/60 px-3 py-1 text-xs font-bold text-[#2563EB]">Pertanyaan Umum</span>
                <h2 class="mt-3 text-xl sm:text-2xl font-extrabold tracking-tight text-[#0B2342]">Hal yang sering ditanyakan masyarakat</h2>

                <div class="mt-6 divide-y divide-[#E2E8F0]">
                    @foreach ([
                        ['Apakah saya harus membuat akun?', 'Tidak. TAMBORA tidak meminta pendaftaran akun, nama, NIK, alamat email, atau nomor telepon pelapor. Semua laporan bersifat 100% anonim.'],
                        ['Bagaimana jika saya sudah meninggalkan lokasi?', 'Anda tetap bisa melapor dengan mencari nama wilayah, nama jalan, desa, kecamatan, atau patokan umum di formulir lokasi, lalu menggeser penanda peta ke titik kejadian.'],
                        ['Apakah bukti wajib dilampirkan?', 'Ya. Lampirkan minimal satu foto atau dokumen yang relevan sebagai dasar verifikasi. Tetap utamakan keselamatan dan jangan mengambil bukti dengan cara yang berisiko.'],
                        ['Bagaimana saya mengetahui perkembangan laporan?', 'Buka menu Cek status laporan, lalu masukkan kode laporan dan PIN enam digit yang Anda peroleh saat mengirim laporan.'],
                        ['Bisakah saya menjawab pertanyaan petugas?', 'Bisa. Setelah membuka halaman status laporan, gunakan fitur komunikasi anonim untuk membaca dan mengirimkan pesan tambahan ke petugas tanpa membuka identitas Anda.'],
                        ['Apa yang harus dilakukan jika kode atau PIN hilang?', 'Akses pelacakan tidak dapat dipulihkan kembali karena sistem kami tidak menyimpan kontak atau identitas pribadi pelapor. Mohon simpan kode dan PIN di tempat yang aman.'],
                    ] as [$question, $answer])
                        <details class="group py-4 first:pt-0 last:pb-0">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-xs sm:text-sm text-[#0B2342] hover:text-[#2563EB] transition-colors">
                                <span>{{ $question }}</span>
                                <span class="grid size-7 shrink-0 place-items-center rounded-lg bg-slate-100 text-sm text-[#0B2342] transition-transform duration-200 group-open:rotate-45">+</span>
                            </summary>
                            <p class="mt-2.5 pr-8 text-xs sm:text-sm leading-6 text-[#64748B]">{{ $answer }}</p>
                        </details>
                    @endforeach
                </div>
            </div>

            <!-- Bottom CTA Banner (Clean Card Style) -->
            <div class="mt-8 flex flex-col items-center justify-between gap-5 rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-8 shadow-xs text-center sm:flex-row sm:text-left">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-[#0B2342]">Siap menyampaikan laporan?</h2>
                    <p class="mt-1 text-xs sm:text-sm text-[#64748B]">Sampaikan laporan KUPVA sekarang secara anonim, cepat, dan terlindungi.</p>
                </div>
                <a href="{{ route('reports.create') }}" class="button-primary shrink-0 text-xs sm:text-sm font-semibold rounded-xl bg-[#2563EB] px-5 py-2.5 text-white hover:bg-[#1D4ED8] transition-colors shadow-2xs">
                    <span>Buat laporan</span>
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
