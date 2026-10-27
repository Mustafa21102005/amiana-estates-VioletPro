@props(['eyebrow' => null, 'title', 'center' => false])

<div {{ $attributes->class(['max-w-2xl', 'mx-auto text-center' => $center]) }}>
    @if ($eyebrow)
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-gold">{{ $eyebrow }}</p>
    @endif
    <h2 class="mt-3 font-serif text-4xl leading-tight text-navy sm:text-5xl dark:text-ivory">{{ $title }}</h2>
    <div @class(['mt-5 h-px w-16 bg-gold', 'mx-auto' => $center])></div>
</div>
