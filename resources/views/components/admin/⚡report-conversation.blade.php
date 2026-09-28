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
    x-data="{
        channelName: @js($this->record->realtimeChannelName()),
        connectionState: 'connecting',
        echoHandler: null,
        echoLoadedHandler: null,
        connectionStateHandler: null,
        fallbackTimer: null,
        hasNewMessage: false,
        isRefreshing: false,

        init() {
            this.scrollToLatest(false)

            this.echoLoadedHandler = () => this.subscribeRealtime()

            if (window.Echo) {
                this.subscribeRealtime()
            } else {
                window.addEventListener('TamboraEchoLoaded', this.echoLoadedHandler, { once: true })
            }

            this.fallbackTimer = window.setInterval(() => {
                if (this.connectionState !== 'live' && ! document.hidden) {
                    this.refreshConversation(false)
                }
            }, 25000)
        },

        subscribeRealtime() {
            if (! window.Echo || this.echoHandler) return

            this.echoHandler = (event) => {
                if (! event?.kind || event.kind === 'message') {
                    this.refreshConversation(true)
                }
            }

            window.Echo.channel(this.channelName)
                .listen('.report.updated', this.echoHandler)

            const connection = window.Echo.connector?.pusher?.connection

            if (! connection) {
                this.connectionState = 'live'
                return
            }

            const syncConnectionState = (state = connection.state) => {
                this.connectionState = state === 'connected'
                    ? 'live'
                    : ['unavailable', 'failed', 'disconnected'].includes(state)
                        ? 'fallback'
                        : 'connecting'
            }

            this.connectionStateHandler = ({ current }) => syncConnectionState(current)
            connection.bind('state_change', this.connectionStateHandler)
            syncConnectionState()
        },

        isNearLatest() {
            const messages = this.$refs.messages

            return (messages.scrollHeight - messages.scrollTop - messages.clientHeight) < 96
        },

        refreshConversation(fromRealtime = false) {
            if (this.isRefreshing) return

            const shouldFollowLatest = this.isNearLatest()
            const previousLatestMessage = this.$refs.messages.dataset.latestMessageId
            this.isRefreshing = true

            $wire.refreshConversation().then(() => {
                const hasChanged = previousLatestMessage !== this.$refs.messages.dataset.latestMessageId

                if (hasChanged && (shouldFollowLatest || ! fromRealtime)) {
                    this.scrollToLatest(true)
                } else if (hasChanged) {
                    this.hasNewMessage = true
                }
            }).finally(() => {
                this.isRefreshing = false
            })
        },

        scrollToLatest(smooth = true) {
            this.$nextTick(() => {
                this.$refs.messages.scrollTo({
                    top: this.$refs.messages.scrollHeight,
                    behavior: smooth && ! window.matchMedia('(prefers-reduced-motion: reduce)').matches
                        ? 'smooth'
                        : 'auto',
                })
                this.hasNewMessage = false
            })
        },

        destroy() {
            window.clearInterval(this.fallbackTimer)
            window.removeEventListener('TamboraEchoLoaded', this.echoLoadedHandler)

            if (window.Echo && this.echoHandler) {
                window.Echo.channel(this.channelName)
                    .stopListening('.report.updated', this.echoHandler)
            }

            const connection = window.Echo?.connector?.pusher?.connection

            if (connection && this.connectionStateHandler) {
                connection.unbind('state_change', this.connectionStateHandler)
            }
        },
    }"
    x-on:report-message-sent.window="scrollToLatest(true)"
    class="overflow-hidden rounded-[1.35rem] border border-slate-200/90 bg-white shadow-[0_18px_50px_-38px_rgba(15,23,42,0.55)] dark:border-white/10 dark:bg-slate-900"
>
    <div class="relative">
        <div
            x-show="isRefreshing"
            x-transition.opacity.duration.150ms
            class="pointer-events-none absolute inset-x-0 top-0 z-10 h-0.5 overflow-hidden bg-blue-100"
            aria-hidden="true"
        >
            <span class="block h-full w-1/3 animate-[tambora-chat-sync_1s_ease-in-out_infinite] rounded-full bg-blue-500"></span>
        </div>

        <div
            x-ref="messages"
            data-latest-message-id="{{ $this->conversationMessages->last()?->getKey() }}"
            x-on:scroll.passive="if (isNearLatest()) hasNewMessage = false"
            class="min-h-48 max-h-[26rem] space-y-3 overflow-y-auto bg-slate-50/70 px-3 py-4 scroll-smooth sm:px-5 sm:py-5 dark:bg-slate-950/30"
            aria-live="polite"
            aria-label="Percakapan dengan pelapor"
        >
        @forelse ($this->conversationMessages as $message)
            @php($isAdmin = $message->sender_type === 'admin')

            <article
                wire:key="conversation-message-{{ $message->getKey() }}"
                @class([
                    'report-chat-message flex items-start gap-2.5',
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

        <button
            x-cloak
            x-show="hasNewMessage"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-2 opacity-0"
            x-on:click="scrollToLatest(true)"
            type="button"
            class="absolute bottom-3 left-1/2 inline-flex -translate-x-1/2 items-center gap-1.5 rounded-full border border-blue-200 bg-white px-3.5 py-2 text-xs font-semibold text-blue-700 shadow-lg shadow-slate-900/10 transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-blue-500/15 dark:border-blue-400/20 dark:bg-slate-800 dark:text-blue-300"
        >
            <x-filament::icon icon="heroicon-m-arrow-down" class="size-3.5" />
            Pesan baru
        </button>
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
