@props(['disabled' => false])

<textarea @disabled($disabled)
    {{ $attributes->merge(['class' => 'rounded-sm border-mist/60 bg-white text-charcoal shadow-sm focus:border-gold focus:ring-gold dark:border-mist/30 dark:bg-white/5 dark:text-ivory dark:placeholder-mist/60']) }}>{{ $slot }}</textarea>
