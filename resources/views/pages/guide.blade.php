<x-layouts.public title="Panduan dan FAQ">
    <section class="bg-navy-950 py-14 text-white sm:py-18">
        <div class="public-container max-w-4xl">
            <p class="eyebrow-dark">Panduan masyarakat</p>
            <h1 class="mt-5 text-3xl font-extrabold tracking-tight sm:text-4xl">Melapor dengan aman dan mudah</h1>
            <p class="mt-4 max-w-2xl text-base leading-7 text-blue-100/75">Ikuti panduan singkat ini untuk mengirim informasi tentang tempat penukaran valuta asing atau money changer di wilayah NTB.</p>
        </div>
    </section>

    <section class="py-12 sm:py-16">
        <div class="public-container max-w-4xl">
            <div class="grid gap-5 md:grid-cols-3">
                @foreach ([
                    ['01', 'Siapkan informasi', 'Catat jenis kejadian, waktu, nama tempat jika diketahui, dan petunjuk lokasi.'],
                    ['02', 'Tentukan titik', 'Gunakan GPS saat masih di lokasi atau cari wilayah lalu geser pin ke titik kejadian.'],
                    ['03', 'Simpan akses', 'Setelah mengirim, simpan kode laporan dan PIN karena keduanya tidak dapat dipulihkan.'],
                ] as [$number, $title, $description])
                    <article class="info-card">
                        <span class="step-number">{{ $number }}</span>
                        <h2 class="mt-6 font-extrabold text-navy-950">{{ $title }}</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $description }}</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-12 rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-9">
                <p class="eyebrow">Pertanyaan umum</p>
                <h2 class="mt-3 text-2xl font-extrabold text-navy-950">Hal yang perlu Anda ketahui</h2>
                <div class="mt-7 divide-y divide-slate-200">
                    @foreach ([
                        ['Apakah saya harus membuat akun?', 'Tidak. TAMBORA tidak meminta akun, nama, NIK, email, atau nomor telepon pelapor.'],
                        ['Bagaimana jika saya sudah meninggalkan lokasi?', 'Cari nama wilayah, jalan, desa, kecamatan, atau patokan. Setelah hasil tampil, geser pin ke lokasi kejadian yang paling akurat.'],
                        ['Apakah foto wajib dilampirkan?', 'Tidak. Bukti foto atau PDF bersifat opsional. Utamakan keselamatan dan jangan mengambil bukti jika situasinya berisiko.'],
                        ['Bagaimana saya mengetahui perkembangan laporan?', 'Buka halaman Cek status, lalu masukkan kode laporan dan PIN enam digit yang ditampilkan setelah laporan dikirim.'],
                        ['Bisakah saya menjawab pertanyaan petugas?', 'Bisa. Setelah membuka progres laporan, gunakan kotak komunikasi anonim untuk membaca dan membalas pesan petugas.'],
                        ['Apa yang harus dilakukan jika kode atau PIN hilang?', 'Akses tidak dapat dipulihkan karena sistem tidak menyimpan identitas atau kontak pelapor. Simpan kode dan PIN di tempat yang aman.'],
                    ] as [$question, $answer])
                        <details class="group py-5 first:pt-0 last:pb-0">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-5 font-bold text-slate-800">
                                {{ $question }}
                                <span class="grid size-8 shrink-0 place-items-center rounded-full bg-slate-100 text-lg text-blue-700 transition group-open:rotate-45">+</span>
                            </summary>
                            <p class="mt-3 pr-12 text-sm leading-6 text-slate-600">{{ $answer }}</p>
                        </details>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 flex flex-col items-center justify-between gap-5 rounded-3xl bg-blue-700 p-7 text-center text-white sm:flex-row sm:text-left">
                <div><h2 class="text-xl font-extrabold">Siap menyampaikan laporan?</h2><p class="mt-1 text-sm text-blue-100">Pastikan informasi disampaikan dengan itikad baik.</p></div>
                <a href="{{ route('reports.create') }}" class="button-light shrink-0">Buat laporan anonim</a>
            </div>
        </div>
    </section>
</x-layouts.public>
