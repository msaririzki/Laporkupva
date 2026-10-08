<div class="rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-4 sm:flex sm:items-center sm:justify-between sm:gap-5">
    <div class="min-w-0">
        <p class="text-sm font-semibold text-slate-900">Gunakan format yang sudah disiapkan</p>
        <p class="mt-1 text-xs leading-5 text-slate-600">
            Unggah langsung file Data KUPVA BB di NTB dari BI atau gunakan template Excel. Nomor telepon tidak diimpor.
            Pada format BI, wilayah dikenali dari alamat dan KP/KC disimpan terpisah. Tanggal teks BI menggunakan MM/DD/YYYY.
            Nomor izin boleh kosong jika alamat tersedia.
        </p>
    </div>

    <a
        href="{{ $templateUrl }}"
        download="template-impor-kupva.xlsx"
        class="mt-3 inline-flex min-h-10 shrink-0 items-center justify-center gap-2 rounded-xl border border-blue-200 bg-white px-4 text-sm font-semibold text-blue-700 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 sm:mt-0"
    >
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M5 20h14" />
        </svg>
        Unduh template Excel
    </a>
</div>
