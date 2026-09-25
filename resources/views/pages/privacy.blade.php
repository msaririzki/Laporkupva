<x-layouts.public title="Informasi privasi">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-[#0B2342] to-[#123B69] py-8 sm:py-9 lg:py-10 text-white overflow-hidden border-b border-[#163B68]/60">
        <!-- Subtle background decorative radial glow -->
        <div class="pointer-events-none absolute -top-24 right-1/4 h-80 w-80 rounded-full bg-blue-500/10 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent" aria-hidden="true"></div>

        <div class="public-container max-w-[1240px] px-4 sm:px-6 lg:px-10 text-center">
            <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold text-[#F2B84B] backdrop-blur-xs">
                <span class="size-1.5 rounded-full bg-[#F2B84B]" aria-hidden="true"></span>
                <span>Privasi & Perlindungan Pelapor</span>
            </div>

            <h1 class="mt-3.5 text-2xl font-bold tracking-tight text-white sm:text-3xl lg:text-[32px] lg:leading-tight">
                Anonim sejak awal
            </h1>

            <p class="mt-2.5 max-w-[760px] mx-auto text-xs sm:text-sm lg:text-[15px] leading-relaxed text-slate-300">
                TAMBORA hanya mengumpulkan data kejadian yang diperlukan untuk proses pengawasan, tanpa pernah meminta data identitas pribadi masyarakat.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-10 sm:py-16 bg-[#F7F9FC]">
        <div class="public-container max-w-4xl">
            <div class="rounded-2xl sm:rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm sm:p-10">
                <div class="grid gap-6 md:grid-cols-2">
                    <article class="p-4 rounded-xl border border-[#E2E8F0] bg-[#F7F9FC]">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Data yang tidak diminta</h2>
                        <p class="mt-2 text-xs leading-5 text-[#64748B]">Nama, NIK, alamat email, nomor telepon, dan pembuatan akun tidak diperlukan sama sekali untuk menyampaikan laporan.</p>
                    </article>

                    <article class="p-4 rounded-xl border border-[#E2E8F0] bg-[#F7F9FC]">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Data yang digunakan</h2>
                        <p class="mt-2 text-xs leading-5 text-[#64748B]">Jenis dan kronologi kejadian, titik lokasi pihak yang dilaporkan, waktu kejadian, serta lampiran bukti opsional digunakan secara khusus untuk verifikasi lapangan.</p>
                    </article>

                    <article class="p-4 rounded-xl border border-[#E2E8F0] bg-[#F7F9FC]">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Lokasi perangkat</h2>
                        <p class="mt-2 text-xs leading-5 text-[#64748B]">GPS perangkat hanya diakses setelah Anda memberikan persetujuan izin di peramban. Sebelum dikirim, Anda dapat menyesuaikan penanda agar titik koordinat merefleksikan lokasi tempat kejadian, bukan domisili Anda.</p>
                    </article>

                    <article class="p-4 rounded-xl border border-[#E2E8F0] bg-[#F7F9FC]">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Akses laporan</h2>
                        <p class="mt-2 text-xs leading-5 text-[#64748B]">Progres dan komunikasi hanya dapat dibuka menggunakan kombinasi Kode Laporan dan PIN rahasia. PIN disimpan dalam bentuk hash kriptografis searah.</p>
                    </article>

                    <article class="p-4 rounded-xl border border-[#E2E8F0] bg-[#F7F9FC]">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Lampiran bukti</h2>
                        <p class="mt-2 text-xs leading-5 text-[#64748B]">Foto atau dokumen yang dilampirkan disimpan di ruang penyimpanan terisolasi dan hanya dapat diakses oleh petugas pemeriksa yang berwenang.</p>
                    </article>

                    <article class="p-4 rounded-xl border border-[#E2E8F0] bg-[#F7F9FC]">
                        <h2 class="text-sm sm:text-base font-bold text-[#0B2342]">Catatan teknis</h2>
                        <p class="mt-2 text-xs leading-5 text-[#64748B]">Alamat IP tidak disimpan sebagai bagian dari data laporan. Log keamanan server dapat mencatat akses teknis untuk waktu terbatas sesuai kebijakan operasional pengamanan sistem informasi Bank Indonesia.</p>
                    </article>
                </div>

                <!-- Protective Notice Box -->
                <div class="mt-8 rounded-xl border border-blue-200 bg-blue-50/70 p-4 sm:p-5 text-xs sm:text-sm leading-6 text-[#0B2342]">
                    <strong class="block font-bold">Lindungi kerahasiaan Anda</strong>
                    <span class="mt-1 block text-[#64748B]">Jangan menuliskan nama, NIK, alamat rumah, atau nomor telepon pribadi di dalam teks kronologi, pesan tambahan, maupun nama berkas bukti yang Anda unggah.</span>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
