<x-filament-widgets::widget class="tambora-welcome-widget">
    <section class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-800 px-6 py-7 text-white shadow-2xl shadow-blue-950/20 sm:px-8 sm:py-9">
        <div class="absolute -right-20 -top-28 -z-10 size-80 rounded-full bg-cyan-400/20 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-32 left-1/3 -z-10 size-72 rounded-full bg-blue-400/20 blur-3xl" aria-hidden="true"></div>
        <div class="absolute inset-y-0 right-0 -z-10 hidden w-2/5 opacity-30 lg:block" aria-hidden="true">
            <svg class="size-full" viewBox="0 0 520 260" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M80 260C127 181 171 150 231 136C312 117 348 75 384 0" stroke="white" stroke-opacity=".16" stroke-width="42" />
                <path d="M180 260C220 201 272 178 327 164C407 143 446 98 489 22" stroke="white" stroke-opacity=".1" stroke-width="24" />
            </svg>
        </div>

        <div class="relative flex flex-col gap-7 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-3xl">
                <div class="mb-5 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-semibold tracking-wide text-blue-50 backdrop-blur-sm">
                        <span class="relative flex size-2">
                            <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-300 opacity-70 motion-reduce:hidden"></span>
                            <span class="relative inline-flex size-2 rounded-full bg-emerald-300"></span>
                        </span>
                        Pusat kendali TAMBORA
                    </span>
                    <span class="text-sm font-medium text-blue-100">{{ $currentDate }}</span>
                </div>

                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-200">Bank Indonesia · Nusa Tenggara Barat</p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Selamat datang, {{ $adminName }}</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-100 sm:text-base">
                    Satu ruang kerja untuk memantau laporan masyarakat, menindaklanjuti proses pengawasan, dan menjaga data KUPVA tetap akurat.
                </p>
            </div>

            <div class="flex flex-wrap gap-3 xl:max-w-sm xl:justify-end">
                <a href="{{ $reportsUrl }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-blue-950 shadow-lg shadow-blue-950/20 transition hover:-translate-y-0.5 hover:bg-blue-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                    <x-filament::icon icon="heroicon-m-clipboard-document-list" class="size-5 text-blue-700" />
                    Buka laporan
                </a>

                <a href="{{ $kupvasUrl }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-bold text-white backdrop-blur-sm transition hover:-translate-y-0.5 hover:bg-white/15 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                    <x-filament::icon icon="heroicon-m-building-office-2" class="size-5 text-blue-100" />
                    Data KUPVA
                </a>

                @if ($canManageAdmins)
                    <a href="{{ $usersUrl }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-bold text-white backdrop-blur-sm transition hover:-translate-y-0.5 hover:bg-white/15 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                        <x-filament::icon icon="heroicon-m-user-group" class="size-5 text-blue-100" />
                        Kelola admin
                    </a>
                @endif
            </div>
        </div>
    </section>
</x-filament-widgets::widget>
