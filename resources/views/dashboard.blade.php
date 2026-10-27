<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <p class="max-w-2xl text-charcoal/70 dark:text-ivory/70">
        Welcome back, {{ auth()->user()->name }}. Everything shown on the Amiana Estates website is managed from here.
    </p>

    <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <x-stat-card :label="$stat['label']" :value="$stat['value']" :note="$stat['note']" :href="$stat['href']" :highlight="$stat['highlight']" />
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-5">

        {{-- Unread messages --}}
        <x-card class="lg:col-span-3">
            <div class="flex items-center justify-between gap-4">
                <h2 class="font-serif text-2xl text-navy dark:text-ivory">Unread messages</h2>
                <a href="{{ route('admin.messages.index') }}"
                    class="text-xs font-semibold uppercase tracking-[0.18em] text-navy transition hover:text-gold dark:text-ivory">

                    Open inbox
                </a>
            </div>
            <div class="mt-4 h-px w-10 bg-gold"></div>

            @if ($latestUnread->isEmpty())
                <p class="mt-6 flex items-start gap-3 text-sm text-charcoal/80 dark:text-ivory/75">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-gold" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="m5 12 5 5 9-10" />
                    </svg>
                    You are all caught up. There are no unread messages.
                </p>
            @else
                <ul class="mt-2 divide-y divide-mist/10 dark:divide-white/10">
                    @foreach ($latestUnread as $message)
                        <li>
                            <a href="{{ route('admin.messages.show', $message) }}"
                                class="block rounded-lg p-4 transition hover:bg-gold/5">
                                <div class="flex items-center justify-between gap-4">
                                    <p class="text-sm font-semibold text-navy dark:text-ivory">{{ $message->phone }}</p>
                                    <p class="shrink-0 text-xs text-charcoal/60 dark:text-ivory/60">
                                        {{ $message->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <p class="mt-1 truncate text-sm text-charcoal/70 dark:text-ivory/70">
                                    {{ $message->body }}
                                </p>
                            </a>
                        </li>
                    @endforeach
                </ul>

                @if ($unreadTotal > $latestUnread->count())
                    <p class="mt-4 text-xs text-charcoal/70 dark:text-ivory/70">
                        and {{ $unreadTotal - $latestUnread->count() }} more unread.
                        <a href="{{ route('admin.messages.index') }}"
                            class="font-semibold underline underline-offset-4 transition hover:text-gold">See them
                            all</a>
                    </p>
                @endif
            @endif
        </x-card>

        {{-- Needs attention --}}
        <x-card class="lg:col-span-2">
            <h2 class="font-serif text-2xl text-navy dark:text-ivory">Needs attention</h2>
            <div class="mt-4 h-px w-10 bg-gold"></div>

            @if ($attention === [])
                <p class="mt-6 flex items-start gap-3 text-sm text-charcoal/80 dark:text-ivory/75">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-gold" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="m5 12 5 5 9-10" />
                    </svg>
                    Everything is in place. The website has all the content it needs.
                </p>
            @else
                <ul class="mt-4 space-y-4">
                    @foreach ($attention as $item)
                        <li class="flex items-start justify-between gap-4 text-sm">
                            <span class="flex gap-3 text-charcoal/80 dark:text-ivory/75">
                                <span @class([
                                    'mt-2 h-1.5 w-1.5 shrink-0 rounded-full',
                                    'bg-red-500' => $item['urgent'] ?? false,
                                    'bg-gold' => !($item['urgent'] ?? false),
                                ]) aria-hidden="true"></span>
                                <span>
                                    @if ($item['urgent'] ?? false)
                                        <span class="sr-only">Urgent: </span>
                                    @endif
                                    {{ $item['text'] }}
                                </span>
                            </span>
                            <a href="{{ $item['url'] }}"
                                class="shrink-0 text-xs font-semibold uppercase tracking-[0.18em] text-navy transition hover:text-gold dark:text-ivory">
                                {{ $item['label'] ?? 'Fix' }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>
    </div>
</x-app-layout>
