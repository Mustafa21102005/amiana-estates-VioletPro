@props(['label', 'value', 'note' => null, 'href' => null, 'highlight' => false])

@php
    // A card is a link when it has somewhere to go, and a plain box when it does not
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif
    {{ $attributes->class([
        'block p-6 shadow-sm ring-1 transition sm:p-8',
        'bg-white ring-mist/20 dark:bg-white/5 dark:ring-white/10' => !$highlight,
        'bg-gold/10 ring-gold/60' => $highlight,
        'hover:ring-gold/60' => $href,
    ]) }}>
    <p class="text-xs font-semibold uppercase tracking-widest text-mist">{{ $label }}</p>
    <p class="mt-3 font-serif text-5xl text-navy dark:text-gold">{{ $value }}</p>

    @if ($note)
        <p class="mt-2 text-xs text-charcoal/60 dark:text-ivory/60">{{ $note }}</p>
    @endif
    </{{ $tag }}>
