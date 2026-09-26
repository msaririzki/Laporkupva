<?php

use App\Enums\UserRole;
use App\Models\Report;
use App\Models\User;
use App\Notifications\Admin\NewReporterMessage;
use App\Notifications\Admin\NewReportSubmitted;
use App\Rules\NoHtml;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public Report $record;

    public string $body = '';

    public function mount(Report $record): void
    {
        $this->record = $record;
        $this->authorizeAdmin();
        $this->markConversationAsRead();
    }

    /** @return Collection<int, \App\Models\AnonymousMessage> */
    #[Computed]
    public function conversationMessages(): Collection
    {
        return $this->record
            ->anonymousMessages()
            ->with('user:id,name')
            ->get();
    }

    public function refreshConversation(): void
    {
        $this->authorizeAdmin();
        $this->markConversationAsRead();
    }

    public function send(): void
    {
        $user = $this->authorizeAdmin();
        $validated = $this->validate();

        $this->record->anonymousMessages()->create([
            'user_id' => $user->getKey(),
            'sender_type' => 'admin',
            'body' => trim($validated['body']),
        ]);

        $this->reset('body');
        $this->markConversationAsRead();
        $this->dispatch('report-message-sent');

        Notification::make()
            ->title('Balasan terkirim')
            ->body('Pesan langsung ditambahkan ke percakapan pelapor.')
            ->success()
            ->send();
    }

    /** @return array<string, array<int, mixed>> */
    protected function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:2', 'max:2000', new NoHtml],
        ];
    }

    /** @return array<string, string> */
    protected function validationAttributes(): array
    {
        return [
            'body' => 'pesan',
        ];
    }

    private function authorizeAdmin(): User
    {
        $user = auth()->user();

        abort_unless(
            $user instanceof User
                && $user->is_active
                && in_array($user->role, [UserRole::Admin, UserRole::SuperAdmin], true),
            403,
        );

        return $user;
    }

    private function markConversationAsRead(): void
    {
        $user = $this->authorizeAdmin();

        $this->record->anonymousMessages()
            ->where('sender_type', 'reporter')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $user->unreadNotifications()
            ->whereIn('type', [NewReportSubmitted::class, NewReporterMessage::class])
            ->where('data->report_id', $this->record->getKey())
            ->update(['read_at' => now()]);
    }
};
?>

<div
    wire:poll.15s="refreshConversation"
    x-data="{
        scrollToLatest() {
            this.$nextTick(() => {
                this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight
            })
        },
    }"
    x-init="scrollToLatest()"
    x-on:report-message-sent.window="scrollToLatest()"
    class="overflow-hidden rounded-[1.35rem] border border-slate-200/90 bg-white shadow-[0_18px_50px_-38px_rgba(15,23,42,0.55)] dark:border-white/10 dark:bg-slate-900"
>
    <div class="flex flex-col gap-3 border-b border-slate-200/80 bg-gradient-to-r from-slate-50 to-white px-4 py-3.5 sm:flex-row sm:items-center sm:justify-between sm:px-5 dark:border-white/10 dark:from-slate-900 dark:to-slate-900">
        <div class="flex min-w-0 items-center gap-3">
            <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700 ring-1 ring-blue-100 dark:bg-blue-500/10 dark:text-blue-300 dark:ring-blue-400/20">
                <x-filament::icon icon="heroicon-m-shield-check" class="size-5" />
            </div>

            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-900 dark:text-white">Identitas pelapor terlindungi</p>
                <p class="truncate text-xs text-slate-500 dark:text-slate-400">Percakapan hanya dapat diakses petugas terkait.</p>
            </div>
        </div>

        <div class="inline-flex w-fit shrink-0 items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-[11px] font-semibold text-emerald-700 dark:border-emerald-400/20 dark:bg-emerald-500/10 dark:text-emerald-300">
            <span class="relative flex size-2">
                <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-50"></span>
                <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
            </span>
            Pesan diperbarui otomatis
        </div>
    </div>

    <div x-ref="messages" class="min-h-48 max-h-[26rem] space-y-3 overflow-y-auto bg-slate-50/70 px-3 py-4 sm:px-5 sm:py-5 dark:bg-slate-950/30">
        @forelse ($this->conversationMessages as $message)
            @php($isAdmin = $message->sender_type === 'admin')

            <article
                wire:key="conversation-message-{{ $message->getKey() }}"
                @class([
                    'flex items-start gap-2.5',
                    'flex-row-reverse' => $isAdmin,
                ])
            >
                <div @class([
                    'mt-5 flex size-7 shrink-0 items-center justify-center rounded-full text-[10px] font-bold ring-1',
                    'bg-blue-600 text-white ring-blue-500/30' => $isAdmin,
                    'bg-white text-slate-600 ring-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:ring-white/10' => ! $isAdmin,
                ])>
                    {{ $isAdmin ? 'TA' : 'PA' }}
                </div>

                <div class="max-w-[82%] sm:max-w-[70%]">
                    <div @class([
                        'mb-1.5 flex flex-wrap items-center gap-x-2 gap-y-0.5 px-1',
                        'justify-end' => $isAdmin,
                    ])>
                        <span class="text-[11px] font-semibold {{ $isAdmin ? 'text-blue-700 dark:text-blue-300' : 'text-slate-600 dark:text-slate-300' }}">
                            {{ $isAdmin ? 'Petugas TAMBORA' : 'Pelapor anonim' }}
                        </span>
                        <time class="text-[10px] tabular-nums text-slate-400 dark:text-slate-500">
                            {{ $message->created_at->translatedFormat('d M, H:i') }}
                        </time>
                    </div>

                    <div @class([
                        'rounded-2xl px-3.5 py-2.5 text-sm leading-relaxed shadow-sm',
                        'rounded-tr-md bg-blue-600 text-white shadow-blue-600/10' => $isAdmin,
                        'rounded-tl-md border border-slate-200 bg-white text-slate-700 dark:border-white/10 dark:bg-slate-800 dark:text-slate-100' => ! $isAdmin,
                    ])>
                        <p class="break-words whitespace-pre-line">{{ $message->body }}</p>
                    </div>
                </div>
            </article>
        @empty
            <div class="flex min-h-48 flex-col items-center justify-center gap-2.5 px-6 text-center">
                <div class="flex size-11 items-center justify-center rounded-2xl bg-white text-blue-600 shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:text-blue-300 dark:ring-white/10">
                    <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="size-5" />
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Belum ada pesan</p>
                    <p class="mt-1 max-w-sm text-xs leading-relaxed text-slate-500 dark:text-slate-400">Mulai percakapan jika petugas memerlukan informasi tambahan.</p>
                </div>
            </div>
        @endforelse
    </div>

    <form wire:submit="send" class="border-t border-slate-200/80 bg-white p-3 sm:p-4 dark:border-white/10 dark:bg-slate-900">
        <label for="admin-report-reply" class="sr-only">Balas pelapor</label>

        <div class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-sm transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10 dark:border-white/10 dark:bg-slate-950">
            <textarea
                id="admin-report-reply"
                wire:model="body"
                wire:keydown.ctrl.enter="send"
                rows="2"
                maxlength="2000"
                placeholder="Tulis balasan untuk pelapor..."
                class="block min-h-20 w-full resize-none border-0 bg-transparent px-3.5 py-3 text-sm leading-relaxed text-slate-900 outline-none placeholder:text-slate-400 focus:ring-0 dark:text-white dark:placeholder:text-slate-500"
            ></textarea>

            <div class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/70 px-2.5 py-2 dark:border-white/10 dark:bg-white/5">
                <p class="hidden px-1 text-[11px] text-slate-400 sm:block dark:text-slate-500">Tekan Ctrl + Enter untuk mengirim</p>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="send"
                    class="ml-auto inline-flex h-9 shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white shadow-sm shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 disabled:cursor-wait disabled:opacity-60"
                >
                    <x-filament::icon wire:loading.remove wire:target="send" icon="heroicon-m-paper-airplane" class="size-4" />
                    <svg wire:loading wire:target="send" class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" />
                        <path class="opacity-75" fill="currentColor" d="M12 3a9 9 0 0 1 9 9h-3a6 6 0 0 0-6-6V3Z" />
                    </svg>
                    <span wire:loading.remove wire:target="send">Kirim</span>
                    <span wire:loading wire:target="send">Mengirim</span>
                </button>
            </div>
        </div>

        @error('body')
            <p class="mt-2 px-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </form>
</div>
