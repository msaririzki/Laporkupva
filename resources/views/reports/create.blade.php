<x-layouts.public title="Buat laporan anonim">
    <!-- Hero Header (Clean Light Canvas, Elegant Reassurance) -->
    <section class="relative bg-white py-8 sm:py-10 lg:py-12 border-b border-[#E2E8F0]">
        <div class="public-container max-w-[1240px] px-4 sm:px-6 lg:px-10">
            <!-- Top Row: Laporan Anonim (Left) & Identitas Anda tetap terlindungi (Right) -->
            <div class="flex items-center justify-between w-full">
                <div class="inline-flex items-center gap-2 rounded-full border border-blue-200/80 bg-blue-50/80 px-3.5 py-1 text-xs font-semibold text-[#2563EB]">
                    <svg class="size-3.5 text-[#2563EB]" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                    </svg>
                    <span>Laporan Anonim</span>
                </div>
                <div class="hidden sm:inline-flex items-center gap-1.5 text-xs text-[#64748B] font-medium">
                    <svg class="size-3.5 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    <span>Identitas Anda tetap terlindungi</span>
                </div>
            </div>

            <!-- Content Area -->
            <div class="mt-4 sm:mt-5 text-left lg:text-center max-w-[800px] lg:mx-auto">
                <h1 class="text-2xl font-extrabold tracking-tight text-[#0F172A] sm:text-3xl lg:text-[34px] lg:leading-tight">
                    Laporkan dengan cepat dan aman
                </h1>

                <p class="mt-2 text-xs sm:text-sm lg:text-[15px] leading-relaxed text-[#64748B] max-w-[720px] lg:mx-auto">
                    Ceritakan kejadian, tentukan lokasi, lalu kirim laporan secara anonim.
                </p>

                <!-- Single Simple Badge: ±3 menit with small clock icon -->
                <div class="mt-3 flex items-center lg:justify-center">
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 bg-slate-100/90 px-3 py-1 rounded-full border border-slate-200/60">
                        <svg class="size-3.5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd"/>
                        </svg>
                        <span>±3 menit</span>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Service Canvas -->
    <section class="py-8 sm:py-12 lg:py-14 bg-[#F8FAFC] min-h-[60vh]">
        <div class="public-container max-w-[1240px] px-4 sm:px-6 lg:px-10">
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-xs sm:text-sm text-[#DC2626]" role="alert">
                    <p class="font-bold flex items-center gap-2">
                        <svg class="size-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                        </svg>
                        <span>Beberapa isian perlu diperiksa kembali</span>
                    </p>
                    <p class="mt-1 ml-6 text-xs text-red-700">Silakan lengkapi atau perbaiki isian yang ditandai dengan warna merah.</p>
                </div>
            @endif

            <!-- Stepper Navigation (Modern Wide Horizontal Desktop Track) -->
            <nav class="stepper-nav" aria-label="Tahapan formulir">
                <div class="stepper-list">
                    <!-- Connecting Track -->
                    <div class="stepper-track" aria-hidden="true">
                        <div id="stepper-progress-fill" class="stepper-track-progress" style="width: 0%;"></div>
                    </div>

                    @foreach ([
                        ['step' => 1, 'num' => '01', 'name' => 'Rincian Laporan', 'desc' => 'Ceritakan kejadian'],
                        ['step' => 2, 'num' => '02', 'name' => 'Lokasi Kejadian', 'desc' => 'Tentukan lokasi'],
                        ['step' => 3, 'num' => '03', 'name' => 'Bukti Pendukung', 'desc' => 'Tambahkan foto'],
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

            <!-- Service Flow Form (Single Clean Card Container) -->
            <form
                action="{{ route('reports.store') }}"
                method="POST"
                enctype="multipart/form-data"
                id="report-form"
                class="bg-white border border-[#E2E8F0] rounded-3xl p-6 sm:p-9 lg:p-12 shadow-xs"
                novalidate
            >
                @csrf

                <!-- Honeypot -->
                <div class="absolute -left-[10000px] h-px w-px overflow-hidden" aria-hidden="true">
                    <label for="website">Situs web</label>
                    <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                </div>

                <!-- STEP 1: KEJADIAN -->
                <section class="form-step max-w-3xl mx-auto" data-step="1">
                    <div class="border-b border-slate-100 pb-5">
                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 border border-blue-200/60 px-3 py-1 text-xs font-bold text-[#2563EB] mb-2.5">
                            <span>Langkah 01</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-[#0F172A]">Ceritakan kejadian</h2>
                        <p class="mt-1 text-xs sm:text-sm text-[#64748B]">Pilih masalahnya dan ceritakan apa yang terjadi secara santai dan jujur.</p>
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
                                <input class="form-control @error('business_name') is-invalid @enderror" id="business_name" name="business_name" value="{{ old('business_name') }}" maxlength="255" autocomplete="off" placeholder="Nama toko, usaha, atau ciri fisik tempat">
                                @error('business_name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <!-- Perkiraan Waktu -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class="form-label" for="incident_time">Perkiraan waktu</label>
                                    <span class="text-xs text-slate-400">Opsional</span>
                                </div>
                                <input class="form-control @error('incident_time') is-invalid @enderror" id="incident_time" name="incident_time" type="time" value="{{ old('incident_time') }}">
                                @error('incident_time')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Kronologi Kejadian -->
                        <div>
                            <label class="form-label" for="description">Kronologi kejadian <span class="text-[#DC4C4C]">*</span></label>
                            <textarea class="form-control min-h-32 sm:min-h-36 py-3 @error('description') is-invalid @enderror" id="description" name="description" minlength="20" maxlength="5000" placeholder="Jelaskan kronologi kejadian secara singkat dan jelas..." required>{{ old('description') }}</textarea>
                            <div class="mt-1.5 flex items-center justify-between text-xs text-[#64748B]">
                                <span>Minimal 20 karakter.</span>
                                <span id="description-count" class="font-mono text-slate-400">0/5000</span>
                            </div>
                            @error('description')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <!-- Kegiatan Masih Berlangsung -->
                        <label class="flex cursor-pointer items-start gap-3.5 rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4 transition-all hover:border-[#2563EB]/40 hover:bg-blue-50/20 shadow-xs">
                            <input class="mt-0.5 size-4.5 rounded border-slate-300 text-[#2563EB] focus:ring-[#2563EB]" type="checkbox" name="is_ongoing" value="1" @checked(old('is_ongoing'))>
                            <div>
                                <strong class="block text-sm font-bold text-[#0F172A]">Kegiatan masih berlangsung</strong>
                                <span class="mt-0.5 block text-xs leading-relaxed text-[#64748B]">Centang jika aktivitas masih beroperasi atau rutin dilakukan di lokasi tersebut.</span>
                            </div>
                        </label>
                    </div>
                </section>

                <!-- STEP 2: LOKASI -->
                <section class="form-step hidden" data-step="2">
                    <div class="border-b border-slate-100 pb-5">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 border border-blue-200/60 px-3 py-1 text-xs font-bold text-[#2563EB]">
                                <span>Langkah 02</span>
                            </div>
                            <span class="text-xs font-medium text-[#64748B] bg-slate-100 px-2.5 py-1 rounded-full">Khusus Wilayah NTB</span>
                        </div>
                        <h2 class="mt-2.5 text-xl sm:text-2xl font-extrabold tracking-tight text-[#0F172A]">Tentukan lokasinya</h2>
                        <p class="mt-1 text-xs sm:text-sm text-[#64748B]">Pilih kabupaten/kota dan tandai titik lokasi kejadian pada peta interaktif.</p>
                    </div>

                    <!-- Desktop 2-Column Split: Form (42%) vs Map (58%) -->
                    <div class="mt-6 lg:grid lg:grid-cols-12 lg:gap-8 lg:items-start">
                        <!-- Left Column: Form & Helpers (42%) -->
                        <div class="lg:col-span-5 space-y-4">
                            <!-- Quick Location Helpers -->
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                                <!-- GPS Option -->
                                <div class="flex flex-col justify-between rounded-2xl bg-[#F8FAFC] p-4 border border-[#E2E8F0] shadow-xs hover:border-[#2563EB]/40 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="grid size-10 shrink-0 place-items-center rounded-xl bg-blue-50 text-[#2563EB]">
                                            <svg class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 1.75a.75.75 0 0 1 .75.75v1.05a6.5 6.5 0 0 1 5.7 5.7h1.05a.75.75 0 0 1 0 1.5h-1.05a6.5 6.5 0 0 1-5.7 5.7v1.05a.75.75 0 0 1-1.5 0v-1.05a6.5 6.5 0 0 1-5.7-5.7H2.5a.75.75 0 0 1 0-1.5h1.05a6.5 6.5 0 0 1 5.7-5.7V2.5a.75.75 0 0 1 .75-.75ZM5 10a5 5 0 1 0 10 0 5 5 0 0 0-10 0Zm5-2.25a2.25 2.25 0 1 1 0 4.5 2.25 2.25 0 0 1 0-4.5Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-[#0F172A]">Sedang di lokasi?</h3>
                                            <p class="mt-0.5 text-xs text-[#64748B] leading-relaxed">Ambil koordinat otomatis dari sensor GPS perangkat Anda.</p>
                                        </div>
                                    </div>
                                    <button type="button" id="use-location" class="mt-3.5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white border border-[#CBD5E1] px-4 py-2.5 text-xs sm:text-sm font-semibold text-[#0F172A] hover:bg-slate-50 hover:border-[#2563EB] hover:text-[#2563EB] transition-colors shadow-2xs">
                                        <svg class="size-4 text-[#2563EB]" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 1.75a.75.75 0 0 1 .75.75v1.05a6.5 6.5 0 0 1 5.7 5.7h1.05a.75.75 0 0 1 0 1.5h-1.05a6.5 6.5 0 0 1-5.7 5.7v1.05a.75.75 0 0 1-1.5 0v-1.05a6.5 6.5 0 0 1-5.7-5.7H2.5a.75.75 0 0 1 0-1.5h1.05a6.5 6.5 0 0 1 5.7-5.7V2.5a.75.75 0 0 1 .75-.75ZM5 10a5 5 0 1 0 10 0 5 5 0 0 0-10 0Zm5-2.25a2.25 2.25 0 1 1 0 4.5 2.25 2.25 0 0 1 0-4.5Z"/>
                                        </svg>
                                        <span>Gunakan lokasi saya</span>
                                    </button>
                                </div>

                                <!-- Search Option -->
                                <div class="flex flex-col justify-between rounded-2xl bg-[#F8FAFC] p-4 border border-[#E2E8F0] shadow-xs hover:border-[#2563EB]/40 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="grid size-10 shrink-0 place-items-center rounded-xl bg-slate-100 text-[#0F172A]">
                                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="11" cy="11" r="8"/>
                                                <path d="m21 21-4.3-4.3"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <label class="text-sm font-bold text-[#0F172A]" for="location-search">Cari nama tempat / jalan</label>
                                            <p class="mt-0.5 text-xs text-[#64748B] leading-relaxed">Ketik jalan, desa, pasar, atau tempat di NTB.</p>
                                        </div>
                                    </div>
                                    <div class="mt-3.5 flex gap-2">
                                        <input class="form-control text-xs sm:text-sm h-10 rounded-xl" id="location-search" type="search" autocomplete="off" placeholder="Contoh: Pasar Tente Bima">
                                        <button type="button" id="search-location" class="inline-flex items-center justify-center rounded-xl bg-[#2563EB] px-4 text-xs sm:text-sm font-semibold text-white hover:bg-[#1D4ED8] transition-colors shadow-2xs shrink-0">Cari</button>
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
                                <details class="group rounded-2xl border border-[#E2E8F0] bg-white shadow-xs" @if ($errors->has('district') || $errors->has('village') || $errors->has('address')) open @endif>
                                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3.5 text-xs sm:text-sm font-semibold text-[#0F172A]">
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

                            <!-- Interactive Map Frame (h-[480px], rounded-3xl) -->
                            <div
                                id="report-map"
                                class="h-80 sm:h-96 lg:h-[480px] w-full overflow-hidden rounded-3xl border border-[#CBD5E1] bg-slate-100 shadow-xs"
                                data-mapbox-token="{{ config('services.mapbox.public_token') }}"
                                aria-label="Peta pemilihan lokasi kejadian"
                            ></div>

                            <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-xs text-[#64748B]">
                                <span id="report-map-zoom-hint" class="tambora-map-zoom-hint tambora-map-zoom-hint--inline"></span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="size-3.5 text-[#2563EB]" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="m9.69 18.933.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 0 0 .281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 1 0 3 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 0 0 2.273 1.765 11.842 11.842 0 0 0 .976.549l.04.018.018.008.006.003ZM10 11.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" clip-rule="evenodd"/>
                                    </svg>
                                    Klik peta atau geser penanda untuk menyesuaikan titik secara tepat.
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Coordinate Inputs -->
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}" required>
                    <input type="hidden" id="location_accuracy" name="location_accuracy" value="{{ old('location_accuracy') }}">
                </section>

                <!-- STEP 3: BUKTI -->
                <section class="form-step hidden max-w-3xl mx-auto" data-step="3">
                    <div class="border-b border-slate-100 pb-5">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 border border-blue-200/60 px-3 py-1 text-xs font-bold text-[#2563EB]">
                                <span>Langkah 03</span>
                            </div>
                            <span class="text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-1 rounded-full">Boleh dilewati</span>
                        </div>
                        <h2 class="mt-2.5 text-xl sm:text-2xl font-extrabold tracking-tight text-[#0F172A]">Tambahkan foto & bukti</h2>
                        <p class="mt-1 text-xs sm:text-sm text-[#64748B]">Sertakan foto lokasi, papan nama, atau dokumen pendukung untuk memperkuat laporan Anda.</p>
                    </div>

                    <!-- Review Summary Cards -->
                    <div class="mt-6 grid gap-3 sm:grid-cols-3" aria-label="Ringkasan laporan">
                        <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4 shadow-xs transition-all hover:bg-white hover:shadow-sm">
                            <div class="flex items-center gap-2">
                                <span class="grid size-6 place-items-center rounded-md bg-blue-50 text-[11px] font-bold text-[#2563EB]">1</span>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Jenis kejadian</span>
                            </div>
                            <strong id="review-incident" class="mt-2 block text-sm font-bold text-[#0F172A] break-words leading-snug">—</strong>
                        </div>
                        <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4 shadow-xs transition-all hover:bg-white hover:shadow-sm">
                            <div class="flex items-center gap-2">
                                <span class="grid size-6 place-items-center rounded-md bg-emerald-50 text-[11px] font-bold text-emerald-600">2</span>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Waktu kejadian</span>
                            </div>
                            <strong id="review-date" class="mt-2 block text-sm font-bold text-[#0F172A] break-words leading-snug">—</strong>
                        </div>
                        <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4 shadow-xs transition-all hover:bg-white hover:shadow-sm">
                            <div class="flex items-center gap-2">
                                <span class="grid size-6 place-items-center rounded-md bg-amber-50 text-[11px] font-bold text-amber-600">3</span>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Lokasi kejadian</span>
                            </div>
                            <strong id="review-location" class="mt-2 block text-sm font-bold text-[#0F172A] break-words leading-snug">—</strong>
                        </div>
                    </div>

                    <!-- Evidence Upload Dropzone (Proportional desktop width, centered) -->
                    <div class="mt-6 space-y-6">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="form-label mb-0" for="evidence">Punya foto atau dokumen?</label>
                                <span class="text-xs font-medium text-[#64748B] bg-slate-100 px-2.5 py-0.5 rounded-full">Boleh dilewati</span>
                            </div>
                            <label class="upload-zone group flex flex-col items-center justify-center p-8 rounded-3xl border-2 border-dashed border-[#CBD5E1] bg-[#F8FAFC] hover:border-[#2563EB] hover:bg-blue-50/30 transition-all cursor-pointer text-center" for="evidence">
                                <div class="grid size-14 place-items-center rounded-2xl bg-blue-50 text-[#2563EB] group-hover:bg-[#2563EB] group-hover:text-white transition-all shadow-xs">
                                    <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="17 8 12 3 7 8"/>
                                        <line x1="12" y1="3" x2="12" y2="15"/>
                                    </svg>
                                </div>
                                <strong class="mt-4 block text-sm font-bold text-[#0F172A] group-hover:text-[#2563EB] transition-colors">
                                    Pilih foto atau seret ke sini
                                </strong>
                                <span class="mt-1 block text-xs text-[#64748B]">
                                    Format: JPG, PNG, WEBP, atau PDF · Maks. 5 berkas, masing-masing 10 MB
                                </span>
                                <span class="mt-3 inline-flex items-center gap-1.5 rounded-lg border border-[#CBD5E1] bg-white px-3 py-1.5 text-xs font-semibold text-[#0F172A] shadow-2xs group-hover:border-[#2563EB] group-hover:text-[#2563EB]">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                        <circle cx="9" cy="9" r="2"/>
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                    </svg>
                                    <span>Pilih berkas dari perangkat</span>
                                </span>
                            </label>
                            <input class="sr-only" id="evidence" name="evidence[]" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" multiple>
                            <div id="file-list" class="mt-3 grid gap-2"></div>
                            <p class="form-helper">Foto besar otomatis diperkecil di perangkat Anda sebelum dikirim agar hemat kuota.</p>
                            @error('evidence')<p class="form-error">{{ $message }}</p>@enderror
                            @error('evidence.*')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <!-- Post-Submission Information Callout -->
                        <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-5 text-xs sm:text-sm leading-relaxed text-[#0F172A] shadow-xs">
                            <div class="flex items-center gap-2.5">
                                <div class="grid size-8 shrink-0 place-items-center rounded-lg bg-blue-100 text-[#2563EB]">
                                    <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                </div>
                                <strong class="font-bold text-[#0F172A]">Setelah laporan terkirim:</strong>
                            </div>
                            <p class="mt-2.5 text-xs sm:text-sm text-[#475569] leading-relaxed">
                                Anda akan memperoleh <strong class="text-[#0F172A]">Kode Laporan</strong> dan <strong class="text-[#0F172A]">PIN Rahasia 6 digit</strong>. Keduanya digunakan untuk melihat perkembangan tindak lanjut tanpa perlu membuat akun.
                            </p>
                        </div>

                        <!-- Good Faith Confirmation -->
                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4 shadow-xs transition-colors hover:border-[#2563EB]/40 hover:bg-blue-50/20">
                            <input class="mt-1 size-4.5 rounded border-slate-300 text-[#2563EB] focus:ring-[#2563EB]" type="checkbox" id="confirmation" name="good_faith" value="1" @checked(old('good_faith')) required>
                            <span class="text-xs sm:text-sm leading-relaxed text-[#0F172A] font-medium">
                                Saya menyatakan bahwa laporan ini disampaikan dengan itikad baik berdasarkan kejadian nyata yang saya ketahui. <span class="text-[#DC2626] font-bold">*</span>
                            </span>
                        </label>
                        @error('good_faith')<p class="form-error mt-2">{{ $message }}</p>@enderror
                    </div>
                </section>

                <!-- Action Area: Clean Bar (NO arrow icons, buttons side-by-side on desktop) -->
                <div class="sticky bottom-0 z-30 -mx-6 sm:-mx-9 lg:-mx-12 -mb-6 sm:-mb-9 lg:-mb-12 mt-8 bg-white/95 border-t border-[#E2E8F0] p-4 sm:p-6 lg:p-8 backdrop-blur-md rounded-b-3xl">
                    <div class="flex items-center justify-between sm:justify-end gap-3 sm:gap-4">
                        <button type="button" id="previous-step" class="hidden min-h-11 sm:min-h-12 items-center justify-center rounded-xl border border-[#CBD5E1] bg-white px-5 sm:px-6 py-2.5 text-sm font-semibold text-[#0F172A] shadow-2xs hover:bg-slate-50 transition-colors shrink-0 w-auto">
                            <span>Kembali</span>
                        </button>
                        <button type="button" id="next-step" class="flex-1 sm:flex-initial sm:w-auto min-h-11 sm:min-h-12 items-center justify-center rounded-xl bg-[#2563EB] px-6 sm:px-7 py-2.5 text-center text-sm font-semibold text-white hover:bg-[#1D4ED8] transition-colors shadow-xs">
                            <span>Lanjut ke lokasi</span>
                        </button>
                        <button type="submit" id="submit-report" class="flex-1 sm:flex-initial sm:w-auto min-h-11 sm:min-h-12 items-center justify-center rounded-xl bg-[#2563EB] px-6 sm:px-7 py-2.5 text-center text-sm font-semibold text-white hover:bg-[#1D4ED8] transition-colors shadow-xs hidden">
                            <span>Kirim laporan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-layouts.public>
