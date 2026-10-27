<x-app-layout>
    <x-slot name="title">Message</x-slot>

    <div x-data class="space-y-8">
        <a href="{{ route('admin.messages.index') }}"
            class="inline-block text-xs font-semibold uppercase tracking-[0.18em] text-navy/70 transition hover:text-gold dark:text-ivory/70">
            &larr; All messages
        </a>

        <x-card>
            <dl class="grid gap-6 sm:grid-cols-3">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-widest text-mist">Phone</dt>
                    <dd class="mt-2 font-serif text-2xl text-navy dark:text-ivory">
                        <a href="{{ $message->phoneLink() }}" class="transition hover:text-gold">{{ $message->phone }}</a>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-widest text-mist">Email</dt>
                    <dd class="mt-2 break-all text-sm">
                        @if ($message->email)
                            <a href="mailto:{{ $message->email }}"
                                class="transition hover:text-gold">{{ $message->email }}</a>
                        @else
                            <span class="text-charcoal/60 dark:text-ivory/60">Not provided</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-widest text-mist">Received</dt>
                    <dd class="mt-2 text-sm">{{ $message->created_at->format('j M Y, H:i') }}</dd>
                </div>
            </dl>

            <div class="mt-8 h-px w-10 bg-gold"></div>

            {{-- Plain text only: the visitor's words are escaped, never rendered as HTML --}}
            <p class="mt-8 whitespace-pre-line text-charcoal/90 dark:text-ivory/85">{{ $message->body }}</p>
        </x-card>

        <div class="flex flex-wrap items-center gap-3">
            <x-button-link :href="$message->phoneLink()" class="gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M6.5 3.5h3l1.5 4-2 1.5a11 11 0 0 0 6 6l1.5-2 4 1.5v3a2 2 0 0 1-2 2A16 16 0 0 1 4.5 5.5a2 2 0 0 1 2-2Z" />
                </svg>
                Call
            </x-button-link>

            @if ($whatsappLink)
                <x-button-link variant="outline" :href="$whatsappLink" target="_blank" rel="noopener" class="gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 5h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-8l-5 4v-4H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                    </svg>
                    WhatsApp
                </x-button-link>
            @endif

            @if ($message->email)
                <x-button-link variant="outline" :href="'mailto:' . $message->email" class="gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="1.5" />
                        <path d="m3.5 7 8.5 6 8.5-6" />
                    </svg>
                    Reply by email
                </x-button-link>
            @endif

            <form method="POST" action="{{ route('admin.messages.unread', $message) }}">
                @csrf
                @method('PATCH')
                <x-secondary-button type="submit">Mark as unread</x-secondary-button>
            </form>

            <button type="button"
                x-on:click="$dispatch('confirm-delete', {
                    action: @js(route('admin.messages.destroy', $message)),
                    title: 'Delete message?',
                    message: 'This message will be permanently removed.',
                })"
                class="ms-auto text-xs font-semibold uppercase tracking-[0.18em] text-red-600 transition hover:text-red-400">Delete</button>
        </div>
    </div>
</x-app-layout>
