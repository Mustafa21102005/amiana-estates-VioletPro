@props(['disabled' => false])

<select @disabled($disabled)
    {{ $attributes->merge(['class' => 'rounded-sm border-mist/60 bg-white text-charcoal shadow-sm focus:border-gold focus:ring-gold dark:border-mist/30 dark:bg-navy dark:text-ivory']) }}>{{ $slot }}</select>
