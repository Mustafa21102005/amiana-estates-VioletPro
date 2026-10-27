<x-site-layout :description="$heroSubtitle" :image="$heroImage?->url">

    {{-- Hero --}}
    <section class="relative isolate flex min-h-[100svh] items-end overflow-hidden bg-navy text-ivory">
        @if ($heroImage)
            <img src="{{ $heroImage->url }}" alt="{{ $heroImage->alt_text ?: config('app.name') }}" fetchpriority="high"
                class="absolute inset-0 -z-10 h-full w-full object-cover">
        @endif

        @if ($heroVideo)
            <video src="{{ $heroVideo->url }}" autoplay muted loop playsinline preload="metadata" aria-hidden="true"
                class="absolute inset-0 -z-10 h-full w-full object-cover motion-reduce:hidden"></video>
        @endif
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-navy via-navy/50 to-navy/60"></div>

        <div class="mx-auto w-full max-w-7xl px-6 pb-20 pt-40 lg:px-8 lg:pb-28">
            <p class="text-xs font-semibold uppercase tracking-[0.4em] text-gold">Aberdeen, Sierra Leone</p>
            <h1 class="mt-5 max-w-4xl font-serif text-5xl leading-[1.05] sm:text-7xl">{{ $heroTitle }}</h1>
            @if ($heroSubtitle)
                <p class="mt-6 max-w-xl text-lg text-ivory/80">{{ $heroSubtitle }}</p>
            @endif
            <div class="mt-10 flex flex-wrap gap-4">
                <x-button-link href="#residences" class="!bg-gold !text-navy hover:!bg-ivory">
                    Discover the residences
                </x-button-link>
                <x-button-link href="#contact" variant="outline"
                    class="!border-ivory/40 !text-ivory hover:!border-gold hover:!text-gold">
                    Get in touch
                </x-button-link>
            </div>
        </div>
    </section>

    {{-- Penthouse showcase: the first residence content, so it takes the #residences anchor --}}
    @if ($featured)
        <section id="residences"
            class="relative isolate scroll-mt-20 overflow-hidden bg-navy py-24 text-ivory sm:py-32">
            @if ($featuredCover)
                <img src="{{ $featuredCover->url }}" alt="" aria-hidden="true" loading="lazy"
                    class="absolute inset-0 -z-10 h-full w-full object-cover opacity-25">
            @endif
            <div class="absolute inset-0 -z-10 bg-gradient-to-b from-navy via-navy/85 to-navy"></div>

            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <p class="text-xs font-semibold uppercase tracking-[0.4em] text-gold">Featured Residence</p>
                <h2 class="mt-4 max-w-4xl font-serif text-5xl leading-[1.05] sm:text-7xl">{{ $featured->name }}</h2>
                @if ($featured->tagline)
                    <p class="mt-5 max-w-2xl font-serif text-2xl text-gold">{{ $featured->tagline }}</p>
                @endif

                {{-- Photo mosaic --}}
                @if ($featuredCover)
                    <div class="mt-14 grid grid-cols-2 gap-4 lg:h-[34rem] lg:grid-cols-3 lg:grid-rows-2">
                        <a href="{{ route('residences.show', $featured) }}" @class([
                            'group relative col-span-2 aspect-[4/3] overflow-hidden bg-navy ring-1 ring-gold/30 lg:aspect-auto lg:row-span-2',
                            'lg:col-span-2' => $featuredExtras->isNotEmpty(),
                            'lg:col-span-3' => $featuredExtras->isEmpty(),
                        ])>
                            <img src="{{ $featuredCover->url }}"
                                alt="{{ $featuredCover->alt_text ?: $featured->name }}" loading="lazy"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                        </a>

                        @foreach ($featuredExtras as $extra)
                            <a href="{{ route('residences.show', $featured) }}" @class([
                                'group relative aspect-[4/3] overflow-hidden bg-navy ring-1 ring-gold/30 lg:aspect-auto',
                                'col-span-2 lg:col-span-1 lg:row-span-2' => $featuredExtras->count() === 1,
                            ])>
                                <img src="{{ $extra->thumb_url }}" alt="{{ $extra->alt_text ?: $featured->name }}"
                                    loading="lazy"
                                    class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="mt-16 grid gap-14 lg:grid-cols-2">
                    <div>
                        @if ($featuredExcerpt)
                            <p class="text-lg leading-relaxed text-ivory/80">{{ $featuredExcerpt }}</p>
                        @endif

                        <x-residence-stats :residence="$featured" class="mt-10" />

                        <div class="mt-10 flex flex-wrap gap-4">
                            <x-button-link :href="route('residences.show', $featured)" class="!bg-gold !text-navy hover:!bg-ivory">Explore this
                                residence</x-button-link>
                            <x-button-link href="#contact" variant="outline"
                                class="!border-ivory/40 !text-ivory hover:!border-gold hover:!text-gold">Enquire</x-button-link>
                        </div>
                    </div>

                    @if ($highlights->isNotEmpty())
                        <div>
                            <h3 class="border-b border-gold/40 pb-3 font-serif text-2xl">Highlights</h3>
                            <ul class="mt-6 grid gap-x-8 gap-y-4 text-sm text-ivory/80 sm:grid-cols-2">
                                @foreach ($highlights as $feature)
                                    <li class="flex gap-3">
                                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-gold" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="m5 12 5 5 9-10" />
                                        </svg>
                                        <span>{{ $feature->title }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- The other residences: with no featured residence, this section takes the anchor --}}
    @if ($residences->isNotEmpty() || !$featured)
        <section id="{{ $featured ? 'more-residences' : 'residences' }}" class="scroll-mt-24 py-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <x-section-heading eyebrow="Residences" title="Choose Your Residence" />

                @if ($residences->isEmpty())
                    <p class="mt-12 text-charcoal/60 dark:text-ivory/60">Residences will appear here soon.</p>
                @else
                    <div class="mt-14 grid gap-10 md:grid-cols-2">
                        @foreach ($residences as $residence)
                            @include('site._residence-card', ['residence' => $residence])
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- Text sections: only the ones that exist, alternating left and right --}}
    @foreach ($story as $item)
        <section id="{{ $item['slug'] }}" @class([
            'scroll-mt-24 py-24',
            'bg-white dark:bg-white/[0.03]' => $loop->even,
        ])>
            <div @class([
                'mx-auto grid max-w-7xl items-center gap-12 px-6 lg:gap-20 lg:px-8',
                'lg:grid-cols-2' => $item['image'],
            ])>
                <div @class(['max-w-2xl', 'lg:order-2' => $loop->even && $item['image']])>
                    <x-section-heading :eyebrow="$item['eyebrow']" :title="$item['section']->title" />
                    @if ($item['section']->subtitle)
                        <p class="mt-6 font-serif text-2xl text-gold">{{ $item['section']->subtitle }}</p>
                    @endif
                    <div class="mt-6 space-y-5 text-charcoal/80 dark:text-ivory/75">
                        <x-prose :text="$item['section']->body" />
                    </div>
                </div>

                @if ($item['image'])
                    <img src="{{ $item['image']->url }}"
                        alt="{{ $item['image']->alt_text ?: $item['section']->title }}" loading="lazy"
                        class="aspect-[4/3] w-full object-cover shadow-xl">
                @endif
            </div>
        </section>
    @endforeach

    {{-- Amenities --}}
    @if ($amenities->isNotEmpty())
        <section id="amenities" class="scroll-mt-24 bg-navy py-24 text-ivory">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-2xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-gold">Amenities</p>
                    <h2 class="mt-3 font-serif text-4xl leading-tight sm:text-5xl">Everything within reach</h2>
                    <div class="mt-5 h-px w-16 bg-gold"></div>
                </div>

                <div class="mt-14 grid gap-x-10 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($amenities as $amenity)
                        <div class="border-t border-gold/40 pt-8">
                            <span
                                class="flex h-14 w-14 items-center justify-center rounded-full border border-gold/50 text-gold">
                                <x-amenity-icon :name="$amenity->icon" class="h-7 w-7" />
                            </span>
                            <h3 class="mt-6 font-serif text-2xl">{{ $amenity->title }}</h3>
                            @if ($amenity->description)
                                <p class="mt-3 text-sm text-ivory/70">{{ $amenity->description }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-site-layout>
