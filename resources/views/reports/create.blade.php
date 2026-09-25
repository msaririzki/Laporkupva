<x-layouts.public title="Buat laporan anonim">
    <section class="bg-navy-950 py-12 text-white sm:py-16">
        <div class="public-container max-w-4xl">
            <div class="eyebrow-dark"><span class="size-2 rounded-full bg-teal-300"></span>Laporan anonim</div>
            <h1 class="mt-5 text-3xl font-extrabold tracking-tight sm:text-4xl">Sampaikan informasi yang Anda ketahui</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-100/75 sm:text-base">Kami tidak meminta data diri Anda. Isi informasi kejadian sejelas mungkin agar petugas dapat menindaklanjuti.</p>
        </div>
    </section>

    <section class="py-10 sm:py-14">
        <div class="public-container max-w-4xl">
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                    <p class="font-bold">Beberapa bagian perlu diperiksa kembali.</p>
                    <p class="mt-1">Kami menandai kolom yang belum lengkap atau belum sesuai.</p>
                </div>
            @endif

            <ol class="mb-7 grid grid-cols-3 gap-2" aria-label="Tahapan formulir">
                @foreach ([['Kejadian', 'Ceritakan informasi'], ['Lokasi', 'Tentukan titik'], ['Bukti', 'Periksa & kirim']] as $index => [$label, $caption])
                    <li class="form-progress {{ $index === 0 ? 'is-active' : '' }}" data-progress="{{ $index + 1 }}"><span class="form-progress-number">{{ $index + 1 }}</span><span class="hidden sm:block"><strong>{{ $label }}</strong><small>{{ $caption }}</small></span></li>
                @endforeach
            </ol>

            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" id="report-form" class="rounded-3xl border border-slate-200 bg-white shadow-soft" novalidate>
                @csrf

                <section class="form-step p-6 sm:p-9" data-step="1">
                    <div class="form-heading"><span>1</span><div><h2>Informasi kejadian</h2><p>Ceritakan apa yang Anda lihat atau alami.</p></div></div>
                    <div class="mt-8 grid gap-6 sm:grid-cols-2">
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
                            <label class="form-label" for="business_name">Nama tempat atau usaha <span class="optional">Opsional</span></label>
                            <input class="form-control @error('business_name') is-invalid @enderror" id="business_name" name="business_name" value="{{ old('business_name') }}" maxlength="255" placeholder="Contoh: Money Changer XYZ">
                            @error('business_name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label" for="incident_date">Tanggal kejadian <span>*</span></label>
                            <input class="form-control @error('incident_date') is-invalid @enderror" id="incident_date" name="incident_date" type="date" value="{{ old('incident_date') }}" max="{{ now()->toDateString() }}" required>
                            @error('incident_date')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label" for="incident_time">Perkiraan waktu <span class="optional">Opsional</span></label>
                            <input class="form-control @error('incident_time') is-invalid @enderror" id="incident_time" name="incident_time" type="time" value="{{ old('incident_time') }}">
                            @error('incident_time')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label" for="description">Kronologi kejadian <span>*</span></label>
                            <textarea class="form-control min-h-36 @error('description') is-invalid @enderror" id="description" name="description" minlength="20" maxlength="5000" placeholder="Jelaskan ciri tempat, aktivitas yang terjadi, dan informasi penting lainnya..." required>{{ old('description') }}</textarea>
                            <div class="mt-2 flex justify-between gap-3 text-xs text-slate-400"><span>Minimal 20 karakter. Jangan tulis identitas pribadi Anda.</span><span id="description-count">0/5000</span></div>
                            @error('description')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <label class="sm:col-span-2 flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <input class="mt-0.5 size-4 rounded border-slate-300 text-blue-700 focus:ring-blue-600" type="checkbox" name="is_ongoing" value="1" @checked(old('is_ongoing'))>
                            <span><strong class="block text-sm text-slate-800">Kegiatan masih berlangsung</strong><span class="mt-1 block text-xs leading-5 text-slate-500">Centang jika aktivitas tersebut masih terlihat atau rutin terjadi.</span></span>
                        </label>
                    </div>
                </section>

                <section class="form-step hidden p-6 sm:p-9" data-step="2">
                    <div class="form-heading"><span>2</span><div><h2>Lokasi kejadian</h2><p>Pilih lokasi tempat atau pihak yang dilaporkan, bukan lokasi tempat tinggal pelapor.</p></div></div>
                    <div class="mt-7 rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm leading-6 text-blue-900"><strong>Anda sudah tidak berada di lokasi?</strong> Cari nama wilayah atau geser penanda pada peta ke titik kejadian.</div>
                    <div class="mt-6 grid gap-4 sm:grid-cols-[1fr_auto]">
                        <div class="relative">
                            <label class="sr-only" for="location-search">Cari lokasi</label>
                            <input class="form-control pl-11" id="location-search" type="search" placeholder="Cari jalan, desa, kecamatan, atau tempat di NTB">
                            <svg class="pointer-events-none absolute left-4 top-3.5 size-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.078 3.079a.75.75 0 1 1-1.06 1.06l-3.079-3.078A7 7 0 0 1 2 9Z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="flex gap-2"><button type="button" id="search-location" class="button-secondary flex-1 sm:flex-none">Cari</button><button type="button" id="use-location" class="button-primary flex-1 sm:flex-none" title="Gunakan lokasi perangkat"><svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.75a.75.75 0 0 1 .75.75v1.05a6.5 6.5 0 0 1 5.7 5.7h1.05a.75.75 0 0 1 0 1.5h-1.05a6.5 6.5 0 0 1-5.7 5.7v1.05a.75.75 0 0 1-1.5 0v-1.05a6.5 6.5 0 0 1-5.7-5.7H2.5a.75.75 0 0 1 0-1.5h1.05a6.5 6.5 0 0 1 5.7-5.7V2.5a.75.75 0 0 1 .75-.75ZM5 10a5 5 0 1 0 10 0 5 5 0 0 0-10 0Zm5-2.25a2.25 2.25 0 1 1 0 4.5 2.25 2.25 0 0 1 0-4.5Z"/></svg>Lokasi saya</button></div>
                    </div>
                    <p id="map-message" class="mt-3 hidden text-sm" role="status"></p>
                    <div
                        id="report-map"
                        class="mt-4 h-[390px] overflow-hidden rounded-2xl border border-slate-200 bg-slate-100"
                        data-mapbox-token="{{ config('services.mapbox.public_token') }}"
                        aria-label="Peta pemilihan lokasi"
                    ></div>
                    <p class="mt-3 text-xs leading-5 text-slate-500">Klik peta atau geser penanda untuk menyesuaikan lokasi. Pastikan titik berada di wilayah Nusa Tenggara Barat.</p>

                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}" required>
                    <input type="hidden" id="location_accuracy" name="location_accuracy" value="{{ old('location_accuracy') }}">

                    <div class="mt-7 grid gap-5 sm:grid-cols-2">
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
                        <div><label class="form-label" for="district">Kecamatan <span class="optional">Opsional</span></label><input class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district') }}" maxlength="120" placeholder="Nama kecamatan">@error('district')<p class="form-error">{{ $message }}</p>@enderror</div>
                        <div><label class="form-label" for="village">Desa/kelurahan <span class="optional">Opsional</span></label><input class="form-control @error('village') is-invalid @enderror" id="village" name="village" value="{{ old('village') }}" maxlength="120" placeholder="Nama desa atau kelurahan">@error('village')<p class="form-error">{{ $message }}</p>@enderror</div>
                        <div><label class="form-label" for="address">Petunjuk alamat <span class="optional">Opsional</span></label><input class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" maxlength="1000" placeholder="Jalan, patokan, atau nomor bangunan">@error('address')<p class="form-error">{{ $message }}</p>@enderror</div>
                        @if ($errors->has('latitude') || $errors->has('longitude'))<p class="form-error sm:col-span-2">Silakan pilih titik lokasi kejadian pada peta.</p>@endif
                    </div>
                </section>

                <section class="form-step hidden p-6 sm:p-9" data-step="3">
                    <div class="form-heading"><span>3</span><div><h2>Bukti dan konfirmasi</h2><p>Lampirkan bukti jika ada, lalu pastikan informasi sudah benar.</p></div></div>
                    <div class="mt-8">
                        <label class="form-label" for="evidence">Foto atau dokumen pendukung <span class="optional">Opsional</span></label>
                        <label class="upload-zone" for="evidence"><span class="grid size-12 place-items-center rounded-xl bg-blue-50 text-blue-700"><svg class="size-6" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 2.75a.75.75 0 0 0-1.5 0v6.69L7.03 7.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 1 0-1.06-1.06l-2.22 2.22V2.75Z"/><path d="M3.5 10.75a.75.75 0 0 0-1.5 0v3.5A2.75 2.75 0 0 0 4.75 17h10.5A2.75 2.75 0 0 0 18 14.25v-3.5a.75.75 0 0 0-1.5 0v3.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-3.5Z"/></svg></span><span><strong>Pilih foto atau dokumen</strong><small>JPG, PNG, WebP, atau PDF · Maks. 10 MB per berkas · Maks. 5 berkas</small></span></label>
                        <input class="sr-only" id="evidence" name="evidence[]" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" multiple>
                        <div id="file-list" class="mt-3 grid gap-2"></div>
                        @error('evidence')<p class="form-error">{{ $message }}</p>@enderror
                        @error('evidence.*')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-5"><h3 class="font-extrabold text-navy-950">Sebelum mengirim</h3><ul class="mt-3 grid gap-2 text-sm leading-6 text-slate-600"><li class="flex gap-2"><span class="text-teal-600">✓</span>Pastikan lokasi yang dipilih adalah lokasi pihak atau kegiatan yang dilaporkan.</li><li class="flex gap-2"><span class="text-teal-600">✓</span>Jangan mencantumkan identitas pribadi Anda di kronologi atau lampiran.</li><li class="flex gap-2"><span class="text-teal-600">✓</span>Setelah dikirim, simpan kode laporan dan PIN yang hanya ditampilkan satu kali.</li></ul></div>
                    <label class="mt-6 flex cursor-pointer items-start gap-3"><input class="mt-0.5 size-4 rounded border-slate-300 text-blue-700 focus:ring-blue-600" type="checkbox" id="confirmation" name="good_faith" value="1" @checked(old('good_faith')) required><span class="text-sm leading-6 text-slate-600">Saya menyatakan bahwa informasi ini disampaikan dengan itikad baik berdasarkan hal yang saya ketahui. <span class="text-red-600">*</span></span></label>
                    @error('good_faith')<p class="form-error mt-2">{{ $message }}</p>@enderror
                </section>

                <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-6 py-5 sm:px-9">
                    <button type="button" id="previous-step" class="button-secondary invisible">Kembali</button>
                    <span class="hidden text-xs text-slate-400 sm:block"><span class="text-red-600">*</span> Wajib diisi</span>
                    <button type="button" id="next-step" class="button-primary">Lanjutkan <span aria-hidden="true">→</span></button>
                    <button type="submit" id="submit-report" class="button-primary hidden">Kirim laporan dengan aman</button>
                </div>
            </form>
        </div>
    </section>

</x-layouts.public>
