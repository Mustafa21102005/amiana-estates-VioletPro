<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center bg-navy px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-ivory transition duration-200 hover:bg-gold hover:text-navy focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 dark:bg-gold dark:text-navy dark:hover:bg-ivory dark:focus:ring-offset-navy']) }}>
    {{ $slot }}
</button>
