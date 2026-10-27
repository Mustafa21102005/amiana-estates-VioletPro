<x-app-layout>
    <x-slot name="title">Property Types</x-slot>

    <div class="flex flex-wrap items-center justify-between gap-4">
        <p class="max-w-xl text-sm text-charcoal/70 dark:text-ivory/70">
            The residences shown on the website: the penthouse and the apartment types.
        </p>
        <x-button-link :href="route('admin.property-types.create')">Add property type</x-button-link>
    </div>

    <div x-data class="mt-6 overflow-x-auto bg-white ring-1 ring-mist/20 dark:bg-white/5 dark:ring-white/10">
        @if ($propertyTypes->isEmpty())
            <p class="px-6 py-12 text-center text-sm text-charcoal/60 dark:text-ivory/60">
                No property types yet. Add your first one.
            </p>
        @else
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-mist/20 text-xs uppercase tracking-widest text-mist dark:border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-center">Order</th>
                        <th class="px-6 py-4 text-center">Name</th>
                        <th class="px-6 py-4 text-center">Size</th>
                        <th class="px-6 py-4 text-center">Beds</th>
                        <th class="px-6 py-4 text-center">Units</th>
                        <th class="px-6 py-4 text-center">Features</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-mist/10 dark:divide-white/10">
                    @foreach ($propertyTypes as $propertyType)
                        <tr>
                            <td class="px-6 py-4 text-center">{{ $propertyType->sort_order }}</td>
                            <td class="px-6 py-4 text-center text-navy dark:text-ivory">
                                {{ $propertyType->name }}
                                @if ($propertyType->is_featured)
                                    <span
                                        class="ms-2 border border-gold px-2 py-0.5 text-[0.65rem] uppercase tracking-widest text-gold">
                                        Featured
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                {{ $propertyType->size_sqm ? $propertyType->size_sqm . ' m²' : '—' }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $propertyType->bedrooms }}</td>
                            <td class="px-6 py-4 text-center">{{ $propertyType->units_count }}</td>
                            <td class="px-6 py-4 text-center">{{ $propertyType->features_count }}</td>
                            <td class="px-6 py-4 text-center">
                                <div
                                    class="flex items-center justify-center gap-5 text-xs font-semibold uppercase tracking-[0.18em]">
                                    <a href="{{ route('admin.property-types.edit', $propertyType) }}"
                                        class="text-navy transition hover:text-gold dark:text-ivory">
                                        Edit
                                    </a>

                                    @if ($canDelete)
                                        <button type="button"
                                            x-on:click="$dispatch('confirm-delete', {
                                                action: @js(route('admin.property-types.destroy', $propertyType)),
                                                title: 'Delete property type?',
                                                message: @js('“' . $propertyType->name . '” and its ' . $propertyType->features_count . ' ' . \Illuminate\Support\Str::plural('feature', $propertyType->features_count) . ' will be permanently removed.'),
                                            })"
                                            class="text-red-600 transition hover:text-red-400">Delete</button>
                                    @else
                                        <span class="text-mist"
                                            title="At least one property type must remain">Required</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="{{ $propertyTypes->hasPages() ? 'px-5 py-2' : '' }}">
                {{ $propertyTypes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
