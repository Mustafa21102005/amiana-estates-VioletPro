<x-site-layout :title="$propertyType->name" :description="$metaDescription" :image="$cover?->url">
    {{-- Hero --}}
    <section class="relative isolate flex min-h-[75svh] items-end overflow-hidden bg-navy text-ivory">
        @if ($cover)
            <img src="{{ $cover->url }}" alt="{{ $cover->alt_text ?: $propertyType->name }}" fetchpriority="high"
                class="absolute inset-0 -z-10 h-full w-full object-cover">
        @endif
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-navy via-navy/40 to-navy/60"></div>

        <div class="mx-auto w-full max-w-7xl px-6 pb-16 pt-40 lg:px-8">
            @if ($propertyType->is_featured)
                <p class="text-xs font-semibold uppercase tracking-[0.4em] text-gold">Featured Residence</p>
            @endif
            <h1 class="mt-4 max-w-4xl font-serif text-5xl leading-[1.05] sm:text-6xl">{{ $propertyType->name }}</h1>
            @if ($propertyType->tagline)
                <p class="mt-5 max-w-2xl font-serif text-2xl text-gold">{{ $propertyType->tagline }}</p>
            @endif
        </div>
    </section>

    {{-- Key facts --}}
    <section class="bg-navy py-10 text-ivory">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-6 lg:px-8">
            <x-residence-stats :residence="$propertyType" />
            <x-button-link href="#contact" class="!bg-gold !text-navy hover:!bg-ivory">Enquire about this
                residence</x-button-link>
        </div>
    </section>

    {{-- About --}}
    @if ($propertyType->description)
        <section class="py-24">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <x-section-heading eyebrow="The Residence" :title="$propertyType->name" />
                <div class="mt-8 space-y-5 text-lg text-charcoal/80 dark:text-ivory/75">
                    <x-prose :text="$propertyType->description" />
                </div>
            </div>
        </section>
    @endif

    {{-- Features --}}
    @if ($categories->isNotEmpty())
        <section class="bg-white py-24 dark:bg-white/[0.03]">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <x-section-heading eyebrow="Features" title="Every detail considered" />

                <div class="mt-14 grid gap-12 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($categories as $category)
                        <div>
                            <h3 class="border-b border-gold/40 pb-3 font-serif text-2xl text-navy dark:text-ivory">
                                {{ $category->name }}</h3>
                            <ul class="mt-5 space-y-3 text-sm text-charcoal/80 dark:text-ivory/75">
                                @foreach ($features->get($category->id) as $feature)
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
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Gallery with lightbox --}}
    @if ($images->isNotEmpty())
        <section class="py-24" x-data="{
            open: false,
            i: 0,
            slides: @js($slides),
            showAll: false,
            show(n) {
                this.i = n;
                this.open = true
            },
            next() { this.i = (this.i + 1) % this.slides.length },
            prev() { this.i = (this.i - 1 + this.slides.length) % this.slides.length },
            toggleAll() {
                this.showAll = !this.showAll;
        
                // collapsing a long grid would leave the visitor far below the gallery, so scroll back up to it
                if (!this.showAll) this.$refs.top.scrollIntoView({ behavior: 'smooth', block: 'start' });
            },
        }"
            x-effect="document.body.classList.toggle('overflow-hidden', open)" x-on:keydown.escape.window="open = false"
            x-on:keydown.arrow-right.window="open && next()" x-on:keydown.arrow-left.window="open && prev()">

            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div x-ref="top" class="scroll-mt-24">
                    <x-section-heading eyebrow="Gallery" title="See it for yourself" />
                </div>

                <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($images as $image)
                        <button type="button" x-on:click="show({{ $loop->index }})"
                            @if ($loop->index >= $galleryPreview) x-show="showAll" x-cloak
                                x-transition:enter="transition duration-500"
                                x-transition:enter-start="translate-y-4 opacity-0"
                                x-transition:enter-end="translate-y-0 opacity-100" @endif
                            class="group relative aspect-[4/3] overflow-hidden bg-navy focus:outline-none focus-visible:ring-2 focus-visible:ring-gold"
                            aria-label="View photo {{ $loop->iteration }} larger">
                            <img src="{{ $image->thumb_url }}" alt="{{ $image->alt_text ?: $propertyType->name }}"
                                loading="lazy"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                        </button>
                    @endforeach
                </div>

                @if ($images->count() > $galleryPreview)
                    <div class="mt-12 text-center">
                        {{-- x-bind: and not ":", because a colon on a Blade component means a PHP expression --}}
                        <x-secondary-button x-on:click="toggleAll()" x-bind:aria-expanded="showAll.toString()">
                            <span x-show="! showAll">See all {{ $images->count() }} photos</span>
                            <span x-show="showAll" x-cloak>Show fewer</span>
                        </x-secondary-button>
                    </div>
                @endif
            </div>

            {{-- Lightbox --}}
            <div x-show="open" x-cloak x-transition.opacity role="dialog" aria-modal="true"
                class="fixed inset-0 z-50 flex items-center justify-center bg-navy/95 p-4 sm:p-10"
                x-on:click.self="open = false">

                <button type="button" x-on:click="open = false" aria-label="Close"
                    class="absolute right-4 top-4 p-2 text-ivory transition hover:text-gold">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6 6 18" />
                    </svg>
                </button>

                <button type="button" x-on:click="prev()" aria-label="Previous photo" x-show="slides.length > 1"
                    class="absolute left-2 top-1/2 -translate-y-1/2 p-3 text-ivory transition hover:text-gold sm:left-6">
                    <svg class="h-9 w-9" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 5-7 7 7 7" />
                    </svg>
                </button>

                <figure class="flex max-h-full max-w-full flex-col items-center">
                    <img :src="slides[i].src" :alt="slides[i].alt" class="max-h-[80vh] max-w-full object-contain">
                    <figcaption class="mt-4 text-center text-sm text-ivory/80">
                        <span x-text="slides[i].caption"></span>
                        <span class="ms-3 text-xs text-mist" x-text="(i + 1) + ' / ' + slides.length"></span>
                    </figcaption>
                </figure>

                <button type="button" x-on:click="next()" aria-label="Next photo" x-show="slides.length > 1"
                    class="absolute right-2 top-1/2 -translate-y-1/2 p-3 text-ivory transition hover:text-gold sm:right-6">
                    <svg class="h-9 w-9" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7" />
                    </svg>
                </button>
            </div>
        </section>
    @endif

    {{-- Film --}}
    @if ($videoItems)
        <section class="bg-navy py-24 text-ivory sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-gold">Film</p>
                    <h2 class="mt-3 font-serif text-4xl sm:text-5xl">A closer look</h2>
                    <div class="mx-auto mt-5 h-px w-16 bg-gold"></div>
                </div>

                <x-video-player :videos="$videoItems" class="mt-14 max-w-6xl" />
            </div>
        </section>
    @endif

    {{-- Other residences --}}
    @if ($others->isNotEmpty())
        <section class="bg-white py-24 dark:bg-white/[0.03]">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <x-section-heading eyebrow="Residences" title="Other residences" />

                <div class="mt-14 grid gap-10 md:grid-cols-2">
                    @foreach ($others as $residence)
                        @include('site._residence-card', ['residence' => $residence])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-site-layout>
