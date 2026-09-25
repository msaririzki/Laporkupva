<x-layouts.public title="Cek status laporan">
    <section class="min-h-[75vh] bg-[#F7F9FC] py-8 sm:py-14">
        <div class="public-container max-w-md">
            <div class="text-center">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2563EB]">
                    <span class="size-1.5 rounded-full bg-[#2563EB]"></span>
                    <span>Pelacakan Privat</span>
                </span>
                <h1 class="mt-1.5 text-xl sm:text-2xl font-bold tracking-tight text-[#0B2342]">Cek status laporan</h1>
                <p class="mt-1 text-xs sm:text-sm text-[#64748B] leading-relaxed">Masukkan kode laporan dan PIN Anda untuk melihat perkembangan tindak lanjut.</p>
            </div>

            <form action="{{ route('reports.track.show') }}" method="POST" class="mt-6 rounded-xl sm:rounded-2xl border border-slate-200/80 bg-white p-5 sm:p-7 shadow-xs">
                @csrf

                <div>
                    <label class="form-label text-xs sm:text-sm" for="tracking_code">Kode laporan <span>*</span></label>
                    <input
                        class="form-control font-mono uppercase tracking-wider text-center text-sm sm:text-base @error('tracking_code') is-invalid @enderror"
                        id="tracking_code"
                        name="tracking_code"
                        value="{{ old('tracking_code', request('code')) }}"
                        placeholder="LKP-XXXX-XXXX"
                        autocomplete="off"
                        required
                    >
                    <p class="mt-1 text-[11px] sm:text-xs text-[#64748B]">Format 8 karakter diawali LKP, contoh: LKP-AB12-CD34.</p>
                    @error('tracking_code')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="mt-4">
                    <label class="form-label text-xs sm:text-sm" for="tracking_pin">PIN 6 digit <span>*</span></label>
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
                    <p class="mt-1 text-[11px] sm:text-xs text-[#64748B]">6 digit angka rahasia yang diterbitkan saat pengiriman.</p>
                    @error('tracking_pin')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <button class="button-primary mt-6 w-full text-sm sm:text-base font-semibold h-12" type="submit">
                    <span>Cek status laporan</span>
                    <svg class="size-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69L10.22 5.03a.75.75 0 0 1 1.06-1.06l5.5 5.5a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 1 1-1.06-1.06l4.22-4.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                    </svg>
                </button>

                <div class="mt-5 rounded-lg bg-blue-50/60 border border-blue-100 p-3 text-center text-xs leading-relaxed text-[#0B2342]">
                    Demi keamanan privasi, petugas tidak dapat melihat atau menerbitkan ulang PIN Anda jika hilang.
                </div>
            </form>
        </div>
    </section>
</x-layouts.public>
