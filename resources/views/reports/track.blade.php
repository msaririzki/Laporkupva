<x-layouts.public title="Cek status laporan">
    <section class="min-h-[75vh] bg-[#F4F7FB] py-8 sm:py-14 lg:py-18">
        <div class="public-container max-w-md lg:max-w-lg">
            <div class="text-center">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2563EB]">
                    <span class="size-1.5 rounded-full bg-[#2563EB]"></span>
                    <span>Pelacakan Privat</span>
                </span>
                <h1 class="mt-2 text-2xl sm:text-3xl font-bold tracking-tight text-[#0F172A]">Cek status laporan</h1>
                <p class="mt-1.5 text-xs sm:text-sm text-[#64748B] leading-relaxed">Masukkan kode laporan dan PIN Anda untuk melihat perkembangan tindak lanjut.</p>
            </div>

            <form action="{{ route('reports.track.show') }}" method="POST" class="mt-8 rounded-2xl border border-[#E2E8F0] bg-white p-6 sm:p-8 lg:p-9 shadow-[0_8px_30px_rgba(15,23,42,0.05)]">
                @csrf

                <div>
                    <label class="form-label text-xs sm:text-sm font-semibold text-[#0F172A]" for="tracking_code">Kode laporan <span class="text-[#DC2626]">*</span></label>
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
                    <label class="form-label text-xs sm:text-sm font-semibold text-[#0F172A]" for="tracking_pin">PIN 6 digit <span class="text-[#DC2626]">*</span></label>
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

                <div class="mt-5 rounded-xl bg-[#EEF4FF] border border-blue-200/60 p-3.5 text-center text-xs leading-relaxed text-[#0F172A]">
                    Demi keamanan privasi, petugas tidak dapat melihat atau menerbitkan ulang PIN Anda jika hilang.
                </div>
            </form>
        </div>
    </section>
</x-layouts.public>
