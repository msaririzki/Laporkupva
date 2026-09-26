<x-layouts.public title="Panduan dan FAQ">
    <section class="border-b border-slate-200 bg-[#F7F9FC] py-12 sm:py-16">
        <div class="public-container">
            <div class="max-w-3xl">
                <p class="eyebrow">Panduan masyarakat</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-[#0B2342] sm:text-4xl">Melapor dengan jelas, aman, dan mudah.</h1>
                <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">Pelajari informasi yang perlu disiapkan, cara menentukan lokasi, dan cara menyimpan akses laporan.</p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('reports.create') }}" class="button-primary">Buat laporan</a>
                    <a href="#pertanyaan-umum" class="button-secondary">Lihat pertanyaan umum</a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-14 sm:py-18" id="panduan-cepat">
        <div class="public-container">
            <div class="max-w-2xl">
                <p class="eyebrow">Panduan cepat</p>
                <h2 class="section-title">Sebelum mengirim laporan</h2>
                <p class="section-lead">Empat hal berikut membantu petugas memeriksa laporan dengan lebih cepat.</p>
            </div>

            <ol class="mt-10 grid gap-x-8 gap-y-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Ceritakan kejadian', 'Jelaskan apa yang terlihat dan kapan kejadiannya.'],
                    ['Tentukan titik lokasi', 'Pilih wilayah lalu pastikan penanda peta berada di lokasi yang tepat.'],
                    ['Lampirkan bukti', 'Tambahkan minimal satu foto atau PDF yang relevan dan aman diperoleh.'],
                    ['Simpan akses rahasia', 'Unduh gambar QR atau catat kode laporan dan PIN enam digit.'],
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

    <section class="border-y border-slate-200 bg-[#F7F9FC] py-14 sm:py-18" id="pertanyaan-umum">
        <div class="public-container grid gap-10 lg:grid-cols-[0.7fr_1.3fr] lg:gap-16">
            <div>
                <p class="eyebrow">Pertanyaan umum</p>
                <h2 class="section-title">Jawaban singkat sebelum Anda melapor.</h2>
                <p class="section-lead">Jika masih ragu, utamakan keselamatan dan sampaikan informasi yang benar-benar Anda ketahui.</p>
                <a href="{{ route('reports.create') }}" class="button-primary mt-6">Mulai membuat laporan</a>
            </div>

            @php
                $questions = [
                    ['Apakah saya harus membuat akun?', 'Tidak. TAMBORA tidak meminta akun, nama, NIK, email, atau nomor telepon pelapor.'],
                    ['Bagaimana jika saya sudah meninggalkan lokasi?', 'Cari nama wilayah, jalan, desa, kecamatan, atau patokan. Setelah peta tampil, geser penanda ke lokasi kejadian yang paling akurat.'],
                    ['Apakah bukti wajib dilampirkan?', 'Ya. Lampirkan minimal satu foto atau PDF yang relevan sebagai dasar verifikasi. Jangan mengambil bukti jika situasinya membahayakan.'],
                    ['Bagaimana cara melihat perkembangan laporan?', 'Buka halaman Cek status. Unggah QR akses atau masukkan kode laporan dan PIN enam digit yang diterima setelah laporan dikirim.'],
                    ['Bisakah saya menjawab pertanyaan petugas?', 'Bisa. Setelah membuka status laporan, gunakan komunikasi anonim untuk membaca dan membalas pesan petugas.'],
                    ['Bagaimana jika kode atau PIN hilang?', 'Akses tidak dapat dipulihkan karena sistem tidak menyimpan kontak pelapor. Simpan gambar QR, kode laporan, dan PIN di tempat yang aman.'],
                ];
            @endphp

            <div class="divide-y divide-slate-200 border-y border-slate-200">
                @foreach ($questions as [$question, $answer])
                    <details class="group py-1">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-4 text-sm font-semibold text-[#0B2342] sm:text-base">
                            <span>{{ $question }}</span>
                            <svg class="size-4 shrink-0 text-slate-400 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.22 7.47a.75.75 0 0 1 1.06 0L10 11.19l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                            </svg>
                        </summary>
                        <p class="max-w-2xl pb-5 pr-8 text-sm leading-6 text-slate-600">{{ $answer }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>
