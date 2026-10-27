<button type="button" x-data="{
    dark: document.documentElement.classList.contains('dark'),
    toggle() {
        this.dark = !this.dark;
        document.documentElement.classList.toggle('dark', this.dark);
        try { localStorage.setItem('amiana.theme', this.dark ? 'dark' : 'light'); } catch (e) {}
    },
}" x-on:click="toggle()" x-bind:aria-pressed="dark.toString()"
    x-bind:title="dark ? 'Switch to light mode' : 'Switch to dark mode'" aria-label="Dark mode"
    {{ $attributes->class(['inline-flex h-10 w-10 shrink-0 items-center justify-center border border-mist/40 transition hover:border-gold hover:text-gold focus:outline-none focus-visible:ring-2 focus-visible:ring-gold']) }}>

    {{-- Moon: shown in light mode (click for dark) --}}
    <svg class="h-5 w-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
        stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M20 14.5A8 8 0 0 1 9.5 4a8 8 0 1 0 10.5 10.5Z" />
    </svg>

    {{-- Sun: shown in dark mode (click for light) --}}
    <svg class="hidden h-5 w-5 dark:block" fill="none" stroke="currentColor" stroke-width="1.5"
        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
        <circle cx="12" cy="12" r="4" />
        <path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" />
    </svg>
</button>
