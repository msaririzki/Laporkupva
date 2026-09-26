<x-layouts.public title="Cek status laporan">
    <section class="bg-[#F7F9FC] py-10 sm:py-14 lg:py-16">
        <div class="public-container max-w-4xl">
            <div class="max-w-2xl">
                <p class="eyebrow">Akses laporan</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-[#0B2342] sm:text-4xl">Cek status laporan</h1>
                <p class="mt-3 text-sm leading-7 text-slate-600 sm:text-base">Gunakan gambar QR untuk akses cepat atau masukkan kode laporan dan PIN secara manual.</p>
            </div>

            <form id="tracking-form" action="{{ route('reports.track.show') }}" method="POST" class="mt-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                @csrf
                <input id="access_token" name="access_token" type="hidden">

                <div class="grid lg:grid-cols-2">
                    <div class="p-5 sm:p-7 lg:p-8">
                        <div class="flex items-center gap-3">
                            <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-blue-50 text-[#2563EB]">
                                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M3.25 2A1.25 1.25 0 0 0 2 3.25v4.5A1.25 1.25 0 0 0 3.25 9h4.5A1.25 1.25 0 0 0 9 7.75v-4.5A1.25 1.25 0 0 0 7.75 2h-4.5ZM4 4h3v3H4V4Zm8.25-2A1.25 1.25 0 0 0 11 3.25v4.5A1.25 1.25 0 0 0 12.25 9h4.5A1.25 1.25 0 0 0 18 7.75v-4.5A1.25 1.25 0 0 0 16.75 2h-4.5ZM13 4h3v3h-3V4ZM3.25 11A1.25 1.25 0 0 0 2 12.25v4.5A1.25 1.25 0 0 0 3.25 18h4.5A1.25 1.25 0 0 0 9 16.75v-4.5A1.25 1.25 0 0 0 7.75 11h-4.5ZM4 13h3v3H4v-3Zm7-2h2v2h-2v-2Zm3 0h1.5v1.5H14V11Zm2.5 0H18v3h-1.5v-3ZM11 14h3v1.5h-1.5V18H11v-4Zm4.5 1.5H18V18h-2.5v-2.5Z"/>
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-base font-bold text-[#0B2342]">Buka dengan QR</h2>
                                <p class="mt-0.5 text-xs leading-5 text-slate-500">Pilih gambar akses yang disimpan setelah melapor.</p>
                            </div>
                        </div>

                        <label class="mt-6 flex min-h-36 cursor-pointer flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-6 text-center transition hover:border-[#2563EB] hover:bg-blue-50/50" for="qr_access_image">
                            <svg class="size-6 text-[#2563EB]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v6.69L7.03 7.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 1 0-1.06-1.06l-2.22 2.22V2.75Z"/><path d="M3.5 10.75a.75.75 0 0 0-1.5 0v3.5A2.75 2.75 0 0 0 4.75 17h10.5A2.75 2.75 0 0 0 18 14.25v-3.5a.75.75 0 0 0-1.5 0v3.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-3.5Z"/>
                            </svg>
                            <span class="mt-3 text-sm font-semibold text-[#0B2342]">Pilih gambar QR</span>
                            <span class="mt-1 text-xs text-slate-500">PNG, JPG, atau WebP · maksimal 8 MB</span>
                        </label>
                        <input class="sr-only" id="qr_access_image" type="file" accept="image/png,image/jpeg,image/webp" data-qr-upload>
                        <p class="mt-3 text-xs leading-5 text-slate-500">Gambar diproses di perangkat Anda dan tidak dikirim ke server.</p>
                        <p class="mt-3 hidden rounded-lg px-3 py-2 text-center text-xs font-semibold" aria-live="polite" data-qr-status></p>
                    </div>

                    <div class="border-t border-slate-200 p-5 sm:p-7 lg:border-l lg:border-t-0 lg:p-8">
                        <h2 class="text-base font-bold text-[#0B2342]">Masukkan akses manual</h2>
                        <p class="mt-1 text-xs leading-5 text-slate-500">Gunakan kode dan PIN yang diterima setelah laporan dikirim.</p>

                        <div class="mt-6">
                            <label class="form-label" for="tracking_code">Kode laporan <span>*</span></label>
                            <input
                                class="form-control font-mono uppercase tracking-wider @error('tracking_code') is-invalid @enderror"
                                id="tracking_code"
                                name="tracking_code"
                                value="{{ old('tracking_code', request('code')) }}"
                                placeholder="LKP-XXXX-XXXX"
                                autocomplete="off"
                                required
                            >
                            @error('tracking_code')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="mt-5">
                            <label class="form-label" for="tracking_pin">PIN 6 digit <span>*</span></label>
                            <input
                                class="form-control font-mono tracking-[0.28em] @error('tracking_pin') is-invalid @enderror"
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
                            @error('tracking_pin')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <button class="button-primary mt-6 w-full" type="submit">Cek status laporan</button>
                    </div>
                </div>

                <div class="flex items-start gap-2.5 border-t border-slate-200 bg-slate-50 px-5 py-4 text-xs leading-5 text-slate-600 sm:px-7 lg:px-8">
                    <svg class="mt-0.5 size-4 shrink-0 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 1.75A4.25 4.25 0 0 0 5.75 6v1.1A2.75 2.75 0 0 0 3.5 9.8v5.7a2.75 2.75 0 0 0 2.75 2.75h7.5a2.75 2.75 0 0 0 2.75-2.75V9.8a2.75 2.75 0 0 0-2.25-2.7V6A4.25 4.25 0 0 0 10 1.75ZM7.25 6a2.75 2.75 0 1 1 5.5 0v1.05h-5.5V6Z" clip-rule="evenodd"/>
                    </svg>
                    <p>PIN tidak dapat diterbitkan ulang. Simpan gambar QR, kode laporan, dan PIN di tempat yang aman.</p>
                </div>
            </form>
        </div>
    </section>
</x-layouts.public>
