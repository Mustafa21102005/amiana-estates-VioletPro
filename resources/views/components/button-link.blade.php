@props(['variant' => 'primary'])

<a
    {{ $attributes->class([
        'inline-flex items-center justify-center px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] transition duration-200 focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 dark:focus:ring-offset-navy',
        'bg-navy text-ivory hover:bg-gold hover:text-navy dark:bg-gold dark:text-navy dark:hover:bg-ivory' =>
            $variant === 'primary',
        'border border-navy/30 text-navy hover:border-gold hover:text-gold dark:border-ivory/30 dark:text-ivory dark:hover:border-gold dark:hover:text-gold' =>
            $variant === 'outline',
    ]) }}>
    {{ $slot }}
</a>
