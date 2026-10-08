<div class="flex flex-col gap-4">
    <div class="relative grid min-h-[52vh] max-h-[78vh] place-items-center overflow-auto rounded-2xl border border-slate-200 bg-slate-50 p-3 shadow-inner sm:min-h-[64vh] sm:p-5">
        <img
            src="{{ $photoUrl }}"
            alt="Pratinjau {{ $photoName }}"
            class="block max-h-[70vh] w-auto max-w-full rounded-xl border border-white bg-white object-contain shadow-lg"
            loading="eager"
            decoding="async"
        >

        <span class="pointer-events-none absolute bottom-4 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full border border-slate-200 bg-white/90 px-3 py-1.5 text-[11px] font-medium text-slate-600 shadow-sm backdrop-blur-sm">
            Foto ditampilkan utuh sesuai orientasi aslinya
        </span>
    </div>

    <p class="break-words text-sm font-semibold text-gray-950 dark:text-white">{{ $photoName }}</p>
</div>
