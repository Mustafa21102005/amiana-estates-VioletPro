<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center bg-red-700 px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white transition duration-200 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-navy']) }}>
    {{ $slot }}
</button>
