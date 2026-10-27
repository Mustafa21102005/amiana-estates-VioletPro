<x-app-layout>
    <x-slot name="title">{{ $section->label() }}</x-slot>

    <div class="space-y-8">
        <a href="{{ route('admin.sections.index') }}"
            class="inline-block text-xs font-semibold uppercase tracking-[0.18em] text-navy/70 transition hover:text-gold dark:text-ivory/70">
            &larr; All sections
        </a>

        <x-card>
            <p class="text-xs font-semibold uppercase tracking-widest text-gold">{{ $section->placement() }}</p>

            <form method="POST" action="{{ route('admin.sections.update', $section) }}" class="mt-6 space-y-6">
                @csrf
                @method('PUT')

                <x-form-field name="title" label="Title" :value="$section->title" />

                <x-form-field name="subtitle" label="Subtitle" :value="$section->subtitle"
                    hint="A short line under the title. Optional." />

                @if ($section->slug === 'hero')
                    <p class="text-sm text-charcoal/70 dark:text-ivory/70">
                        The hero shows the title and subtitle over a background photo or video, which you set below.
                    </p>
                @else
                    <x-form-field name="body" label="Text" :textarea="true" :rows="12" :value="$section->body"
                        hint="Leave a blank line between paragraphs." />
                @endif

                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-button-link variant="outline" :href="route('admin.sections.index')">Back</x-button-link>
                    <x-primary-button>Save section</x-primary-button>
                </div>
            </form>
        </x-card>

        <x-media-gallery :owner="$section" />
    </div>
</x-app-layout>
