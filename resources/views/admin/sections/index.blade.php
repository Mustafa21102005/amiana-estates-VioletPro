<x-app-layout>
    <x-slot name="title">Content Sections</x-slot>

    <p class="max-w-2xl text-sm text-charcoal/70 dark:text-ivory/70">
        The text blocks of the home page, listed in the order they appear.
    </p>

    @if ($missing)
        <div class="mt-6 border-l-4 border-red-500 bg-red-500/10 px-4 py-3 text-sm">
            Missing from the database: {{ implode(', ', $missing) }}. Re-run your content sections seeder to restore
            them.
        </div>
    @endif

    <div class="mt-6 overflow-x-auto bg-white ring-1 ring-mist/20 dark:bg-white/5 dark:ring-white/10">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-mist/20 text-xs uppercase tracking-widest text-mist dark:border-white/10">
                <tr>
                    <th class="px-6 py-4 text-center">Section</th>
                    <th class="hidden px-6 py-4 text-center md:table-cell">Where it appears</th>
                    <th class="px-6 py-4 text-center">Media</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-mist/10 dark:divide-white/10">
                @foreach ($sections as $section)
                    <tr>
                        <td class="px-6 py-4 text-center">
                            <p class="font-medium text-navy dark:text-ivory">{{ $section->label() }}</p>
                            <p class="mt-1 max-w-xs truncate text-xs text-charcoal/60 dark:text-ivory/60">
                                {{ $section->title }}
                            </p>
                        </td>
                        <td class="hidden px-6 py-4 text-charcoal/70 md:table-cell dark:text-ivory/70 text-center">
                            {{ $section->placement() }}</td>
                        <td class="px-6 py-4 text-center">{{ $section->media_count }}</td>
                        <td class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-[0.18em]">
                            <a href="{{ route('admin.sections.edit', $section) }}"
                                class="text-navy transition hover:text-gold dark:text-ivory">
                                Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="{{ $sections->hasPages() ? 'px-5 py-2' : '' }}">
            {{ $sections->links() }}
        </div>
    </div>
</x-app-layout>
