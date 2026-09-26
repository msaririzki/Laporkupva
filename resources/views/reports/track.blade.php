<x-layouts.public title="Cek status laporan">
    <section class="min-h-[75vh] bg-[#F4F7FB] py-8 sm:py-14 lg:py-18">
        <div class="public-container max-w-md lg:max-w-lg">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 border border-blue-200/60 px-3 py-1 text-xs font-bold text-[#2563EB]">
                    <span>Pelacakan Privat</span>
                </div>
                <h1 class="mt-3 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#0B2342]">Cek status laporan</h1>
                <p class="mt-2 text-xs sm:text-sm text-[#64748B] leading-relaxed">Unggah QR akses untuk masuk cepat, atau gunakan kode laporan dan PIN.</p>
            </div>

            <form id="tracking-form" action="{{ route('reports.track.show') }}" method="POST" class="mt-8 rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-8 lg:p-9 shadow-xs">
                @csrf
                <input id="access_token" name="access_token" type="hidden">

                <div class="rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50/90 to-white p-4 sm:p-5">
                    <div class="flex items-start gap-3.5">
                        <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-[#0B2342] text-white shadow-sm">
                            <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M3.25 2A1.25 1.25 0 0 0 2 3.25v4.5A1.25 1.25 0 0 0 3.25 9h4.5A1.25 1.25 0 0 0 9 7.75v-4.5A1.25 1.25 0 0 0 7.75 2h-4.5ZM4 4h3v3H4V4Zm8.25-2A1.25 1.25 0 0 0 11 3.25v4.5A1.25 1.25 0 0 0 12.25 9h4.5A1.25 1.25 0 0 0 18 7.75v-4.5A1.25 1.25 0 0 0 16.75 2h-4.5ZM13 4h3v3h-3V4ZM3.25 11A1.25 1.25 0 0 0 2 12.25v4.5A1.25 1.25 0 0 0 3.25 18h4.5A1.25 1.25 0 0 0 9 16.75v-4.5A1.25 1.25 0 0 0 7.75 11h-4.5ZM4 13h3v3H4v-3Zm7-2h2v2h-2v-2Zm3 0h1.5v1.5H14V11Zm2.5 0H18v3h-1.5v-3ZM11 14h3v1.5h-1.5V18H11v-4Zm4.5 1.5H18V18h-2.5v-2.5Z"/>
                            </svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <h2 class="text-sm font-bold text-[#0B2342] sm:text-base">Masuk cepat dengan QR</h2>
                            <p class="mt-1 text-xs leading-relaxed text-[#64748B]">Pilih gambar akses yang Anda unduh setelah mengirim laporan.</p>
                        </div>
                    </div>

                    <label class="mt-4 flex min-h-12 cursor-pointer items-center justify-center gap-2 rounded-xl border border-[#2563EB]/20 bg-white px-4 py-3 text-sm font-bold text-[#2563EB] shadow-xs transition hover:border-[#2563EB]/40 hover:bg-blue-50" for="qr_access_image">
                        <svg class="size-4.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v6.69L7.03 7.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 1 0-1.06-1.06l-2.22 2.22V2.75Z"/><path d="M3.5 10.75a.75.75 0 0 0-1.5 0v3.5A2.75 2.75 0 0 0 4.75 17h10.5A2.75 2.75 0 0 0 18 14.25v-3.5a.75.75 0 0 0-1.5 0v3.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-3.5Z"/>
                        </svg>
                        <span>Unggah QR akses</span>
                    </label>
                    <input class="sr-only" id="qr_access_image" type="file" accept="image/png,image/jpeg,image/webp" data-qr-upload>
                    <p class="mt-2 text-center text-[11px] leading-relaxed text-[#64748B]">Gambar dibaca di perangkat Anda dan tidak diunggah ke server.</p>
                    <p class="mt-2 hidden rounded-lg px-3 py-2 text-center text-xs font-semibold" aria-live="polite" data-qr-status></p>
                </div>

                <div class="my-6 flex items-center gap-3" aria-hidden="true">
                    <span class="h-px flex-1 bg-slate-200"></span>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">atau isi manual</span>
                    <span class="h-px flex-1 bg-slate-200"></span>
                </div>

                <div>
                    <label class="form-label text-xs sm:text-sm font-semibold text-[#0B2342]" for="tracking_code">Kode laporan <span class="text-[#DC2626]">*</span></label>
                    <input
                        class="form-control font-mono uppercase tracking-wider text-center text-sm sm:text-base @error('tracking_code') is-invalid @enderror"
                        id="tracking_code"
                        name="tracking_code"
                        value="{{ old('tracking_code', request('code')) }}"
                        placeholder="LKP-XXXX-XXXX"
                        autocomplete="off"
                        required
                    >
                    <p class="mt-1.5 text-xs text-[#64748B]">Format 8 karakter diawali LKP, contoh: LKP-AB12-CD34.</p>
                    @error('tracking_code')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="mt-5">
                    <label class="form-label text-xs sm:text-sm font-semibold text-[#0B2342]" for="tracking_pin">PIN 6 digit <span class="text-[#DC2626]">*</span></label>
                    <input
                        class="form-control font-mono tracking-[0.3em] text-center text-sm sm:text-base @error('tracking_pin') is-invalid @enderror"
                        id="tracking_pin"
                        name="tracking_pin"
                        type="password"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        maxlength="6"
                        autocomplete="off"
                        placeholder="••••••"
                        required
                    >
                    <p class="mt-1.5 text-xs text-[#64748B]">6 digit angka rahasia yang diterbitkan saat pengiriman.</p>
                    @error('tracking_pin')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <button class="button-primary mt-6 w-full text-sm sm:text-base font-semibold h-11 sm:h-12 rounded-xl bg-[#2563EB] text-white hover:bg-[#1D4ED8] transition-colors shadow-2xs" type="submit">
                    <span>Cek status</span>
                </button>

                <div class="mt-5 rounded-2xl bg-blue-50/60 border border-blue-100 p-4 text-center text-xs leading-relaxed text-[#0B2342]">
                    Demi keamanan privasi, petugas tidak dapat melihat atau menerbitkan ulang PIN Anda jika hilang.
                </div>
            </form>
        </div>
    </section>
</x-layouts.public>
