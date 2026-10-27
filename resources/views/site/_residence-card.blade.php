@php($cover = $residence->cover_image)

<a href="{{ route('residences.show', $residence) }}"
    class="group block bg-white ring-1 ring-mist/20 transition hover:ring-gold/60 dark:bg-white/5 dark:ring-white/10">
    <div class="relative aspect-[4/3] overflow-hidden bg-navy">
        @if ($cover)
            <img src="{{ $cover->thumb_url }}" alt="{{ $cover->alt_text ?: $residence->name }}" loading="lazy"
                class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
        @endif
    </div>
    <div class="p-6 sm:p-8">
        <h3 class="font-serif text-3xl text-navy dark:text-ivory">{{ $residence->name }}</h3>
        @if ($residence->tagline)
            <p class="mt-2 text-sm text-charcoal/70 dark:text-ivory/70">{{ $residence->tagline }}</p>
        @endif
        <x-residence-stats :residence="$residence" class="mt-6 text-navy dark:text-ivory" />
        <span class="mt-6 inline-block text-xs font-semibold uppercase tracking-[0.2em] text-gold">
            View residence &rarr;
        </span>
    </div>
</a>
