<x-layouts.public title="Money Changer berizin di NTB">
    <section class="border-b border-slate-200 bg-white py-7 sm:py-9">
        <div class="public-container max-w-[1180px] px-4 sm:px-6 lg:px-10">
            <div class="max-w-3xl">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2563EB]">Data resmi wilayah NTB</p>
                <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-[#0F172A] sm:text-3xl lg:text-[34px]">
                    Daftar Money Changer Berizin
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-[#64748B] sm:text-[15px]">
                    Temukan Money Changer berizin Bank Indonesia di Nusa Tenggara Barat, lalu cocokkan nama usaha dan nomor izinnya sebelum bertransaksi.
                </p>
                <p class="mt-2 text-xs text-slate-500">KUPVA BB adalah istilah resmi untuk usaha Money Changer bukan bank.</p>
            </div>
        </div>
    </section>

    <section class="bg-[#F4F7FB] py-6 sm:py-8">
        <div class="public-container max-w-[1180px] px-4 sm:px-6 lg:px-10">
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs" aria-labelledby="official-kupva-title">
                <div class="flex flex-col gap-4 border-b border-slate-200 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5 lg:px-6">
                    <div class="flex items-start gap-3">
                        <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-blue-50 text-[#2563EB] ring-1 ring-blue-100" aria-hidden="true">
                            <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                        </span>
                        <div>
                            <h2 id="official-kupva-title" class="text-base font-extrabold tracking-tight text-[#0F172A] sm:text-lg">Kenali Money Changer Resmi</h2>
                            <p class="mt-0.5 text-xs leading-relaxed text-[#64748B] sm:text-sm">Periksa empat hal berikut sebelum menukar uang.</p>
                        </div>
                    </div>
                    <div class="flex shrink-0 items-center gap-3 text-xs font-bold">
                        <a href="#daftar-kupva" class="text-[#2563EB] transition hover:text-[#1D4ED8]">Lihat daftar ↓</a>
                        <span class="h-4 w-px bg-slate-200" aria-hidden="true"></span>
                        <a href="https://www.bi.go.id/id/edukasi/Pages/Penjualan-Valuta-Asing.aspx" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-slate-600 transition hover:text-[#2563EB]">
                            Sumber BI
                            <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </div>

                <div class="grid divide-y divide-slate-200 sm:grid-cols-2 sm:divide-x sm:[&>*:nth-child(3)]:border-l-0 lg:grid-cols-4 lg:divide-y-0 lg:[&>*:nth-child(3)]:border-l">
                    @foreach ([
                        ['number' => '01', 'title' => 'Logo resmi', 'description' => 'Terdapat logo KUPVA Berizin dari Bank Indonesia.'],
                        ['number' => '02', 'title' => 'Izin terlihat', 'description' => 'Sertifikat izin dipasang di lokasi usaha.'],
                        ['number' => '03', 'title' => 'Identitas jelas', 'description' => 'Nama usaha dan papan “Authorized Money Changer” terlihat jelas.'],
                        ['number' => '04', 'title' => 'Kurs transparan', 'description' => 'Nilai tukar disampaikan sebelum transaksi.'],
                    ] as $indicator)
                        <article class="flex gap-3 px-4 py-4 sm:min-h-28 sm:px-5 lg:min-h-32 lg:flex-col lg:gap-2.5 lg:px-5">
                            <span class="grid size-7 shrink-0 place-items-center rounded-lg bg-slate-100 text-[10px] font-extrabold text-[#2563EB]">{{ $indicator['number'] }}</span>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#0F172A]">{{ $indicator['title'] }}</h3>
                                <p class="mt-1 text-xs leading-relaxed text-[#64748B]">{{ $indicator['description'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <form id="daftar-kupva" action="{{ route('kupvas.index') }}" method="GET" class="mt-4 scroll-mt-24 rounded-2xl border border-slate-200 bg-white p-3 shadow-xs sm:p-4" role="search" data-kupva-filters>
                <div class="grid gap-2.5 md:grid-cols-[minmax(0,1fr)_minmax(14rem,0.42fr)_auto] md:items-end">
                    <div>
                        <label for="kupva-search" class="form-label">Cari Money Changer</label>
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 3.473 9.767l3.63 3.63a.75.75 0 1 0 1.06-1.06l-3.63-3.63A5.5 5.5 0 0 0 9 3.5ZM5 9a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                            </svg>
                            <input
                                id="kupva-search"
                                name="q"
                                type="search"
                                value="{{ $search }}"
                                maxlength="100"
                                class="form-control pl-10"
                                placeholder="Nama usaha, nomor izin, atau alamat"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="kupva-regency" class="form-label">Kabupaten/kota</label>
                        <select id="kupva-regency" name="regency" class="form-control" data-custom-select data-auto-submit>
                            <option value="">Semua wilayah</option>
                            @foreach ($regencies as $regency)
                                <option value="{{ $regency->value }}" @selected($selectedRegency === $regency->value)>{{ $regency->getLabel() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-stretch gap-2 md:self-end" data-kupva-filter-actions>
                        <button type="submit" class="button-primary h-11 flex-1 justify-center rounded-xl px-5 text-sm font-bold sm:h-12 md:flex-none">
                            <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 3.473 9.767l3.63 3.63a.75.75 0 1 0 1.06-1.06l-3.63-3.63A5.5 5.5 0 0 0 9 3.5ZM5 9a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                            </svg>
                            <span>Cari nama</span>
                        </button>
                        @if (filled($search) || $selectedRegency !== null)
                            <a href="{{ route('kupvas.index') }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-600 transition hover:border-blue-300 hover:text-blue-700 sm:h-12">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="mt-5 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm font-bold text-[#0B2342]">
                    {{ number_format($kupvas->total(), 0, ',', '.') }} Money Changer ditemukan
                </p>
                <p class="text-xs text-[#64748B]">Hanya menampilkan izin aktif dan usaha yang masih beroperasi.</p>
            </div>

            @if ($kupvas->isEmpty())
                <div class="mt-4 rounded-2xl border border-dashed border-slate-300 bg-white px-5 py-12 text-center">
                    <div class="mx-auto grid size-12 place-items-center rounded-2xl bg-slate-100 text-slate-500">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15v18h-15V3Zm3.75 4.5h.008v.008H8.25V7.5Zm0 4.5h.008v.008H8.25V12Zm0 4.5h.008v.008H8.25V16.5Zm4.5-9h3m-3 4.5h3m-3 4.5h3" />
                        </svg>
                    </div>
                    <h2 class="mt-3 text-base font-bold text-[#0F172A]">Data tidak ditemukan</h2>
                    <p class="mt-1 text-sm text-[#64748B]">Coba gunakan nama atau wilayah lain.</p>
                </div>
            @else
                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($kupvas as $kupva)
                        <article class="group flex min-h-full flex-col rounded-2xl border border-slate-200 bg-white p-4 shadow-xs transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-[0_18px_38px_-28px_rgba(37,99,235,0.45)] sm:p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="grid size-10 shrink-0 place-items-center rounded-xl bg-blue-50 text-[#2563EB] ring-1 ring-blue-100">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15v18h-15V3Zm3.75 4.5h.008v.008H8.25V7.5Zm0 4.5h.008v.008H8.25V12Zm0 4.5h.008v.008H8.25V16.5Zm4.5-9h3m-3 4.5h3m-3 4.5h3" />
                                    </svg>
                                </div>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 ring-1 ring-emerald-200/80">
                                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                    Izin aktif
                                </span>
                            </div>

                            <h2 class="mt-3 text-sm font-extrabold leading-snug text-[#0F172A] sm:text-base">{{ $kupva->name }}</h2>
                            <div class="mt-2 rounded-xl bg-slate-50 px-3 py-2.5 ring-1 ring-slate-200/80">
                                <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Nomor izin</p>
                                <p class="mt-0.5 break-all text-xs font-bold text-[#1E3A5F] sm:text-sm">{{ $kupva->license_number }}</p>
                            </div>

                            <dl class="mt-3 grid gap-2 text-xs leading-relaxed text-[#64748B]">
                                <div class="flex items-start gap-2">
                                    <svg class="mt-0.5 size-4 shrink-0 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M9.69 18.933.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 0 0 .281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 1 0 3 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 0 0 2.273 1.765 11.842 11.842 0 0 0 .976.549l.04.018.018.008.006.003ZM10 11.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" clip-rule="evenodd" /></svg>
                                    <div>
                                        <dt class="sr-only">Lokasi</dt>
                                        <dd class="font-semibold text-slate-700">{{ $kupva->regency }}</dd>
                                        @if (filled($kupva->address) || filled($kupva->district))
                                            <dd>{{ collect([$kupva->address, $kupva->district])->filter()->join(', ') }}</dd>
                                        @endif
                                    </div>
                                </div>
                                @if ($kupva->license_expires_at !== null)
                                    <div class="flex items-center gap-2">
                                        <svg class="size-4 shrink-0 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M6.75 2a.75.75 0 0 1 .75.75V4h5V2.75a.75.75 0 0 1 1.5 0V4h.75A2.25 2.25 0 0 1 17 6.25v8.5A2.25 2.25 0 0 1 14.75 17h-9.5A2.25 2.25 0 0 1 3 14.75v-8.5A2.25 2.25 0 0 1 5.25 4H6V2.75A.75.75 0 0 1 6.75 2ZM4.5 8.5v6.25c0 .414.336.75.75.75h9.5a.75.75 0 0 0 .75-.75V8.5h-11Z" clip-rule="evenodd" /></svg>
                                        <div>
                                            <dt class="sr-only">Masa berlaku</dt>
                                            <dd>Berlaku sampai <strong class="text-slate-700">{{ $kupva->license_expires_at->translatedFormat('d F Y') }}</strong></dd>
                                        </div>
                                    </div>
                                @endif
                            </dl>

                            @if ($kupva->latitude !== null && $kupva->longitude !== null)
                                <a
                                    href="https://www.google.com/maps/search/?api=1&query={{ rawurlencode($kupva->latitude.','.$kupva->longitude) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-auto inline-flex items-center gap-1.5 pt-4 text-xs font-bold text-[#2563EB] transition group-hover:text-[#1D4ED8]"
                                >
                                    Lihat lokasi
                                    <span aria-hidden="true">↗</span>
                                </a>
                            @endif
                        </article>
                    @endforeach
                </div>

                @if ($kupvas->hasPages())
                    <div class="mt-6">
                        {{ $kupvas->links() }}
                    </div>
                @endif
            @endif

            <div class="mt-6 flex flex-col gap-3 rounded-2xl border border-blue-100 bg-blue-50/70 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
                <div>
                    <h2 class="text-sm font-bold text-[#0B2342]">Money Changer tidak tercantum dalam daftar?</h2>
                    <p class="mt-1 text-xs leading-relaxed text-[#64748B]">Laporkan dugaan Money Changer tanpa izin agar dapat diperiksa oleh petugas.</p>
                </div>
                <a href="{{ route('reports.create') }}" class="button-primary shrink-0 justify-center rounded-xl px-4 py-2.5 text-xs font-bold sm:text-sm">
                    Buat laporan
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
