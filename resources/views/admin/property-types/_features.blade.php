<x-card id="features">
    <h2 class="font-serif text-2xl text-navy dark:text-ivory">Features</h2>
    <div class="mt-4 h-px w-10 bg-gold"></div>
    <p class="mt-4 text-sm text-charcoal/70 dark:text-ivory/70">
        The highlights listed on this residence's page, grouped by category.
    </p>

    {{-- Errors from the inline edit forms --}}
    @if ($errors->featureUpdate->any())
        <div class="mt-4 border-l-4 border-red-500 bg-red-500/10 px-4 py-3 text-sm">
            @foreach ($errors->featureUpdate->all() as $message)
                <p>{{ $message }}</p>
            @endforeach
        </div>
    @endif

    @if ($categories->isEmpty())
        <p class="mt-6 text-sm">
            There are no categories yet.
            <a href="{{ route('admin.categories.index') }}" class="font-semibold text-gold underline">
                Go to Feature Categories
            </a>
            to create one, then come back to add features.
        </p>
    @else
        {{-- Add several features at once --}}
        <form method="POST" action="{{ route('admin.property-types.features.store', $propertyType) }}"
            class="mt-6 space-y-5 border border-mist/20 p-5 dark:border-white/10">
            @csrf

            <div>
                <x-input-label for="category_id" value="Category" />
                <x-select id="category_id" name="category_id" class="mt-2 block w-full sm:w-72">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id') === $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </x-select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            <x-form-field name="titles" label="Features" :textarea="true" :rows="5"
                placeholder="One feature per line"
                hint="Add several at once: put each feature on its own line. Pasted bullet points are cleaned up for you." />

            <x-primary-button>Add features</x-primary-button>
        </form>

        {{-- Existing features, grouped by category --}}
        <div class="mt-8 space-y-8">
            @if ($features->isEmpty())
                <p class="text-sm text-charcoal/60 dark:text-ivory/60">No features yet.</p>
            @endif

            @foreach ($categories as $category)
                @php($items = $features->get($category->id, collect()))
                @continue($items->isEmpty())

                <section>
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-gold">{{ $category->name }}</h3>

                    <ul class="mt-3 divide-y divide-mist/10 dark:divide-white/10">
                        @foreach ($items as $feature)
                            <li x-data="{ editing: false }" class="py-3">

                                {{-- Reading mode --}}
                                <div x-show="! editing" class="flex items-center justify-between gap-4">
                                    <span class="text-sm">
                                        <span class="me-2 text-xs text-mist">{{ $feature->sort_order }}.</span>
                                        {{ $feature->title }}
                                    </span>

                                    <div
                                        class="flex shrink-0 items-center gap-5 text-xs font-semibold uppercase tracking-[0.18em]">
                                        <button type="button" @click="editing = true"
                                            class="text-navy transition hover:text-gold dark:text-ivory">
                                            Edit
                                        </button>

                                        <button type="button"
                                            x-on:click="$dispatch('confirm-delete', {
                                                action: @js(route('admin.features.destroy', $feature)),
                                                title: 'Delete feature?',
                                                message: @js('“' . $feature->title . '” will be removed from this residence.'),
                                            })"
                                            class="text-red-600 transition hover:text-red-400">
                                            Delete
                                        </button>
                                    </div>
                                </div>

                                {{-- Editing mode --}}
                                <form x-show="editing" x-cloak method="POST"
                                    action="{{ route('admin.features.update', $feature) }}"
                                    class="grid gap-3 sm:grid-cols-[1fr_12rem_6rem_auto] sm:items-center">
                                    @csrf
                                    @method('PUT')

                                    <x-text-input name="title" :value="$feature->title" required aria-label="Feature title"
                                        class="block w-full" />

                                    <x-select name="category_id" aria-label="Category" class="block w-full">
                                        @foreach ($categories as $option)
                                            <option value="{{ $option->id }}" @selected($option->id === $feature->category_id)>
                                                {{ $option->name }}</option>
                                        @endforeach
                                    </x-select>

                                    <x-text-input type="number" name="sort_order" :value="$feature->sort_order" min="1"
                                        aria-label="Position in category" class="block w-full" />

                                    <div
                                        class="flex items-center gap-4 text-xs font-semibold uppercase tracking-[0.18em]">
                                        <button type="submit"
                                            class="text-gold transition hover:text-navy dark:hover:text-ivory">
                                            Save
                                        </button>
                                        <button type="button" @click="editing = false"
                                            class="text-mist transition hover:text-navy dark:hover:text-ivory">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>
    @endif
</x-card>
