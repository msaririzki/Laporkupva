<x-layouts.public title="Panduan dan FAQ">
    <section class="relative overflow-hidden bg-gradient-to-b from-[#eaf4fe] via-[#f3f8fe] to-[#e4f0fd] py-10 sm:py-16 lg:py-20">
        <!-- Mountain Silhouette Background at Bottom (TAMBORA Atmosphere) -->
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-24 sm:h-36 overflow-hidden opacity-30" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1440 240" fill="none" preserveAspectRatio="none">
                <path d="M0 240 L0 150 L140 130 L280 180 L440 100 L590 160 L740 60 L890 150 L1040 85 L1190 140 L1340 75 L1440 120 L1440 240 Z" fill="url(#guide-mountains-grad)" />
                <defs>
                    <linearGradient id="guide-mountains-grad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#93c5fd" stop-opacity="0.5"/>
                        <stop offset="100%" stop-color="#2563eb" stop-opacity="0.8"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <!-- Subtle Dot Grid Patterns on Sides -->
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute top-10 right-10 size-32 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
            <div class="absolute bottom-10 left-10 size-32 bg-[radial-gradient(#93c5fd_1.5px,transparent_1.5px)] [background-size:16px_16px] opacity-40 hidden sm:block"></div>
        </div>

        <div class="public-container">
            <div class="grid items-center gap-8 lg:grid-cols-12 lg:gap-12">
                <!-- Left Content: Panduan Masyarakat -->
                <div class="text-center lg:col-span-7 lg:text-left">
                    <!-- Eyebrow Badge -->
                    <div class="inline-flex items-center gap-2 rounded-full bg-blue-100/90 px-3.5 py-1.5 text-xs font-extrabold uppercase tracking-wider text-blue-700 shadow-xs">
                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z" />
                            <path d="M6 6h10" />
                            <path d="M6 10h10" />
                        </svg>
                        PANDUAN
                    </div>

                    <!-- Main Title -->
                    <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-navy-950 sm:text-4xl lg:text-[2.65rem] lg:leading-[1.18]">
                        Panduan lengkap untuk melapor di TAMBORA
                    </h1>

                    <!-- Subtitle -->
                    <p class="mt-3.5 text-sm sm:text-base text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Melapor dengan aman dan mudah. Temukan langkah-langkah membuat laporan, informasi keamanan, dan jawaban untuk pertanyaan yang sering ditanyakan.
                    </p>

                    <!-- Interactive Search Bar -->
                    <div class="relative mt-6 max-w-lg mx-auto lg:mx-0">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-blue-600">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="guide-search-input"
                            placeholder="Cari pertanyaan atau topik panduan..."
                            class="w-full rounded-2xl border border-slate-200/90 bg-white py-3.5 pl-11 pr-4 text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 transition-all"
                        >
                    </div>

                    <!-- Popular Topics -->
                    <div class="mt-4 flex flex-wrap items-center justify-center lg:justify-start gap-2 text-xs text-slate-500">
                        <span class="font-medium text-slate-400">Topik populer:</span>
                        <button type="button" onclick="filterFaq('lapor')" class="rounded-xl bg-blue-50/90 border border-blue-100 px-3 py-1 font-semibold text-blue-700 hover:bg-blue-600 hover:text-white transition-colors cursor-pointer">
                            Buat laporan
                        </button>
                        <button type="button" onclick="filterFaq('lokasi')" class="rounded-xl bg-blue-50/90 border border-blue-100 px-3 py-1 font-semibold text-blue-700 hover:bg-blue-600 hover:text-white transition-colors cursor-pointer">
                            Lokasi kejadian
                        </button>
                        <button type="button" onclick="filterFaq('kode')" class="rounded-xl bg-blue-50/90 border border-blue-100 px-3 py-1 font-semibold text-blue-700 hover:bg-blue-600 hover:text-white transition-colors cursor-pointer">
                            Kode dan PIN
                        </button>
                        <button type="button" onclick="filterFaq('foto')" class="rounded-xl bg-blue-50/90 border border-blue-100 px-3 py-1 font-semibold text-blue-700 hover:bg-blue-600 hover:text-white transition-colors cursor-pointer">
                            Bukti laporan
                        </button>
                    </div>
                </div>

                <!-- Right Illustration (Gambar Cewek yang Dikirim User) -->
                <div class="lg:col-span-5 flex items-center justify-center">
                    <div class="relative mx-auto flex w-full max-w-[340px] sm:max-w-[420px] lg:max-w-[480px] items-center justify-center py-2">
                        <!-- Glow Accent Behind Image -->
                        <div class="absolute inset-0 -z-10 rounded-full bg-gradient-to-tr from-blue-300/30 via-sky-200/40 to-blue-100/30 blur-3xl"></div>
                        <img
                            src="{{ asset('images/illustrations/guide-hero.png') }}"
                            alt="Ilustrasi Panduan Masyarakat TAMBORA"
                            class="h-auto w-full object-contain drop-shadow-xl transition-transform duration-500 hover:scale-[1.02]"
                            width="960"
                            height="720"
                            loading="eager"
                        >
                    </div>
                </div>
            </div>
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('guide-search-input');
            const faqDetails = document.querySelectorAll('details');

            function performSearch(term) {
                term = (term || '').toLowerCase().trim();
                faqDetails.forEach(detail => {
                    const text = detail.textContent.toLowerCase();
                    if (!term || text.includes(term)) {
                        detail.style.display = '';
                        if (term) {
                            detail.open = true;
                        }
                    } else {
                        detail.style.display = 'none';
                        detail.open = false;
                    }
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', function (e) {
                    performSearch(e.target.value);
                });
            }

            window.filterFaq = function (term) {
                if (searchInput) {
                    searchInput.value = term;
                }
                performSearch(term);
                const faqSection = document.querySelector('details')?.closest('div.rounded-3xl');
                if (faqSection) {
                    faqSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            };
        });
    </script>
    @endpush
</x-layouts.public>
