@php
    $summary = $analysis['summary'] ?? [];
    $items = $analysis['items'] ?? [];
    $errors = $analysis['errors'] ?? [];
@endphp

@if (! $analysis)
    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center">
        <p class="text-sm font-semibold text-slate-800">Berkas belum tersedia</p>
        <p class="mt-1 text-xs text-slate-500">Kembali ke langkah pertama dan pilih berkas yang akan diperiksa.</p>
    </div>
@else
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-5">
            @foreach ([
                ['key' => 'new', 'label' => 'Data baru', 'classes' => 'border-emerald-200 bg-emerald-50 text-emerald-800'],
                ['key' => 'updated', 'label' => 'Berubah', 'classes' => 'border-blue-200 bg-blue-50 text-blue-800'],
                ['key' => 'unchanged', 'label' => 'Tetap sama', 'classes' => 'border-slate-200 bg-slate-50 text-slate-700'],
                ['key' => 'duplicates', 'label' => 'Duplikat', 'classes' => 'border-amber-200 bg-amber-50 text-amber-800'],
                ['key' => 'invalid', 'label' => 'Bermasalah', 'classes' => 'border-rose-200 bg-rose-50 text-rose-800'],
            ] as $card)
                <div class="rounded-xl border px-3 py-3 {{ $card['classes'] }}">
                    <span class="block text-xl font-bold leading-none">{{ $summary[$card['key']] ?? 0 }}</span>
                    <span class="mt-1.5 block text-[0.68rem] font-semibold uppercase tracking-wide">{{ $card['label'] }}</span>
                </div>
            @endforeach
        </div>

        @if ($errors !== [])
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="font-semibold">Perbaiki data berikut sebelum menyimpan:</p>
                <ul class="mt-1.5 list-disc space-y-1 pl-5 text-xs leading-5">
                    @foreach (array_slice($errors, 0, 5) as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @elseif (($summary['duplicates'] ?? 0) > 0)
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs leading-5 text-amber-800">
                Duplikat yang isinya sama akan dilewati otomatis agar data tidak tersimpan dua kali.
            </div>
        @endif

        <div class="flex items-end justify-between gap-3">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Rincian pratinjau</h3>
                <p class="mt-0.5 text-xs text-slate-500">Tidak ada data yang disimpan sebelum Anda menekan tombol simpan.</p>
            </div>
            <span class="shrink-0 text-xs font-medium text-slate-500">{{ count($items) }} baris</span>
        </div>

        <div class="max-h-[26rem] space-y-2.5 overflow-y-auto pr-1">
            @forelse ($items as $item)
                @php
                    $badge = match ($item['status']) {
                        'new' => ['label' => 'Baru', 'classes' => 'bg-emerald-100 text-emerald-700'],
                        'updated' => ['label' => 'Diubah', 'classes' => 'bg-blue-100 text-blue-700'],
                        'unchanged' => ['label' => 'Tidak berubah', 'classes' => 'bg-slate-100 text-slate-600'],
                        'duplicate' => ['label' => 'Duplikat', 'classes' => 'bg-amber-100 text-amber-700'],
                        default => ['label' => 'Perlu diperbaiki', 'classes' => 'bg-rose-100 text-rose-700'],
                    };
                @endphp

                <article class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[0.65rem] font-bold text-slate-500">Baris {{ $item['row'] }}</span>
                                <span class="rounded-full px-2 py-0.5 text-[0.68rem] font-semibold {{ $badge['classes'] }}">{{ $badge['label'] }}</span>
                            </div>
                            <p class="mt-2 truncate text-sm font-semibold text-slate-900">{{ $item['name'] }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">Nomor izin: {{ $item['license_number'] }}</p>
                            @if (filled($item['office_type'] ?? null))
                                <p class="mt-0.5 text-xs font-semibold text-slate-600">{{ $item['office_type'] === 'KP' ? 'Kantor pusat (KP)' : 'Kantor cabang (KC)' }}</p>
                            @endif
                            @if (filled($item['address'] ?? null))
                                <p class="mt-1 text-xs leading-5 text-slate-500">{{ $item['address'] }}</p>
                            @endif
                        </div>
                    </div>

                    @if ($item['changes'] !== [])
                        <div class="mt-3 grid gap-2 sm:grid-cols-2">
                            @foreach ($item['changes'] as $change)
                                <div class="rounded-lg border border-blue-100 bg-blue-50/70 px-3 py-2.5">
                                    <p class="text-[0.68rem] font-bold uppercase tracking-wide text-blue-700">{{ $change['label'] }}</p>
                                    <div class="mt-1.5 flex min-w-0 items-center gap-2 text-xs">
                                        <span class="min-w-0 truncate text-slate-500 line-through" title="{{ $change['before'] }}">{{ $change['before'] }}</span>
                                        <svg class="size-3.5 shrink-0 text-blue-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69l-3.22-3.22a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="min-w-0 truncate font-semibold text-blue-800" title="{{ $change['after'] }}">{{ $change['after'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($item['message'])
                        <p class="mt-3 rounded-lg px-3 py-2 text-xs leading-5 {{ $item['is_conflict'] ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $item['message'] }}
                        </p>
                    @endif
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center text-sm text-slate-500">
                    Belum ada baris data yang dapat ditampilkan.
                </div>
            @endforelse
        </div>

        <div class="rounded-xl border px-4 py-3 text-xs font-medium {{ $analysis['can_import'] ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-rose-200 bg-rose-50 text-rose-800' }}">
            {{ $analysis['can_import']
                ? 'Pratinjau siap. Periksa kembali, lalu simpan hasil impor.'
                : 'Impor belum dapat disimpan. Kembali dan unggah ulang berkas setelah masalah diperbaiki.' }}
        </div>
    </div>
@endif
