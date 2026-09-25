<x-layouts.public title="Buat laporan anonim">
    <!-- Hero Header -->
    <section class="relative bg-gradient-to-br from-[#0B2342] to-[#123B69] py-8 sm:py-9 lg:py-10 text-white overflow-hidden border-b border-[#163B68]/60">
        <!-- Subtle background decorative radial glow -->
        <div class="pointer-events-none absolute -top-24 right-1/4 h-80 w-80 rounded-full bg-blue-500/10 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent" aria-hidden="true"></div>

        <div class="public-container max-w-[1240px] px-4 sm:px-6 lg:px-10">
            <!-- Baris paling atas: Laporan Anonim (kiri) & Identitas Anda tetap terlindungi (kanan di desktop) -->
            <div class="flex items-center justify-between w-full">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold text-[#F2B84B] backdrop-blur-xs">
                    <span class="size-1.5 rounded-full bg-[#F2B84B]" aria-hidden="true"></span>
                    <span>Laporan Anonim</span>
                </div>
                <div class="hidden sm:inline-flex items-center gap-1.5 text-xs text-slate-300 font-medium">
                    <svg class="size-3.5 text-[#F2B84B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    <span>Identitas Anda tetap terlindungi</span>
                </div>
            </div>

            <!-- Content Area: Mobile left-aligned, Desktop centered with natural max-width -->
            <div class="mt-4 sm:mt-5 text-left lg:text-center max-w-[800px] lg:mx-auto">
                <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl lg:text-[32px] lg:leading-tight">
                    Laporkan dengan cepat dan aman
                </h1>

                <p class="mt-2 text-xs sm:text-sm lg:text-[15px] leading-relaxed text-slate-300 max-w-[720px] lg:mx-auto">
                    Ceritakan kejadian, tentukan lokasi, lalu kirim laporan secara anonim.
                </p>

                <!-- Benefits (Di bawah deskripsi) -->
                <div class="mt-4 flex flex-wrap items-center justify-start lg:justify-center gap-2 sm:gap-3 text-xs text-slate-200">
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/10 px-2.5 py-1 border border-white/15">
                        <span class="text-[#F2B84B] font-bold">•</span>
                        <span>±3 menit</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/10 px-2.5 py-1 border border-white/15">
                        <span class="text-[#F2B84B] font-bold">•</span>
                        <span>Bukti foto opsional</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/10 px-2.5 py-1 border border-white/15">
                        <span class="text-[#F2B84B] font-bold">•</span>
                        <span>Tanpa identitas</span>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Service Canvas (Clean open canvas, desktop elevated card) -->
    <section class="py-6 sm:py-10 lg:py-12 bg-[#F4F7FB] min-h-[60vh]">
        <div class="public-container max-w-[1240px] px-4 sm:px-6 lg:px-10">
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-xs sm:text-sm text-[#DC2626]" role="alert">
                    <p class="font-bold flex items-center gap-2">
                        <svg class="size-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                        </svg>
                        <span>Beberapa isian perlu diperiksa kembali</span>
                    </p>
                    <p class="mt-1 ml-6 text-xs text-red-700">Silakan lengkapi atau perbaiki isian yang ditandai dengan warna merah.</p>
                </div>
            @endif

            <!-- Stepper Navigation (Modern 01 - 02 - 03 Track) -->
            <nav class="stepper-nav mb-8 sm:mb-10 lg:mb-12 lg:max-w-xl mx-auto" aria-label="Tahapan formulir">
                <div class="stepper-list">
                    <!-- Connecting Track -->
                    <div class="stepper-track" aria-hidden="true">
                        <div id="stepper-progress-fill" class="stepper-track-progress" style="width: 0%;"></div>
                    </div>

                    @foreach ([
                        ['step' => 1, 'num' => '01', 'name' => 'Kejadian', 'desc' => 'Ceritakan kejadian'],
                        ['step' => 2, 'num' => '02', 'name' => 'Lokasi', 'desc' => 'Tentukan lokasi'],
                        ['step' => 3, 'num' => '03', 'name' => 'Bukti', 'desc' => 'Tambahkan bukti'],
                    ] as $item)
                        <button
                            type="button"
                            class="stepper-step {{ $item['step'] === 1 ? 'is-active' : '' }}"
                            data-progress="{{ $item['step'] }}"
                            aria-label="Langkah {{ $item['step'] }}: {{ $item['name'] }}"
                        >
                            <span class="stepper-circle">
                                <span class="stepper-number">{{ $item['num'] }}</span>
                            </span>
                            <span class="stepper-title">{{ $item['name'] }}</span>
                            <span class="stepper-desc">{{ $item['desc'] }}</span>
                        </button>
                    @endforeach
                </div>
            </nav>

            <!-- Subtle Live Accessibility Step Status -->
            <p id="form-step-status" class="sr-only" aria-live="polite">Langkah 1 dari 3</p>

            <!-- Service Flow Form (Desktop Single White Card Container) -->
            <form
                action="{{ route('reports.store') }}"
                method="POST"
                enctype="multipart/form-data"
                id="report-form"
                class="lg:bg-white lg:border lg:border-[#E2E8F0] lg:rounded-2xl lg:p-8 xl:p-10 lg:shadow-[0_8px_30px_rgba(15,23,42,0.05)]"
                novalidate
            >
                @csrf

                <!-- Honeypot -->
                <div class="absolute -left-[10000px] h-px w-px overflow-hidden" aria-hidden="true">
                    <label for="website">Situs web</label>
                    <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                </div>

                <!-- STEP 1: KEJADIAN -->
                <section class="form-step" data-step="1">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Apa yang terjadi?</h2>
                        <p class="mt-1 text-xs sm:text-sm text-[#64748B]">Ceritakan kejadian dengan singkat dan jelas. Tidak perlu bahasa resmi.</p>
                    </div>

                    <div class="mt-6 space-y-5 sm:space-y-6">
                        <!-- Desktop 2-Column Row 1: Jenis Kejadian & Tanggal Kejadian -->
                        <div class="grid gap-5 lg:grid-cols-2 lg:gap-6">
                            <!-- Jenis Kejadian -->
                            <div>
                                <label class="form-label" for="incident_type">Jenis kejadian <span class="text-[#DC2626]">*</span></label>
                                <select class="form-control @error('incident_type') is-invalid @enderror" id="incident_type" name="incident_type" required>
                                    <option value="">Pilih jenis kejadian</option>
                                    <option value="kupva_tanpa_izin" @selected(old('incident_type') === 'kupva_tanpa_izin')>Dugaan KUPVA tanpa izin</option>
                                    <option value="transaksi_mencurigakan" @selected(old('incident_type') === 'transaksi_mencurigakan')>Transaksi penukaran mencurigakan</option>
                                    <option value="pelanggaran_kurs" @selected(old('incident_type') === 'pelanggaran_kurs')>Informasi kurs tidak wajar/tidak transparan</option>
                                    <option value="penolakan_rupiah" @selected(old('incident_type') === 'penolakan_rupiah')>Penolakan penggunaan Rupiah</option>
                                    <option value="lainnya" @selected(old('incident_type') === 'lainnya')>Lainnya terkait penukaran valuta asing</option>
                                </select>
                                <p class="form-helper">Pilih jenis kejadian yang sesuai dengan laporan Anda.</p>
                                @error('incident_type')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <!-- Tanggal Kejadian -->
                            <div>
                                <label class="form-label" for="incident_date">Tanggal kejadian <span class="text-[#DC4C4C]">*</span></label>
                                <input class="form-control @error('incident_date') is-invalid @enderror" id="incident_date" name="incident_date" type="date" value="{{ old('incident_date') }}" max="{{ now()->toDateString() }}" required>
                                <div class="mt-2.5 flex items-center gap-2">
                                    <button type="button" class="date-shortcut" data-date-offset="0">Hari ini</button>
                                    <button type="button" class="date-shortcut" data-date-offset="1">Kemarin</button>
                                </div>
                                @error('incident_date')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Desktop 2-Column Row 2: Nama Tempat & Perkiraan Waktu -->
                        <div class="grid gap-5 lg:grid-cols-2 lg:gap-6">
                            <!-- Nama atau Ciri Tempat -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class="form-label" for="business_name">Nama atau ciri tempat</label>
                                    <span class="text-xs text-slate-400">Opsional</span>
                                </div>
                                <input class="form-control @error('business_name') is-invalid @enderror" id="business_name" name="business_name" value="{{ old('business_name') }}" maxlength="255" autocomplete="off" placeholder="Contoh: Money Changer XYZ atau toko dekat pasar">
                                <p class="form-helper">Nama tempat, papan nama usaha, atau ciri fisik lokasi.</p>
                                @error('business_name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <!-- Perkiraan Waktu -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class="form-label" for="incident_time">Perkiraan waktu</label>
                                    <span class="text-xs text-slate-400">Opsional</span>
                                </div>
                                <input class="form-control @error('incident_time') is-invalid @enderror" id="incident_time" name="incident_time" type="time" value="{{ old('incident_time') }}">
                                <p class="form-helper">Contoh: 14:30 (jam saat aktivitas berlangsung).</p>
                                @error('incident_time')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Kronologi Kejadian -->
                        <div>
                            <label class="form-label" for="description">Kronologi kejadian <span class="text-[#DC4C4C]">*</span></label>
                            <textarea class="form-control min-h-32 sm:min-h-36 py-3 @error('description') is-invalid @enderror" id="description" name="description" minlength="20" maxlength="5000" placeholder="Contoh: Pada sore hari saya melihat kegiatan penukaran uang asing tanpa plang nama resmi Bank Indonesia di dekat area..." required>{{ old('description') }}</textarea>
                            <div class="mt-1.5 flex items-center justify-between text-xs text-[#64748B]">
                                <span>Minimal 20 karakter.</span>
                                <span id="description-count" class="font-mono text-slate-400">0/5000</span>
                            </div>
                            @error('description')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <!-- Kegiatan Masih Berlangsung -->
                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-3.5 sm:p-4 shadow-2xs transition-colors hover:border-blue-300">
                            <input class="mt-0.5 size-4 rounded border-slate-300 text-[#2563EB] focus:ring-[#2563EB]" type="checkbox" name="is_ongoing" value="1" @checked(old('is_ongoing'))>
                            <div>
                                <strong class="block text-sm font-semibold text-[#0B2342]">Kegiatan masih berlangsung</strong>
                                <span class="mt-0.5 block text-xs leading-relaxed text-[#64748B]">Centang jika aktivitas masih beroperasi atau rutin dilakukan di lokasi tersebut.</span>
                            </div>
                        </label>
                    </div>
                </section>

                <!-- STEP 2: LOKASI -->
                <section class="form-step hidden" data-step="2">
                    <div>
                        <div class="flex flex-wrap items-baseline justify-between gap-1">
                            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Tentukan lokasi kejadian</h2>
                            <span class="text-xs text-slate-400">Khusus wilayah Nusa Tenggara Barat</span>
                        </div>
                        <p class="mt-1 text-xs sm:text-sm text-[#64748B]">Tentukan lokasi tempat kejadian.</p>
                    </div>

                    <!-- Desktop 2-Column Split: Detail Lokasi (42%) vs Map (58%) -->
                    <div class="mt-6 lg:grid lg:grid-cols-12 lg:gap-8 lg:items-start">
                        <!-- Left Column: Form & Helpers (42%) -->
                        <div class="lg:col-span-5 space-y-4">
                            <!-- Quick Location Helpers -->
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                                <!-- GPS Option -->
                                <div class="flex flex-col justify-between rounded-xl bg-[#F8FAFC] p-3.5 sm:p-4 border border-[#CBD5E1] shadow-2xs">
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs sm:text-sm font-semibold text-[#0F172A]">Masih di lokasi?</p>
                                            <span class="rounded-full bg-blue-50 border border-blue-200 px-2 py-0.5 text-[10px] font-bold text-[#2563EB]">GPS</span>
                                        </div>
                                        <p class="mt-1 text-xs text-[#64748B] leading-relaxed">Titik lokasi terisi otomatis dari perangkat Anda.</p>
                                    </div>
                                    <button type="button" id="use-location" class="button-primary mt-3 w-full text-xs sm:text-sm font-semibold py-2.5 min-h-11">
                                        <svg class="size-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 1.75a.75.75 0 0 1 .75.75v1.05a6.5 6.5 0 0 1 5.7 5.7h1.05a.75.75 0 0 1 0 1.5h-1.05a6.5 6.5 0 0 1-5.7 5.7v1.05a.75.75 0 0 1-1.5 0v-1.05a6.5 6.5 0 0 1-5.7-5.7H2.5a.75.75 0 0 1 0-1.5h1.05a6.5 6.5 0 0 1 5.7-5.7V2.5a.75.75 0 0 1 .75-.75ZM5 10a5 5 0 1 0 10 0 5 5 0 0 0-10 0Zm5-2.25a2.25 2.25 0 1 1 0 4.5 2.25 2.25 0 0 1 0-4.5Z"/>
                                        </svg>
                                        <span>Gunakan lokasi saya</span>
                                    </button>
                                </div>

                                <!-- Search Option -->
                                <div class="flex flex-col justify-between rounded-xl bg-[#F8FAFC] p-3.5 sm:p-4 border border-[#CBD5E1] shadow-2xs">
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-xs sm:text-sm font-semibold text-[#0F172A]" for="location-search">Sudah di luar lokasi?</label>
                                            <span class="rounded-full bg-slate-100 border border-slate-200 px-2 py-0.5 text-[10px] font-bold text-slate-600">Cari</span>
                                        </div>
                                        <p class="mt-1 text-xs text-[#64748B] leading-relaxed">Cari jalan, desa, kecamatan, atau tempat di NTB.</p>
                                    </div>
                                    <div class="mt-3 flex gap-2">
                                        <input class="form-control text-xs sm:text-sm h-11" id="location-search" type="search" autocomplete="off" placeholder="Contoh: Pasar Tente Bima">
                                        <button type="button" id="search-location" class="button-secondary px-3.5 text-xs sm:text-sm font-semibold h-11 shrink-0">Cari</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Regency Selection -->
                            <div>
                                <label class="form-label" for="regency">Kabupaten/kota <span class="text-[#DC2626]">*</span></label>
                                <select class="form-control @error('regency') is-invalid @enderror" id="regency" name="regency" required>
                                    <option value="">Pilih kabupaten/kota</option>
                                    @foreach (\App\Enums\NtbRegency::cases() as $regency)
                                        <option value="{{ $regency->value }}" @selected(old('regency') === $regency->value)>{{ $regency->getLabel() }}</option>
                                    @endforeach
                                </select>
                                <p class="form-helper">Wilayah kabupaten/kota tempat kejadian berada di NTB.</p>
                                @error('regency')<p class="form-error">{{ $message }}</p>@enderror
                                @if ($errors->has('latitude') || $errors->has('longitude'))
                                    <p class="form-error">Silakan pilih titik lokasi kejadian pada peta.</p>
                                @endif
                            </div>

                            <!-- Expandable Address Details -->
                            <div>
                                <details class="group rounded-xl border border-[#CBD5E1] bg-white shadow-2xs" @if ($errors->has('district') || $errors->has('village') || $errors->has('address')) open @endif>
                                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3 text-xs sm:text-sm font-semibold text-[#0F172A]">
                                        <span id="location-details-summary">Tambahkan petunjuk alamat <span class="font-normal text-[#64748B]">(opsional)</span></span>
                                        <span class="text-base text-[#64748B] transition-transform duration-200 group-open:rotate-45" aria-hidden="true">+</span>
                                    </summary>
                                    <div class="grid gap-3.5 border-t border-slate-100 p-4">
                                        <div class="grid gap-3 sm:grid-cols-2">
                                            <div>
                                                <label class="form-label text-xs sm:text-sm" for="district">Kecamatan</label>
                                                <input class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district') }}" maxlength="120" autocomplete="address-level2" placeholder="Nama kecamatan">
                                                @error('district')<p class="form-error">{{ $message }}</p>@enderror
                                            </div>
                                            <div>
                                                <label class="form-label text-xs sm:text-sm" for="village">Desa/kelurahan</label>
                                                <input class="form-control @error('village') is-invalid @enderror" id="village" name="village" value="{{ old('village') }}" maxlength="120" autocomplete="address-level3" placeholder="Nama desa atau kelurahan">
                                                @error('village')<p class="form-error">{{ $message }}</p>@enderror
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label text-xs sm:text-sm" for="address">Patokan atau nama jalan</label>
                                            <input class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" maxlength="1000" autocomplete="street-address" placeholder="Contoh: Depan pasar induk, samping ruko biru">
                                            @error('address')<p class="form-error">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                </details>
                            </div>
                        </div>

                        <!-- Right Column: Interactive Map (58%) -->
                        <div class="mt-6 lg:mt-0 lg:col-span-7">
                            <!-- Map status message -->
                            <p id="map-message" class="mb-2 hidden text-xs sm:text-sm" role="status"></p>

                            <!-- Interactive Map Frame (min-h 420px+, rounded-2xl) -->
                            <div
                                id="report-map"
                                class="h-72 sm:h-80 lg:h-[460px] w-full overflow-hidden rounded-2xl border border-[#CBD5E1] bg-slate-100 shadow-2xs"
                                data-mapbox-token="{{ config('services.mapbox.public_token') }}"
                                aria-label="Peta pemilihan lokasi kejadian"
                            ></div>

                            <div class="mt-2.5 flex flex-wrap items-center justify-between gap-2 text-xs text-[#64748B]">
                                <span id="report-map-zoom-hint" class="tambora-map-zoom-hint tambora-map-zoom-hint--inline"></span>
                                <span>Klik peta atau geser penanda untuk menyesuaikan titik secara tepat.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Coordinate Inputs -->
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}" required>
                    <input type="hidden" id="location_accuracy" name="location_accuracy" value="{{ old('location_accuracy') }}">
                </section>

                <!-- STEP 3: BUKTI -->
                <section class="form-step hidden" data-step="3">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Tambahkan bukti</h2>
                        <p class="mt-1 text-xs sm:text-sm text-[#64748B]">Tambahkan foto atau file pendukung jika tersedia.</p>
                    </div>

                    <!-- Review Summary Cards -->
                    <div class="mt-6 grid gap-3 sm:grid-cols-3" aria-label="Ringkasan laporan">
                        <div class="rounded-xl border border-blue-100 bg-[#EEF4FF] p-3.5 shadow-2xs">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-[#2563EB]">Jenis kejadian</span>
                            <strong id="review-incident" class="mt-1 block text-xs sm:text-sm font-semibold text-[#0F172A] break-words">—</strong>
                        </div>
                        <div class="rounded-xl border border-teal-100 bg-[#E8F6F3] p-3.5 shadow-2xs">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-[#168A7A]">Waktu kejadian</span>
                            <strong id="review-date" class="mt-1 block text-xs sm:text-sm font-semibold text-[#0F172A] break-words">—</strong>
                        </div>
                        <div class="rounded-xl border border-amber-100 bg-[#FFF7E3] p-3.5 shadow-2xs">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-[#B45309]">Lokasi kejadian</span>
                            <strong id="review-location" class="mt-1 block text-xs sm:text-sm font-semibold text-[#0F172A] break-words">—</strong>
                        </div>
                    </div>

                    <!-- Desktop 2-Column: Evidence Upload & Confirmation Details -->
                    <div class="mt-6 grid gap-6 lg:grid-cols-12 lg:items-start">
                        <!-- Left: Upload Evidence Dropzone -->
                        <div class="lg:col-span-7">
                            <div class="flex items-center justify-between">
                                <label class="form-label" for="evidence">Punya foto atau dokumen?</label>
                                <span class="text-xs text-slate-400">Boleh dilewati</span>
                            </div>
                            <label class="upload-zone group bg-[#F8FAFC] border border-dashed border-[#CBD5E1] rounded-2xl hover:border-[#2563EB] hover:bg-[#EEF4FF] shadow-2xs transition-colors" for="evidence">
                                <span class="grid size-12 place-items-center rounded-xl bg-blue-50 text-[#2563EB] group-hover:bg-[#2563EB] group-hover:text-white transition-colors shrink-0">
                                    <svg class="size-6" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v6.69L7.03 7.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 1 0-1.06-1.06l-2.22 2.22V2.75Z"/>
                                        <path d="M3.5 10.75a.75.75 0 0 0-1.5 0v3.5A2.75 2.75 0 0 0 4.75 17h10.5A2.75 2.75 0 0 0 18 14.25v-3.5a.75.75 0 0 0-1.5 0v3.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-3.5Z"/>
                                    </svg>
                                </span>
                                <div class="space-y-0.5">
                                    <strong class="block text-sm font-semibold text-[#0F172A]">Tambahkan bukti</strong>
                                    <small class="block text-xs text-[#64748B]">Boleh dilewati · Maks. 5 berkas, masing-masing 10 MB</small>
                                </div>
                            </label>
                            <input class="sr-only" id="evidence" name="evidence[]" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" multiple>
                            <div id="file-list" class="mt-3 grid gap-2"></div>
                            <p class="form-helper">Foto besar otomatis diperkecil di perangkat Anda. Foto yang sudah kecil tetap dikirim dalam kualitas asli.</p>
                            @error('evidence')<p class="form-error">{{ $message }}</p>@enderror
                            @error('evidence.*')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <!-- Right: Post-Submission Information & Confirmation -->
                        <div class="lg:col-span-5 space-y-4">
                            <!-- Post-Submission Information Callout -->
                            <div class="rounded-xl border border-teal-200 bg-[#E8F6F3] p-4 text-xs sm:text-sm leading-relaxed text-[#0F172A] shadow-2xs">
                                <strong class="font-semibold flex items-center gap-1.5 text-[#168A7A]">
                                    <svg class="size-4 shrink-0 text-[#168A7A]" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/>
                                    </svg>
                                    Setelah laporan dikirim:
                                </strong>
                                <span class="mt-1 block text-xs text-[#0F172A]/90 leading-relaxed">Anda akan menerima kode laporan dan PIN untuk melihat perkembangan laporan. Simpan keduanya karena PIN hanya ditampilkan satu kali.</span>
                            </div>

                            <!-- Good Faith Confirmation -->
                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-[#CBD5E1] bg-white p-3.5 sm:p-4 shadow-2xs transition-colors hover:border-[#2563EB]">
                                <input class="mt-0.5 size-4 rounded border-slate-300 text-[#2563EB] focus:ring-[#2563EB]" type="checkbox" id="confirmation" name="good_faith" value="1" @checked(old('good_faith')) required>
                                <span class="text-xs sm:text-sm leading-relaxed text-[#0F172A]">
                                    Informasi ini saya sampaikan dengan itikad baik berdasarkan hal yang saya ketahui. <span class="text-[#DC2626] font-semibold">*</span>
                                </span>
                            </label>
                            @error('good_faith')<p class="form-error mt-2">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                <!-- Action Area: Sticky on mobile, Normal flow inside form card on desktop -->
                <div class="sticky bottom-0 z-30 -mx-4 sm:-mx-6 mt-8 bg-[#F4F7FB]/95 border-t border-slate-200/80 px-4 py-3.5 backdrop-blur-md lg:static lg:z-auto lg:mx-0 lg:mt-10 lg:pt-6 lg:border-t lg:border-slate-200 lg:bg-transparent lg:px-0 lg:py-0 lg:backdrop-blur-none">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <button type="button" id="previous-step" class="button-secondary hidden h-11 sm:h-12 px-5 text-sm font-semibold rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] hover:bg-slate-50 transition-colors">
                                <span>Kembali</span>
                            </button>
                        </div>
                        <div class="flex-1 lg:flex-initial flex justify-end">
                            <button type="button" id="next-step" class="button-primary w-full lg:w-auto lg:min-w-[200px] text-center text-sm font-semibold h-11 sm:h-12 px-6 rounded-xl bg-[#2563EB] text-white hover:bg-[#1D4ED8] transition-colors shadow-2xs">
                                <span>Lanjut ke lokasi</span>
                            </button>
                            <button type="submit" id="submit-report" class="button-primary w-full lg:w-auto lg:min-w-[200px] text-center text-sm font-semibold h-11 sm:h-12 px-6 rounded-xl bg-[#2563EB] text-white hover:bg-[#1D4ED8] transition-colors shadow-2xs hidden">
                                <span>Kirim laporan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Quick Assistance Links -->
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3 sm:gap-4 text-xs sm:text-sm">
                <span class="text-[#64748B] font-medium">Butuh informasi sebelum melapor?</span>
                <a
                    href="{{ route('privacy') }}"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-[#EEF4FF] px-3.5 py-2 text-xs sm:text-sm font-semibold text-[#1D4ED8] hover:bg-blue-100 hover:border-blue-300 transition-colors shadow-2xs"
                >
                    <svg class="size-4 text-[#1D4ED8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    <span>Informasi Privasi</span>
                </a>
                <a
                    href="{{ route('guide') }}"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-amber-200 bg-[#FFF7E3] px-3.5 py-2 text-xs sm:text-sm font-semibold text-[#B45309] hover:bg-amber-100 hover:border-amber-300 transition-colors shadow-2xs"
                >
                    <svg class="size-4 text-[#B45309]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span>Panduan Penggunaan</span>
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
