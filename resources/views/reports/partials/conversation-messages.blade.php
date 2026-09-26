@forelse ($report->anonymousMessages as $message)
    <article class="flex {{ $message->sender_type === 'reporter' ? 'justify-end' : 'justify-start' }}">
        <div class="max-w-[85%] rounded-xl px-3.5 py-2.5 {{ $message->sender_type === 'reporter' ? 'rounded-br-sm bg-[#2563EB] text-white' : 'rounded-bl-sm bg-slate-100 text-[#0B2342]' }}">
            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[10px] font-semibold uppercase tracking-wider {{ $message->sender_type === 'reporter' ? 'text-blue-100' : 'text-[#64748B]' }}">
                <span>{{ $message->sender_type === 'reporter' ? 'Anda (Pelapor)' : 'Petugas TAMBORA' }}</span>
                <span>·</span>
                <time>{{ $message->created_at->translatedFormat('d M Y, H:i') }}</time>
            </div>
            <p class="mt-1 whitespace-pre-line text-xs leading-relaxed sm:text-sm">{{ $message->body }}</p>
        </div>
    </article>
@empty
    <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50/50 px-4 py-5 text-center text-xs leading-relaxed text-[#64748B]">
        Belum ada percakapan. Jika ada informasi atau klarifikasi baru yang ingin disampaikan, kirimkan melalui formulir di bawah ini.
    </div>
@endforelse
