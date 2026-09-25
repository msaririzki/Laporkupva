<x-layouts.public title="Buat laporan anonim">
    <section class="bg-navy-950 py-8 text-white sm:py-10">
        <div class="public-container max-w-4xl">
            <div class="eyebrow-dark"><span class="size-2 rounded-full bg-teal-300"></span>Laporan anonim</div>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">Laporkan dengan cepat dan aman</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-100/80 sm:text-base">Cukup ceritakan kejadian, pilih lokasinya, lalu kirim. Kami tidak meminta nama, nomor telepon, atau identitas Anda.</p>
            <div class="mt-5 flex flex-wrap gap-2 text-xs font-bold text-blue-50">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-2"><span class="size-2 rounded-full bg-teal-300"></span>Sekitar 3 menit</span>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-2"><span aria-hidden="true">✓</span>Bukti foto opsional</span>
            </div>
        </div>
    </section>

    <section class="py-7 sm:py-10">
        <div class="public-container max-w-4xl">
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                    <p class="font-bold">Beberapa bagian perlu diperiksa kembali.</p>
                    <p class="mt-1">Kami menandai kolom yang belum lengkap atau belum sesuai.</p>
                </div>
            @endif

            <div class="mb-4 flex items-center justify-between gap-4">
                <p id="form-step-status" class="text-sm font-extrabold text-navy-950" aria-live="polite">Langkah 1 dari 3</p>
                <p class="text-xs font-semibold text-slate-400"><span class="text-red-600">*</span> Wajib diisi</p>
            </div>

            <ol class="mb-5 grid grid-cols-3 gap-2" aria-label="Tahapan formulir">
                @foreach ([['Kejadian', 'Ceritakan inti laporan'], ['Lokasi', 'Pilih titik kejadian'], ['Kirim', 'Periksa dan kirim']] as $index => [$label, $caption])
                    <li>
                        <button
                            type="button"
                            class="form-progress {{ $index === 0 ? 'is-active' : '' }}"
                            data-progress="{{ $index + 1 }}"
                            aria-label="Langkah {{ $index + 1 }}: {{ $label }}"
                        >
                            <span class="form-progress-number">{{ $index + 1 }}</span>
                            <span class="hidden sm:block"><strong>{{ $label }}</strong><small>{{ $caption }}</small></span>
                        </button>
                    </li>
                @endforeach
            </ol>

            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" id="report-form" class="rounded-3xl border border-slate-200 bg-white shadow-soft" novalidate>
                @csrf

                <section class="form-step p-5 sm:p-8" data-step="1">
                    <div class="form-heading"><span>1</span><div><h2>Apa yang terjadi?</h2><p>Isi informasi inti yang Anda ketahui. Tidak harus menggunakan bahasa resmi.</p></div></div>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="form-label" for="incident_type">Jenis laporan <span>*</span></label>
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
                        <div class="sm:col-span-2">
                            <label class="form-label" for="business_name">Nama atau ciri tempat <span class="optional">Jika tahu</span></label>
                            <input class="form-control @error('business_name') is-invalid @enderror" id="business_name" name="business_name" value="{{ old('business_name') }}" maxlength="255" autocomplete="off" placeholder="Contoh: Money Changer XYZ atau toko dekat pasar">
                            @error('business_name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label" for="incident_date">Tanggal kejadian <span>*</span></label>
                            <input class="form-control @error('incident_date') is-invalid @enderror" id="incident_date" name="incident_date" type="date" value="{{ old('incident_date') }}" max="{{ now()->toDateString() }}" required>
                            <div class="mt-2 flex gap-2">
                                <button type="button" class="date-shortcut" data-date-offset="0">Hari ini</button>
                                <button type="button" class="date-shortcut" data-date-offset="1">Kemarin</button>
                            </div>
                            @error('incident_date')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label" for="incident_time">Perkiraan waktu <span class="optional">Opsional</span></label>
                            <input class="form-control @error('incident_time') is-invalid @enderror" id="incident_time" name="incident_time" type="time" value="{{ old('incident_time') }}">
                            @error('incident_time')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label" for="description">Kronologi kejadian <span>*</span></label>
                            <textarea class="form-control min-h-28 @error('description') is-invalid @enderror" id="description" name="description" minlength="20" maxlength="5000" placeholder="Contoh: Pada sore hari saya melihat kegiatan penukaran uang tanpa papan izin..." required>{{ old('description') }}</textarea>
                            <div class="mt-2 flex justify-between gap-3 text-xs text-slate-400"><span>Tulis singkat dan jelas, minimal 20 karakter.</span><span id="description-count">0/5000</span></div>
                            @error('description')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <label class="sm:col-span-2 flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-blue-200 hover:bg-blue-50/50">
                            <input class="mt-0.5 size-4 rounded border-slate-300 text-blue-700 focus:ring-blue-600" type="checkbox" name="is_ongoing" value="1" @checked(old('is_ongoing'))>
                            <span><strong class="block text-sm text-slate-800">Kegiatan masih berlangsung</strong><span class="mt-1 block text-xs leading-5 text-slate-500">Centang jika aktivitas tersebut masih terlihat atau rutin terjadi.</span></span>
                        </label>
                    </div>
                </section>

                <section class="form-step hidden p-5 sm:p-8" data-step="2">
                    <div class="form-heading"><span>2</span><div><h2>Di mana kejadiannya?</h2><p>Pilih lokasi pihak yang dilaporkan—bukan rumah atau posisi Anda sekarang jika sudah berpindah.</p></div></div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                            <p class="text-sm font-extrabold text-blue-950">Masih berada di lokasi?</p>
                            <p class="mt-1 text-xs leading-5 text-blue-800/75">Gunakan GPS agar titik terisi otomatis.</p>
                            <button type="button" id="use-location" class="button-primary mt-3 w-full" title="Gunakan lokasi perangkat"><svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.75a.75.75 0 0 1 .75.75v1.05a6.5 6.5 0 0 1 5.7 5.7h1.05a.75.75 0 0 1 0 1.5h-1.05a6.5 6.5 0 0 1-5.7 5.7v1.05a.75.75 0 0 1-1.5 0v-1.05a6.5 6.5 0 0 1-5.7-5.7H2.5a.75.75 0 0 1 0-1.5h1.05a6.5 6.5 0 0 1 5.7-5.7V2.5a.75.75 0 0 1 .75-.75ZM5 10a5 5 0 1 0 10 0 5 5 0 0 0-10 0Zm5-2.25a2.25 2.25 0 1 1 0 4.5 2.25 2.25 0 0 1 0-4.5Z"/></svg>Gunakan lokasi saya</button>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <label class="text-sm font-extrabold text-slate-800" for="location-search">Sudah meninggalkan lokasi?</label>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Cari jalan, desa, kecamatan, atau nama tempat.</p>
                            <div class="mt-3 flex gap-2">
                                <div class="relative min-w-0 flex-1">
                                    <input class="form-control pl-10" id="location-search" type="search" autocomplete="off" placeholder="Contoh: Pasar Tente">
                                    <svg class="pointer-events-none absolute left-3.5 top-3.5 size-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.078 3.079a.75.75 0 1 1-1.06 1.06l-3.079-3.078A7 7 0 0 1 2 9Z" clip-rule="evenodd"/></svg>
                                </div>
                                <button type="button" id="search-location" class="button-secondary px-4">Cari</button>
                            </div>
                        </div>
                    </div>
                    <p id="map-message" class="mt-3 hidden text-sm" role="status"></p>
                    <div
                        id="report-map"
                        class="mt-4 h-72 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 sm:h-[360px]"
                        data-mapbox-token="{{ config('services.mapbox.public_token') }}"
                        aria-label="Peta pemilihan lokasi"
                    ></div>
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs leading-5 text-slate-500">
                        <span id="report-map-zoom-hint" class="tambora-map-zoom-hint tambora-map-zoom-hint--inline"></span>
                        <span>Klik peta atau geser penanda untuk menyesuaikan titik.</span>
                    </div>

                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}" required>
                    <input type="hidden" id="location_accuracy" name="location_accuracy" value="{{ old('location_accuracy') }}">

                    <div class="mt-6">
                        <div>
                            <label class="form-label" for="regency">Kabupaten/kota <span>*</span></label>
                            <select class="form-control @error('regency') is-invalid @enderror" id="regency" name="regency" required>
                                <option value="">Pilih kabupaten/kota</option>
                                @foreach (['Kota Mataram', 'Kota Bima', 'Kabupaten Lombok Barat', 'Kabupaten Lombok Tengah', 'Kabupaten Lombok Timur', 'Kabupaten Lombok Utara', 'Kabupaten Sumbawa', 'Kabupaten Sumbawa Barat', 'Kabupaten Dompu', 'Kabupaten Bima'] as $regency)
                                    <option value="{{ $regency }}" @selected(old('regency') === $regency)>{{ $regency }}</option>
                                @endforeach
                            </select>
                            @error('regency')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        @if ($errors->has('latitude') || $errors->has('longitude'))<p class="form-error sm:col-span-2">Silakan pilih titik lokasi kejadian pada peta.</p>@endif
                    </div>

                    <details class="group mt-4 rounded-2xl border border-slate-200 bg-slate-50" @if ($errors->has('district') || $errors->has('village') || $errors->has('address')) open @endif>
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3 text-sm font-bold text-slate-700">
                            <span id="location-details-summary">Tambahkan petunjuk alamat <span class="font-medium text-slate-400">(opsional)</span></span>
                            <span class="text-lg text-slate-400 transition group-open:rotate-45" aria-hidden="true">+</span>
                        </summary>
                        <div class="grid gap-5 border-t border-slate-200 p-4 sm:grid-cols-2">
                            <div><label class="form-label" for="district">Kecamatan <span class="optional">Opsional</span></label><input class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district') }}" maxlength="120" autocomplete="address-level2" placeholder="Nama kecamatan">@error('district')<p class="form-error">{{ $message }}</p>@enderror</div>
                            <div><label class="form-label" for="village">Desa/kelurahan <span class="optional">Opsional</span></label><input class="form-control @error('village') is-invalid @enderror" id="village" name="village" value="{{ old('village') }}" maxlength="120" autocomplete="address-level3" placeholder="Nama desa atau kelurahan">@error('village')<p class="form-error">{{ $message }}</p>@enderror</div>
                            <div class="sm:col-span-2"><label class="form-label" for="address">Patokan atau nama jalan <span class="optional">Opsional</span></label><input class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" maxlength="1000" autocomplete="street-address" placeholder="Contoh: depan pasar, samping minimarket">@error('address')<p class="form-error">{{ $message }}</p>@enderror</div>
                        </div>
                    </details>
                </section>

                <section class="form-step hidden p-5 sm:p-8" data-step="3">
                    <div class="form-heading"><span>3</span><div><h2>Periksa lalu kirim</h2><p>Pastikan tiga informasi utama berikut sudah benar.</p></div></div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-3" aria-label="Ringkasan laporan">
                        <div class="report-review"><span>Jenis laporan</span><strong id="review-incident">—</strong></div>
                        <div class="report-review"><span>Waktu kejadian</span><strong id="review-date">—</strong></div>
                        <div class="report-review"><span>Lokasi</span><strong id="review-location">—</strong></div>
                    </div>

                    <div class="mt-6">
                        <label class="form-label" for="evidence">Punya foto atau dokumen? <span class="optional">Opsional</span></label>
                        <label class="upload-zone" for="evidence"><span class="grid size-11 place-items-center rounded-xl bg-blue-50 text-blue-700"><svg class="size-6" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 2.75a.75.75 0 0 0-1.5 0v6.69L7.03 7.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 1 0-1.06-1.06l-2.22 2.22V2.75Z"/><path d="M3.5 10.75a.75.75 0 0 0-1.5 0v3.5A2.75 2.75 0 0 0 4.75 17h10.5A2.75 2.75 0 0 0 18 14.25v-3.5a.75.75 0 0 0-1.5 0v3.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-3.5Z"/></svg></span><span><strong>Tambahkan bukti</strong><small>Boleh dilewati · Maks. 5 berkas, masing-masing 10 MB</small></span></label>
                        <input class="sr-only" id="evidence" name="evidence[]" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" multiple>
                        <div id="file-list" class="mt-3 grid gap-2"></div>
                        <p class="mt-2 text-xs leading-5 text-slate-500">Foto besar otomatis diperkecil di perangkat Anda. Foto yang sudah kecil tetap dikirim dalam kualitas asli.</p>
                        @error('evidence')<p class="form-error">{{ $message }}</p>@enderror
                        @error('evidence.*')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="mt-6 rounded-2xl border border-teal-200 bg-teal-50 p-4 text-sm leading-6 text-teal-950"><strong>Setelah dikirim:</strong> Anda akan menerima kode laporan dan PIN untuk melihat progres. Simpan keduanya karena PIN hanya ditampilkan satu kali.</div>
                    <label class="mt-5 flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 transition hover:border-blue-200"><input class="mt-0.5 size-4 rounded border-slate-300 text-blue-700 focus:ring-blue-600" type="checkbox" id="confirmation" name="good_faith" value="1" @checked(old('good_faith')) required><span class="text-sm leading-6 text-slate-600">Informasi ini saya sampaikan dengan itikad baik berdasarkan hal yang saya ketahui. <span class="text-red-600">*</span></span></label>
                    @error('good_faith')<p class="form-error mt-2">{{ $message }}</p>@enderror
                </section>

                <div class="sticky bottom-0 z-20 flex items-center justify-between gap-3 rounded-b-3xl border-t border-slate-200 bg-white/95 px-5 py-4 shadow-[0_-12px_30px_-24px_rgb(15_23_42/0.45)] backdrop-blur sm:px-8">
                    <button type="button" id="previous-step" class="button-secondary hidden flex-1 sm:flex-none">Kembali</button>
                    <button type="button" id="next-step" class="button-primary ml-auto flex-1 sm:flex-none">Lanjut ke lokasi <span aria-hidden="true">→</span></button>
                    <button type="submit" id="submit-report" class="button-primary ml-auto hidden flex-1 sm:flex-none">Kirim sekarang</button>
                </div>
            </form>
        </div>
    </section>

</x-layouts.public>
