<form method="POST"
    action="{{ $propertyType->exists ? route('admin.property-types.update', $propertyType) : route('admin.property-types.store') }}"
    class="space-y-6">
    @csrf
    @if ($propertyType->exists)
        @method('PUT')
    @endif

    <div>
        <x-form-field name="name" label="Name" :value="$propertyType->name" placeholder="e.g. Ultra-Luxury Duplex Penthouse" />
        @if ($propertyType->exists)
            <p class="mt-2 text-xs text-charcoal/60 dark:text-ivory/60">
                Web address name: <span class="font-medium">{{ $propertyType->slug }}</span> (stays the same if you
                rename this).
            </p>
        @endif
    </div>

    <x-form-field name="tagline" label="Tagline" :value="$propertyType->tagline"
        hint="One line under the name, e.g. The Crown Jewel of Amiana Estates." />

    <x-form-field name="description" label="Description" :textarea="true" :rows="8" :value="$propertyType->description"
        hint="Line breaks are kept on the website. Maximum 5000 characters." />

    <div class="grid gap-6 sm:grid-cols-3">
        <x-form-field name="size_sqm" label="Size (m²)" type="number" :value="$propertyType->size_sqm" />
        <x-form-field name="bedrooms" label="Bedrooms" type="number" :value="$propertyType->bedrooms" />
        <x-form-field name="units_count" label="Number of units" type="number" :value="$propertyType->units_count" />
    </div>

    <x-form-field name="sort_order" label="Display order" type="number" :value="$propertyType->sort_order"
        hint="1 appears first. If this number is already taken, the others move down to make room." />

    <label for="is_featured" class="flex items-start gap-3">
        <input id="is_featured" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $propertyType->is_featured))
            class="mt-1 rounded-sm border-mist/60 text-navy focus:ring-gold dark:border-mist/40 dark:bg-white/5 dark:text-gold">
        <span>
            <span class="block text-xs font-semibold uppercase tracking-widest text-navy dark:text-ivory">Featured
                residence</span>
            <span class="mt-1 block text-xs text-charcoal/60 dark:text-ivory/60">
                Receives the premium showcase on the website. Only one property type can be featured, so ticking this
                unticks the others.
            </span>
        </span>
    </label>

    <div class="flex items-center justify-end gap-3 pt-2">
        <x-button-link variant="outline" :href="route('admin.property-types.index')">Cancel</x-button-link>
        <x-primary-button>{{ $propertyType->exists ? 'Save changes' : 'Create property type' }}</x-primary-button>
    </div>
</form>
