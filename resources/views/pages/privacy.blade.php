<x-layouts.public title="Informasi privasi">
    <!-- Hero Section (Clean Light Palette) -->
    <section class="relative bg-white py-8 sm:py-10 lg:py-12 border-b border-[#E2E8F0]">
        <div class="public-container max-w-[1240px] px-4 sm:px-6 lg:px-10 text-center">
            <div class="inline-flex items-center gap-2 rounded-full border border-blue-200/80 bg-blue-50/80 px-3.5 py-1 text-xs font-semibold text-[#2563EB]">
                <span class="size-1.5 rounded-full bg-[#2563EB]"></span>
                <span>Privasi & Perlindungan Pelapor</span>
            </div>

            <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-[#0F172A] sm:text-3xl lg:text-[34px] lg:leading-tight">
                Anonim sejak awal
            </h1>

            <p class="mt-2.5 max-w-[760px] mx-auto text-xs sm:text-sm lg:text-[15px] leading-relaxed text-[#64748B]">
                TAMBORA hanya mengumpulkan data kejadian yang diperlukan untuk proses pengawasan, tanpa pernah meminta data identitas pribadi masyarakat.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-10 sm:py-16 bg-[#F4F7FB]">
        <div class="public-container max-w-4xl">
            <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-10 shadow-xs">
                <div class="grid gap-5 md:grid-cols-2">
                    <article class="p-5 rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] transition-colors hover:border-[#2563EB]/40">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Data yang tidak diminta</h2>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-[#64748B]">Nama, NIK, alamat email, nomor HP, dan pendaftaran akun sama sekali tidak diperlukan untuk membuat laporan.</p>
                    </article>

                    <article class="p-5 rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] transition-colors hover:border-[#2563EB]/40">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Data yang digunakan</h2>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-[#64748B]">Jenis kejadian, titik lokasi, waktu, dan bukti foto (jika ada) hanya digunakan petugas resmi untuk verifikasi lapangan.</p>
                    </article>

                    <article class="p-5 rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] transition-colors hover:border-[#2563EB]/40">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Lokasi perangkat</h2>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-[#64748B]">GPS hanya aktif dengan izin Anda. Anda bebas menggeser pin peta agar titik sesuai lokasi kejadian, bukan posisi Anda.</p>
                    </article>

                    <article class="p-5 rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] transition-colors hover:border-[#2563EB]/40">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Akses laporan privat</h2>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-[#64748B]">Perkembangan laporan hanya dapat dibuka memakai kombinasi Kode Laporan dan PIN acak 6 digit yang tersimpan terenkripsi.</p>
                    </article>

                    <article class="p-5 rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] transition-colors hover:border-[#2563EB]/40">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Penyimpanan bukti terenkripsi</h2>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-[#64748B]">Berkas foto atau dokumen bukti tersimpan dalam repositori terisolasi dan hanya bisa dibuka petugas resmi berwenang.</p>
                    </article>

                    <article class="p-5 rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] transition-colors hover:border-[#2563EB]/40">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Tanpa jejak pelapor</h2>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-[#64748B]">Sistem tidak menyimpan identitas pribadi. Alamat IP tidak disimpan sebagai bagian dari data laporan.</p>
                    </article>
                </div>

                <!-- Protective Notice Box -->
                <div class="mt-8 rounded-2xl border border-blue-100 bg-blue-50/60 p-5 text-xs sm:text-sm leading-relaxed text-[#0B2342]">
                    <strong class="block font-bold text-[#0B2342]">Tips Menjaga Kerahasiaan</strong>
                    <span class="mt-1 block text-[#475569]">Jangan mencantumkan nama, nomor telepon, atau data diri pribadi di dalam uraian kronologi maupun nama file berkas yang Anda unggah.</span>
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
