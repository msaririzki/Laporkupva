<x-layouts.public title="Cek status laporan">
    <section class="min-h-[70vh] bg-slate-50 py-14 sm:py-20">
        <div class="public-container max-w-xl">
            <div class="text-center">
                <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-blue-100 text-blue-700"><svg class="size-7" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.078 3.079a.75.75 0 1 1-1.06 1.06l-3.079-3.078A7 7 0 0 1 2 9Z" clip-rule="evenodd"/></svg></span>
                <p class="eyebrow mt-6">Pelacakan privat</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-navy-950">Cek status laporan</h1>
                <p class="mt-3 text-sm leading-6 text-slate-500">Masukkan kode dan PIN yang Anda simpan setelah mengirim laporan.</p>
            </div>
            <form action="{{ route('reports.track.show') }}" method="POST" class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-8">
                @csrf
                <div><label class="form-label" for="tracking_code">Kode laporan</label><input class="form-control font-mono uppercase tracking-wider @error('tracking_code') is-invalid @enderror" id="tracking_code" name="tracking_code" value="{{ old('tracking_code') }}" placeholder="LKP-XXXX-XXXX" autocomplete="off" required>@error('tracking_code')<p class="form-error">{{ $message }}</p>@enderror</div>
                <div class="mt-5"><label class="form-label" for="tracking_pin">PIN 6 digit</label><input class="form-control font-mono tracking-[.3em] @error('tracking_pin') is-invalid @enderror" id="tracking_pin" name="tracking_pin" type="password" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="off" placeholder="••••••" required>@error('tracking_pin')<p class="form-error">{{ $message }}</p>@enderror</div>
                <button class="button-primary mt-7 w-full" type="submit">Lihat progres laporan</button>
                <p class="mt-5 text-center text-xs leading-5 text-slate-400">Demi keamanan, petugas tidak dapat melihat atau mengirim ulang PIN Anda.</p>
            </form>
        </div>
    </section>
</x-layouts.public>
