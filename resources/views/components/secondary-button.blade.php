<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center border border-navy/30 px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-navy transition duration-200 hover:border-gold hover:text-gold focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 dark:border-ivory/30 dark:text-ivory dark:hover:border-gold dark:hover:text-gold dark:focus:ring-offset-navy']) }}>
    {{ $slot }}
</button>
