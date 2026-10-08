<section class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs" aria-label="Peta lokasi Money Changer" data-public-kupva-map>
    <div class="flex flex-col gap-3 border-b border-slate-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-sm font-bold text-slate-900">Jelajahi Money Changer di NTB</h2>
            <p class="mt-1 text-xs text-slate-500">Klik penanda untuk melihat nama dan alamat usaha.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" data-kupva-locate disabled class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-blue-600 px-3 text-xs font-bold text-white transition hover:bg-blue-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-wait disabled:opacity-60">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="7"/><circle cx="12" cy="12" r="2"/><path d="M12 2v3m0 14v3M2 12h3m14 0h3"/></svg>
                <span>Gunakan lokasi saya</span>
            </button>
            <button type="button" data-kupva-reset-map disabled class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-xs font-bold text-slate-600 transition hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-wait disabled:opacity-60">Lihat semua titik</button>
        </div>
    </div>
    <p class="border-b border-slate-100 bg-blue-50/50 px-4 py-2.5 text-xs leading-relaxed text-slate-600" role="status" aria-live="polite" data-kupva-map-status>Menyiapkan peta lokasi…</p>
    <div class="grid lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div id="public-kupva-map" class="h-[400px] bg-slate-100 sm:h-[520px]" role="region" aria-label="Peta Money Changer di Nusa Tenggara Barat" tabindex="0" data-kupva-map-canvas></div>
        <aside class="border-t border-slate-200 lg:border-l lg:border-t-0" aria-label="Hasil pencarian Money Changer">
            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                <h3 class="text-xs font-bold uppercase tracking-wide text-slate-600">Hasil pencarian</h3>
                <span class="rounded-full bg-blue-50 px-2 py-0.5 text-xs font-bold text-blue-700">{{ $mapKupvas->count() }}</span>
            </div>
            <ul class="max-h-80 divide-y divide-slate-100 overflow-y-auto lg:max-h-[472px]" data-kupva-results>
                @forelse ($mapKupvas as $kupva)
                    <li data-kupva-result="{{ $kupva['id'] }}" class="px-4 py-3">
                        <h4 class="text-sm font-bold leading-snug text-slate-900">{{ $kupva['name'] }}</h4>
                        <p class="mt-1.5 text-xs leading-relaxed text-slate-500">{{ $kupva['address'] }}</p>
                        @if ($kupva['approximate'])
                            <p class="mt-1.5 text-[11px] font-semibold text-amber-700">Perkiraan lokasi dari alamat</p>
                        @endif
                        <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                            <button type="button" data-kupva-focus="{{ $kupva['id'] }}" disabled class="min-h-8 text-xs font-bold text-blue-700 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-default disabled:text-slate-400 disabled:no-underline">Lihat di peta</button>
                            <span class="text-xs text-slate-500" data-kupva-distance></span>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-8 text-center">
                        <p class="text-sm font-bold text-slate-900">Data tidak ditemukan</p>
                        <p class="mt-2 text-xs leading-relaxed text-slate-500">Coba gunakan nama atau wilayah lain.</p>
                    </li>
                @endforelse
            </ul>
        </aside>
    </div>
    <div class="border-t border-slate-200 px-4 py-3 text-xs leading-relaxed text-slate-500">
        Lokasi Anda digunakan untuk mencari titik terdekat pada peta ini.
        <noscript>Aktifkan JavaScript untuk melihat peta, atau pilih tampilan Kartu.</noscript>
    </div>
    <script type="application/json" data-kupva-map-data>{!! json_encode(['kupvas' => $mapKupvas->values(), 'focusResults' => filled($search) || $selectedRegency !== null, 'mapboxPublicToken' => config('services.mapbox.public_token', '')], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) !!}</script>
</section>
