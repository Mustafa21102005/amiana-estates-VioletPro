<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fullTitle }}</title>

    @if ($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
    @endif

    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $fullTitle }}">

    @if ($metaDescription)
        <meta property="og:description" content="{{ $metaDescription }}">
    @endif

    @if ($image)
        <meta property="og:image" content="{{ $image }}">
    @endif

    @include('partials.assets')
</head>

<body class="bg-ivory font-sans text-charcoal antialiased dark:bg-navy dark:text-ivory">

    <div x-data="{
        progress: 0,
        update() {
            const max = document.documentElement.scrollHeight - window.innerHeight;
            this.progress = max > 0 ? Math.min(1, window.scrollY / max) : 0;
        },
    }" x-init="update()" x-on:scroll.window.passive="update()"
        x-on:resize.window.passive="update()" class="pointer-events-none fixed inset-x-0 top-0 z-50 h-[3px]"
        aria-hidden="true">
        <div class="h-full origin-left bg-gold" x-bind:style="`transform: scaleX(${progress})`"></div>
    </div>

    {{-- Header: transparent over the hero, solid once the page is scrolled or the menu is open --}}
    <header x-data="{ scrolled: window.scrollY > 30, menu: false }" x-on:scroll.window="scrolled = window.scrollY > 30"
        x-on:keydown.escape.window="menu = false"
        :class="scrolled || menu ? 'bg-ivory/95 shadow-sm backdrop-blur dark:bg-navy/95' : 'bg-transparent'"
        class="fixed inset-x-0 top-0 z-40 transition duration-300">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">

            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span x-show="! (scrolled || menu)">
                    <x-application-logo :mark="true" on="dark" class="h-11" />
                </span>
                <span x-show="scrolled || menu" x-cloak>
                    <x-application-logo :mark="true" class="h-11" />
                </span>
                <span class="leading-none">
                    <span class="block font-serif text-xl uppercase tracking-[0.3em] transition"
                        :class="scrolled || menu ? 'text-navy dark:text-ivory' : 'text-ivory'">Amiana</span>
                    <span class="mt-1.5 block text-[0.6rem] uppercase tracking-[0.55em] text-gold">Estates</span>
                </span>
            </a>

            <div class="flex items-center gap-4 md:gap-10">
                <nav class="hidden items-center gap-10 md:flex">
                    @foreach ($nav as [$label, $href])
                        <a href="{{ $href }}"
                            class="text-xs font-semibold uppercase tracking-[0.2em] transition hover:text-gold"
                            :class="scrolled ? 'text-navy dark:text-ivory' : 'text-ivory'">{{ $label }}</a>
                    @endforeach
                </nav>

                <x-theme-toggle x-bind:class="scrolled || menu ? 'text-navy dark:text-ivory' : 'text-ivory'" />

                <button type="button" x-on:click="menu = ! menu" class="md:hidden" aria-label="Menu"
                    :aria-expanded="menu.toString()"
                    :class="scrolled || menu ? 'text-navy dark:text-ivory' : 'text-ivory'">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path x-show="! menu" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                        <path x-show="menu" x-cloak stroke-linecap="round" d="M6 6l12 12M18 6 6 18" />
                    </svg>
                </button>
            </div>
        </div>

        <nav x-show="menu" x-cloak x-transition class="border-t border-mist/20 px-6 pb-6 pt-2 md:hidden">
            @foreach ($nav as [$label, $href])
                <a href="{{ $href }}" x-on:click="menu = false"
                    class="block py-3 text-xs font-semibold uppercase tracking-[0.2em] text-navy dark:text-ivory">{{ $label }}</a>
            @endforeach
        </nav>
    </header>

    <main>{{ $slot }}</main>

    <x-contact-form />

    <footer class="bg-navy text-ivory">
        <div class="mx-auto grid max-w-7xl gap-14 px-6 py-20 lg:grid-cols-3 lg:px-8">

            <div>
                <x-application-logo on="dark" class="h-40" />
                @if ($tagline)
                    <p class="mt-6 max-w-xs font-serif text-xl text-ivory/80">{{ $tagline }}</p>
                @endif
            </div>

            <div>
                <h2 class="text-xs font-semibold uppercase tracking-[0.3em] text-gold">Contact</h2>
                <ul class="mt-6 space-y-4 text-sm text-ivory/80">
                    @if (!empty($settings['phone']))
                        <li>
                            <a href="{{ $phoneHref }}" class="transition hover:text-gold">
                                {{ $settings['phone'] }}
                            </a>
                        </li>
                    @endif
                    @if (!empty($settings['email']))
                        <li>
                            <a href="mailto:{{ $settings['email'] }}" class="transition hover:text-gold">
                                {{ $settings['email'] }}
                            </a>
                        </li>
                    @endif
                    @if (!empty($settings['address']))
                        <li class="whitespace-pre-line">{{ $settings['address'] }}</li>
                    @endif
                    @if (!empty($settings['map_url']))
                        <li>
                            <a href="{{ $settings['map_url'] }}" target="_blank" rel="noopener"
                                class="text-gold underline underline-offset-4">
                                View on map
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div>
                <h2 class="text-xs font-semibold uppercase tracking-[0.3em] text-gold">Explore</h2>
                <ul class="mt-6 space-y-4 text-sm text-ivory/80">
                    @foreach ($footerNav as [$label, $href])
                        <li>
                            <a href="{{ $href }}" class="transition hover:text-gold">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>

                @if ($social)
                    <div class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-xs font-semibold uppercase tracking-[0.2em]">
                        @foreach ($social as $label => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener"
                                class="text-ivory/70 transition hover:text-gold">{{ $label }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="border-t border-white/10 py-6 text-center text-xs text-mist">
            &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
        </div>
    </footer>
</body>

</html>
