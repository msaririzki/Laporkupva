<x-layouts.public title="Buat laporan anonim">
    <!-- Hero Header (Mobile-First, Focused, Clean) -->
    <section class="relative bg-[#0B2342] py-6 sm:py-8 text-white overflow-hidden">
        <div class="public-container max-w-xl px-4 sm:px-6">
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#F2B84B] tracking-wide">
                <span class="size-1.5 rounded-full bg-[#F2B84B]"></span>
                <span>Laporan Anonim</span>
            </span>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl lg:text-4xl">Laporkan dengan cepat dan aman</h1>
            <p class="mt-1.5 text-xs sm:text-sm leading-relaxed text-slate-300 max-w-lg">Ceritakan kejadian, tentukan lokasi, lalu kirim laporan secara anonim.</p>
            <div class="mt-3.5 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-slate-300 font-medium">
                <span class="inline-flex items-center gap-1.5">
                    <span class="text-[#F2B84B] font-bold">•</span>
                    <span>±3 menit</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="text-[#F2B84B] font-bold">•</span>
                    <span>Bukti foto opsional</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="text-[#F2B84B] font-bold">•</span>
                    <span>Tanpa identitas</span>
                </span>
            </div>
        </div>
    </section>

    <!-- Main Service Canvas (No heavy outer card box) -->
    <section class="py-6 sm:py-10 bg-white min-h-[60vh]">
        <div class="public-container max-w-xl px-4 sm:px-6">
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-xs sm:text-sm text-[#DC4C4C]" role="alert">
                    <p class="font-bold flex items-center gap-2">
                        <svg class="size-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                        </svg>
                        <span>Beberapa isian perlu diperiksa kembali</span>
                    </p>
                    <p class="mt-1 ml-6 text-xs text-red-700">Silakan lengkapi atau perbaiki isian yang ditandai dengan warna merah.</p>
                </div>
            @endif

            <!-- Stepper Navigation (Total Redesign: Large touchable targets, clear typography) -->
            <nav class="stepper-nav mb-8 sm:mb-10" aria-label="Tahapan formulir">
                <div class="stepper-list">
                    <!-- Connecting Track -->
                    <div class="stepper-track" aria-hidden="true">
                        <div id="stepper-progress-fill" class="stepper-track-progress" style="width: 0%;"></div>
                    </div>

                    @foreach ([
                        ['step' => 1, 'name' => 'Kejadian', 'desc' => 'Ceritakan kejadian'],
                        ['step' => 2, 'name' => 'Lokasi', 'desc' => 'Tentukan lokasi'],
                        ['step' => 3, 'name' => 'Bukti', 'desc' => 'Tambahkan bukti'],
                    ] as $item)
                        <button
                            type="button"
                            class="stepper-step {{ $item['step'] === 1 ? 'is-active' : '' }}"
                            data-progress="{{ $item['step'] }}"
                            aria-label="Langkah {{ $item['step'] }}: {{ $item['name'] }}"
                        >
                            <span class="stepper-circle">
                                <span class="stepper-number">{{ $item['step'] }}</span>
                            </span>
                            <span class="stepper-title">{{ $item['name'] }}</span>
                            <span class="stepper-desc">{{ $item['desc'] }}</span>
                        </button>
                    @endforeach
                </div>
            </nav>

            <!-- Subtle Live Accessibility Step Status -->
            <p id="form-step-status" class="sr-only" aria-live="polite">Langkah 1 dari 3</p>

            <!-- Service Flow Form (Direct Canvas, No heavy card) -->
            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" id="report-form" novalidate>
                @csrf

                <!-- Honeypot -->
                <div class="absolute -left-[10000px] h-px w-px overflow-hidden" aria-hidden="true">
                    <label for="website">Situs web</label>
                    <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                </div>

                <!-- STEP 1: KEJADIAN -->
                <section class="form-step" data-step="1">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0B2342]">Apa yang terjadi?</h2>
                        <p class="mt-1 text-xs sm:text-sm text-[#64748B]">Ceritakan kejadian dengan singkat dan jelas. Tidak perlu bahasa resmi.</p>
                    </div>

                    <div class="mt-6 space-y-5 sm:space-y-6">
                        <!-- Jenis Kejadian -->
                        <div>
                            <label class="form-label" for="incident_type">Jenis kejadian <span class="text-[#DC4C4C]">*</span></label>
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

                        <!-- Tanggal & Waktu -->
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="form-label" for="incident_date">Tanggal kejadian <span class="text-[#DC4C4C]">*</span></label>
                                <input class="form-control @error('incident_date') is-invalid @enderror" id="incident_date" name="incident_date" type="date" value="{{ old('incident_date') }}" max="{{ now()->toDateString() }}" required>
                                <div class="mt-2.5 flex items-center gap-2">
                                    <button type="button" class="date-shortcut" data-date-offset="0">Hari ini</button>
                                    <button type="button" class="date-shortcut" data-date-offset="1">Kemarin</button>
                                </div>
                                @error('incident_date')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

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
                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200/80 bg-slate-50/60 p-4 transition-colors hover:border-slate-300">
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
                            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0B2342]">Tentukan lokasi kejadian</h2>
                            <span class="text-xs text-slate-400">Khusus wilayah Nusa Tenggara Barat</span>
                        </div>
                        <p class="mt-1 text-xs sm:text-sm text-[#64748B]">Tentukan lokasi tempat kejadian.</p>
                    </div>

                    <!-- Quick Location Helpers -->
                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        <!-- GPS Option -->
                        <div class="flex flex-col justify-between rounded-xl bg-blue-50/60 p-4 border border-blue-100">
                            <div>
                                <div class="flex items-center justify-between">
                                    <p class="text-xs sm:text-sm font-semibold text-[#0B2342]">Masih di lokasi?</p>
                                    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-[#2563EB]">GPS</span>
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
                        <div class="flex flex-col justify-between rounded-xl bg-slate-50/80 p-4 border border-slate-200/80">
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class="text-xs sm:text-sm font-semibold text-[#0B2342]" for="location-search">Sudah di luar lokasi?</label>
                                    <span class="rounded-full bg-slate-200/70 px-2 py-0.5 text-[10px] font-bold text-slate-600">Cari</span>
                                </div>
                                <p class="mt-1 text-xs text-[#64748B] leading-relaxed">Cari jalan, desa, kecamatan, atau nama tempat di NTB.</p>
                            </div>
                            <div class="mt-3 flex gap-2">
                                <input class="form-control text-xs sm:text-sm h-11" id="location-search" type="search" autocomplete="off" placeholder="Contoh: Pasar Tente Bima">
                                <button type="button" id="search-location" class="button-secondary px-3.5 text-xs sm:text-sm font-semibold h-11 shrink-0">Cari</button>
                            </div>
                        </div>
                    </div>

                    <!-- Map status message -->
                    <p id="map-message" class="mt-3 hidden text-xs sm:text-sm" role="status"></p>

                    <!-- Interactive Map Frame -->
                    <div
                        id="report-map"
                        class="mt-4 h-64 sm:h-80 w-full overflow-hidden rounded-xl border border-slate-200 bg-slate-100"
                        data-mapbox-token="{{ config('services.mapbox.public_token') }}"
                        aria-label="Peta pemilihan lokasi kejadian"
                    ></div>

                    <div class="mt-2 flex flex-wrap items-center justify-between gap-2 text-xs text-[#64748B]">
                        <span id="report-map-zoom-hint" class="tambora-map-zoom-hint tambora-map-zoom-hint--inline"></span>
                        <span>Klik peta atau geser penanda untuk menyesuaikan titik secara tepat.</span>
                    </div>

                    <!-- Hidden Coordinate Inputs -->
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}" required>
                    <input type="hidden" id="location_accuracy" name="location_accuracy" value="{{ old('location_accuracy') }}">

                    <!-- Regency Selection -->
                    <div class="mt-6">
                        <label class="form-label" for="regency">Kabupaten/kota <span class="text-[#DC4C4C]">*</span></label>
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
                    <details class="group mt-4 rounded-xl border border-slate-200/80 bg-slate-50/50" @if ($errors->has('district') || $errors->has('village') || $errors->has('address')) open @endif>
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3.5 text-xs sm:text-sm font-semibold text-[#0B2342]">
                            <span id="location-details-summary">Tambahkan petunjuk alamat <span class="font-normal text-[#64748B]">(opsional)</span></span>
                            <span class="text-base text-[#64748B] transition-transform duration-200 group-open:rotate-45" aria-hidden="true">+</span>
                        </summary>
                        <div class="grid gap-4 border-t border-slate-200/70 p-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label" for="district">Kecamatan</label>
                                <input class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district') }}" maxlength="120" autocomplete="address-level2" placeholder="Nama kecamatan">
                                @error('district')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="village">Desa/kelurahan</label>
                                <input class="form-control @error('village') is-invalid @enderror" id="village" name="village" value="{{ old('village') }}" maxlength="120" autocomplete="address-level3" placeholder="Nama desa atau kelurahan">
                                @error('village')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="form-label" for="address">Patokan atau nama jalan</label>
                                <input class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" maxlength="1000" autocomplete="street-address" placeholder="Contoh: Depan pasar induk, samping ruko biru">
                                @error('address')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </details>
                </section>

                <!-- STEP 3: BUKTI -->
                <section class="form-step hidden" data-step="3">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0B2342]">Tambahkan bukti</h2>
                        <p class="mt-1 text-xs sm:text-sm text-[#64748B]">Tambahkan foto atau file pendukung jika tersedia.</p>
                    </div>

                    <!-- Review Summary Cards -->
                    <div class="mt-6 grid gap-3 sm:grid-cols-3" aria-label="Ringkasan laporan">
                        <div class="rounded-xl border border-blue-100 bg-[#EAF2FF]/60 p-3.5">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-[#2563EB]">Jenis kejadian</span>
                            <strong id="review-incident" class="mt-1 block text-xs sm:text-sm font-semibold text-[#0B2342] break-words">—</strong>
                        </div>
                        <div class="rounded-xl border border-teal-100 bg-[#E8F6F3]/60 p-3.5">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-[#168A7A]">Waktu kejadian</span>
                            <strong id="review-date" class="mt-1 block text-xs sm:text-sm font-semibold text-[#0B2342] break-words">—</strong>
                        </div>
                        <div class="rounded-xl border border-amber-100 bg-[#FFF4D6]/60 p-3.5">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-[#B45309]">Lokasi kejadian</span>
                            <strong id="review-location" class="mt-1 block text-xs sm:text-sm font-semibold text-[#0B2342] break-words">—</strong>
                        </div>
                    </div>

                    <!-- Upload Evidence Dropzone -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between">
                            <label class="form-label" for="evidence">Punya foto atau dokumen?</label>
                            <span class="text-xs text-slate-400">Boleh dilewati</span>
                        </div>
                        <label class="upload-zone group" for="evidence">
                            <span class="grid size-12 place-items-center rounded-xl bg-blue-50 text-[#2563EB] group-hover:bg-[#2563EB] group-hover:text-white transition-colors shrink-0">
                                <svg class="size-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v6.69L7.03 7.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 1 0-1.06-1.06l-2.22 2.22V2.75Z"/>
                                    <path d="M3.5 10.75a.75.75 0 0 0-1.5 0v3.5A2.75 2.75 0 0 0 4.75 17h10.5A2.75 2.75 0 0 0 18 14.25v-3.5a.75.75 0 0 0-1.5 0v3.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-3.5Z"/>
                                </svg>
                            </span>
                            <div class="space-y-0.5">
                                <strong class="block text-sm font-semibold text-[#0B2342]">Tambahkan bukti</strong>
                                <small class="block text-xs text-[#64748B]">Boleh dilewati · Maks. 5 berkas, masing-masing 10 MB</small>
                            </div>
                        </label>
                        <input class="sr-only" id="evidence" name="evidence[]" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" multiple>
                        <div id="file-list" class="mt-3 grid gap-2"></div>
                        <p class="form-helper">Foto besar otomatis diperkecil di perangkat Anda. Foto yang sudah kecil tetap dikirim dalam kualitas asli.</p>
                        @error('evidence')<p class="form-error">{{ $message }}</p>@enderror
                        @error('evidence.*')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <!-- Post-Submission Information Callout -->
                    <div class="mt-6 rounded-xl border border-teal-200 bg-[#E8F6F3] p-4 text-xs sm:text-sm leading-relaxed text-[#0B2342]">
                        <strong class="font-semibold flex items-center gap-1.5 text-[#168A7A]">
                            <svg class="size-4 shrink-0 text-[#168A7A]" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/>
                            </svg>
                            Setelah laporan dikirim:
                        </strong>
                        <span class="mt-0.5 block text-xs text-[#0B2342]/90">Anda akan menerima kode laporan dan PIN untuk melihat perkembangan laporan. Simpan keduanya karena PIN hanya ditampilkan satu kali.</span>
                    </div>

                    <!-- Good Faith Confirmation -->
                    <label class="mt-5 flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200/80 bg-slate-50/60 p-4 transition-colors hover:border-slate-300">
                        <input class="mt-0.5 size-4 rounded border-slate-300 text-[#2563EB] focus:ring-[#2563EB]" type="checkbox" id="confirmation" name="good_faith" value="1" @checked(old('good_faith')) required>
                        <span class="text-xs sm:text-sm leading-relaxed text-[#0B2342]">
                            Informasi ini saya sampaikan dengan itikad baik berdasarkan hal yang saya ketahui. <span class="text-[#DC4C4C] font-semibold">*</span>
                        </span>
                    </label>
                    @error('good_faith')<p class="form-error mt-2">{{ $message }}</p>@enderror
                </section>

                <!-- Sticky Bottom CTA (Mobile-First: Full Width on Mobile, Clear Hierarchy) -->
                <div class="sticky bottom-0 z-30 -mx-4 sm:-mx-6 mt-8 bg-white/95 border-t border-slate-200/80 px-4 py-3.5 sm:px-6 backdrop-blur-md shadow-[0_-4px_20px_rgba(11,35,66,0.06)]">
                    <div class="max-w-xl mx-auto flex items-center gap-3">
                        <button type="button" id="previous-step" class="button-secondary hidden text-xs sm:text-sm font-semibold h-12 px-4 shrink-0">
                            <span>← Kembali</span>
                        </button>
                        <button type="button" id="next-step" class="button-primary flex-1 w-full text-center text-sm sm:text-base font-semibold h-12">
                            <span>Lanjut ke lokasi →</span>
                        </button>
                        <button type="submit" id="submit-report" class="button-primary flex-1 w-full text-center text-sm sm:text-base font-semibold h-12 hidden">
                            <span>Kirim sekarang</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-layouts.public>
