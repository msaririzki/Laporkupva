@php
    $request = $record->pendingProgressRequest;
    $latestRequest = $record->latestProgressRequest;
@endphp

@if ($request)
    <details id="persetujuan-progres" class="group/progress rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-800 dark:bg-amber-950/30">
        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl p-4 text-amber-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 dark:text-amber-200 sm:px-6 [&::-webkit-details-marker]:hidden">
            <span class="text-base font-bold">Menunggu persetujuan Administrator</span>
            <span class="flex shrink-0 items-center gap-2 text-xs font-medium">
                <span class="hidden sm:inline">
                    <span class="group-open/progress:hidden">Lihat detail</span>
                    <span class="hidden group-open/progress:inline">Tutup detail</span>
                </span>
                <x-filament::icon icon="heroicon-m-chevron-down" class="size-5 transition-transform group-open/progress:rotate-180" />
            </span>
        </summary>
        <div class="border-t border-amber-200 px-4 pb-4 pt-2 dark:border-amber-800 sm:px-6 sm:pb-6">
            <p class="mt-3 text-xs font-semibold text-amber-900 dark:text-amber-200">Catatan Operator</p>
            <p class="mt-1 whitespace-pre-line text-sm text-amber-900 dark:text-amber-100">{{ $request->internal_note ?: 'Tidak ada catatan kegiatan tambahan.' }}</p>
            <p class="mt-2 text-sm text-amber-900 dark:text-amber-100">
                {{ $request->requester?->name ?? 'Petugas' }} mengajukan tahap <strong>{{ $request->to_status->label() }}</strong>
                pada {{ $request->created_at->translatedFormat('d M Y, H:i') }}.
                Status masyarakat tetap <strong>{{ $record->status->label() }}</strong> sampai pengajuan disetujui.
            </p>
            <p class="mt-3 text-xs font-semibold text-amber-900 dark:text-amber-200">Pesan yang diajukan untuk masyarakat</p>
            <p class="mt-1 whitespace-pre-line text-sm text-amber-900 dark:text-amber-100">{{ $request->public_note }}</p>
            <p class="mt-3 text-xs text-amber-800 dark:text-amber-200">{{ count($request->activity_photos ?? []) }} foto dilampirkan.</p>
            <p class="mt-2 text-sm font-medium text-amber-900 dark:text-amber-100">
                {{ auth()->user()?->isSuperAdmin() ? 'Buka halaman persetujuan untuk meninjau catatan kegiatan, pesan, dan foto dokumentasi pengajuan ini.' : 'Pengajuan sudah dikirim. Anda akan menerima notifikasi setelah Administrator memberikan keputusan.' }}
            </p>
            @if (auth()->user()?->isSuperAdmin())
                <x-filament::button class="mt-3" color="warning" icon="heroicon-m-check-badge" tag="a" :href="\App\Filament\Resources\ReportProgressRequests\ReportProgressRequestResource::getUrl('view', ['record' => $request])">
                    Tinjau pengajuan
                </x-filament::button>
            @endif
        </div>
    </details>
@elseif ($latestRequest?->status === 'rejected')
    <section id="persetujuan-progres" class="rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-950/30 sm:p-6">
        <h2 class="text-base font-bold text-red-900 dark:text-red-200">Pengajuan progres ditolak</h2>
        <p class="mt-2 text-sm text-red-900 dark:text-red-100">Pengajuan tahap {{ $latestRequest->to_status->label() }} ditolak oleh {{ $latestRequest->reviewer?->name ?? 'Administrator' }}.</p>
        <p class="mt-2 whitespace-pre-line text-sm text-red-900 dark:text-red-100">Alasan: {{ $latestRequest->rejection_reason }}</p>
        <p class="mt-2 text-sm font-medium text-red-900 dark:text-red-100">Perbaiki pesan atau dokumentasi, lalu gunakan Ajukan progres untuk mengirim pengajuan baru.</p>
    </section>
@elseif ($latestRequest?->status === 'approved')
    <section id="persetujuan-progres" class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-800 dark:bg-emerald-950/30 sm:p-6">
        <h2 class="text-base font-bold text-emerald-900 dark:text-emerald-200">Pengajuan progres disetujui</h2>
        <p class="mt-2 text-sm text-emerald-900 dark:text-emerald-100">Tahap {{ $latestRequest->to_status->label() }} disetujui oleh {{ $latestRequest->reviewer?->name ?? 'Administrator' }} pada {{ $latestRequest->reviewed_at?->translatedFormat('d M Y, H:i') }}. Progres masyarakat telah diperbarui.</p>
    </section>
@endif
