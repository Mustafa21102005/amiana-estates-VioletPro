<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ isset($title) ? trim($title) . ' | ' : '' }}{{ config('app.name') }}</title>
    @include('partials.assets')
</head>

<body class="bg-ivory font-sans text-charcoal antialiased dark:bg-navy dark:text-ivory">
    <div x-data="{
        open: false,
        collapsed: document.documentElement.classList.contains('sidebar-collapsed'),
        toggleCollapsed() {
            this.collapsed = !this.collapsed;
            document.documentElement.classList.toggle('sidebar-collapsed', this.collapsed);
            try {
                localStorage.setItem('amiana.sidebar', this.collapsed ? 'collapsed' : 'expanded');
            } catch (e) {}
        },
    }" @keydown.escape.window="open = false" class="min-h-screen">

        {{-- Mobile overlay: darkens the page behind the open drawer --}}
        <div x-show="open" x-cloak x-transition.opacity @click="open = false"
            class="fixed inset-0 z-40 bg-black/60 lg:hidden"></div>

        {{-- Sidebar: a slide-in drawer on small screens, collapsible on desktop --}}
        <aside :class="open ? '' : 'max-lg:-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col overflow-x-hidden border-r border-gold/10 bg-navy text-ivory transition-[width,transform] duration-200 lg:[.sidebar-collapsed_&]:w-20">

            <div
                class="flex justify-center px-6 pb-4 pt-8 lg:[.sidebar-collapsed_&]:px-2 lg:[.sidebar-collapsed_&]:pt-6">
                <a href="{{ route('dashboard') }}">
                    <span class="lg:[.sidebar-collapsed_&]:hidden">
                        <x-application-logo on="dark" class="h-28" />
                    </span>
                    <span class="hidden lg:[.sidebar-collapsed_&]:block">
                        <x-application-logo on="dark" :mark="true" class="h-12" />
                    </span>
                </a>
            </div>

            <div class="mx-6 h-px bg-gold/30 lg:[.sidebar-collapsed_&]:mx-4"></div>

            <div class="scrollbar-dark flex-1 overflow-y-auto overflow-x-hidden py-6">
                @include('partials.admin-nav')
            </div>

            {{-- Collapse toggle: desktop only --}}
            <div class="hidden border-t border-white/10 lg:block">
                <button type="button" @click="toggleCollapsed()" :aria-expanded="(!collapsed).toString()"
                    :title="collapsed ? 'Expand menu' : null"
                    class="flex w-full items-center gap-3 whitespace-nowrap px-5 py-3 text-xs font-medium uppercase tracking-[0.18em] text-ivory/60 transition hover:bg-white/5 hover:text-ivory lg:[.sidebar-collapsed_&]:justify-center lg:[.sidebar-collapsed_&]:gap-0 lg:[.sidebar-collapsed_&]:px-0">
                    <svg class="h-5 w-5 shrink-0 transition-transform duration-200 lg:[.sidebar-collapsed_&]:rotate-180"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="m15 6-6 6 6 6" />
                    </svg>
                    <span class="lg:[.sidebar-collapsed_&]:sr-only"
                        x-text="collapsed ? 'Expand menu' : 'Collapse menu'">Collapse menu</span>
                </button>
            </div>

            <div class="border-t border-white/10 p-5 lg:[.sidebar-collapsed_&]:px-0 lg:[.sidebar-collapsed_&]:py-4">
                <div class="lg:[.sidebar-collapsed_&]:hidden">
                    <p class="truncate text-sm text-ivory">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-mist">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-4 lg:[.sidebar-collapsed_&]:mt-0">
                    @csrf
                    <button type="submit" :title="collapsed ? 'Log out' : null"
                        class="flex items-center gap-3 whitespace-nowrap text-xs font-semibold uppercase tracking-[0.18em] text-gold transition hover:text-ivory lg:[.sidebar-collapsed_&]:w-full lg:[.sidebar-collapsed_&]:justify-center lg:[.sidebar-collapsed_&]:gap-0">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M9 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h4M16 8l4 4-4 4M20 12H9" />
                        </svg>
                        <span class="lg:[.sidebar-collapsed_&]:sr-only">Log out</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Everything to the right of the sidebar --}}
        <div class="transition-[padding] duration-200 lg:pl-64 lg:[.sidebar-collapsed_&]:pl-20">

            <header
                class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-mist/20 bg-ivory/90 px-4 backdrop-blur sm:px-8 dark:border-white/10 dark:bg-navy/90">
                <button type="button" @click="open = true" class="text-navy lg:hidden dark:text-ivory"
                    aria-label="Open menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                @isset($title)
                    <h1 class="font-serif text-2xl text-navy dark:text-ivory">{{ $title }}</h1>
                @endisset

                <div class="ms-auto flex items-center gap-5">
                    <a href="{{ url('/') }}" target="_blank" rel="noopener"
                        class="text-xs font-semibold uppercase tracking-[0.18em] text-navy/70 transition hover:text-gold dark:text-ivory/70">
                        View website
                    </a>
                    <x-theme-toggle class="text-navy dark:text-ivory" />
                </div>
            </header>

            <main class="px-4 py-8 sm:px-8">
                <div class="mx-auto max-w-6xl">
                    @foreach (['success' => 'border-gold bg-gold/10', 'error' => 'border-red-500 bg-red-500/10'] as $key => $classes)
                        @if (session($key))
                            <div class="mb-6 border-l-4 px-4 py-3 text-sm {{ $classes }}">{{ session($key) }}
                            </div>
                        @endif
                    @endforeach

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <x-confirm-delete />
</body>

</html>
