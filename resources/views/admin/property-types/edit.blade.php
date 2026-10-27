<x-app-layout>
    <x-slot name="title">Edit Property Type</x-slot>

    <div class="space-y-8">
        <a href="{{ route('admin.property-types.index') }}"
            class="inline-block text-xs font-semibold uppercase tracking-[0.18em] text-navy/70 transition hover:text-gold dark:text-ivory/70">
            &larr; All property types
        </a>

        <x-card>
            @include('admin.property-types._form')
        </x-card>

        <x-media-gallery :owner="$propertyType" />

        @include('admin.property-types._features')
    </div>
</x-app-layout>
