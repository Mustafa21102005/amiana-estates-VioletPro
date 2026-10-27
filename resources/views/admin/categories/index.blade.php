<x-app-layout>
    <x-slot name="title">Feature Categories</x-slot>

    <div x-data="{
        editing: @js(old('_editing')),
        name: @js(old('name', '')),
        sort_order: @js(old('sort_order', $nextOrder)),
        nextOrder: @js($nextOrder),
        storeUrl: @js(route('admin.categories.store')),
        updateUrl: @js(route('admin.categories.update', '__slug__')),
        get action() {
            return this.editing ? this.updateUrl.replace('__slug__', this.editing) : this.storeUrl;
        },
        openCreate() {
            this.editing = null;
            this.name = '';
            this.sort_order = this.nextOrder;
            this.$dispatch('open-modal', 'category-form');
        },
        openEdit(category) {
            this.editing = category.slug;
            this.name = category.name;
            this.sort_order = category.sort_order;
            this.$dispatch('open-modal', 'category-form');
        },
    }" x-init="if (@js($errors->any())) $nextTick(() => $dispatch('open-modal', 'category-form'))">

        <div class="flex flex-wrap items-center justify-between gap-4">
            <p class="max-w-xl text-sm text-charcoal/70 dark:text-ivory/70">
                Headings used to group a property's features on the website, such as "Interior Features" or "Outdoor
                Luxury".
            </p>
            <x-primary-button type="button" x-on:click="openCreate()">Add category</x-primary-button>
        </div>

        <div class="mt-6 overflow-x-auto bg-white ring-1 ring-mist/20 dark:bg-white/5 dark:ring-white/10">
            @if ($categories->isEmpty())
                <p class="px-6 py-12 text-center text-sm text-charcoal/60 dark:text-ivory/60">
                    No categories yet. Add your first one to start grouping features.
                </p>
            @else
                <table class="min-w-full text-left text-sm">
                    <thead
                        class="border-b border-mist/20 text-xs uppercase tracking-widest text-mist dark:border-white/10">
                        <tr>
                            <th class="px-6 py-4 text-center">Order</th>
                            <th class="px-6 py-4 text-center">Name</th>
                            <th class="px-6 py-4 text-center">Features</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-mist/10 dark:divide-white/10">
                        @foreach ($categories as $category)
                            <tr>
                                <td class="px-6 py-4 text-center">{{ $category->sort_order }}</td>
                                <td class="px-6 py-4 font-medium text-navy dark:text-ivory text-center">
                                    {{ $category->name }}
                                </td>
                                <td class="px-6 py-4 text-center">{{ $category->features_count }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div
                                        class="flex items-center justify-center gap-5 text-xs font-semibold uppercase tracking-[0.18em]">
                                        <button type="button" x-on:click="openEdit(@js(['slug' => $category->slug, 'name' => $category->name, 'sort_order' => $category->sort_order]))"
                                            class="text-navy transition hover:text-gold dark:text-ivory">
                                            Edit
                                        </button>

                                        @if ($category->features_count === 0)
                                            <button type="button"
                                                x-on:click="$dispatch('confirm-delete', {
                                                    action: @js(route('admin.categories.destroy', $category)),
                                                    title: 'Delete category?',
                                                    message: @js('“' . $category->name . '” will be permanently removed.'),
                                                })"
                                                class="text-red-600 transition hover:text-red-400">
                                                Delete
                                            </button>
                                        @else
                                            <span class="text-mist" title="Move or delete its features first">
                                                In use
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="{{ $categories->hasPages() ? 'px-5 py-2' : '' }}">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

        {{-- One modal, used for both creating and editing --}}
        <x-modal name="category-form" maxWidth="md" focusable>
            <form method="POST" x-bind:action="action" class="p-6 sm:p-8">
                @csrf
                <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editing">
                <input type="hidden" name="_editing" x-bind:value="editing" x-bind:disabled="!editing">

                <h2 class="font-serif text-2xl text-navy dark:text-ivory"
                    x-text="editing ? 'Edit category' : 'Add category'"></h2>
                <div class="mt-4 h-px w-10 bg-gold"></div>

                <div class="mt-6 space-y-6">
                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" x-model="name" maxlength="100" required
                            class="mt-2 block w-full" />
                        <p class="mt-2 text-xs text-charcoal/60 dark:text-ivory/60">
                            Shown as a heading on the website, e.g. Interior Features.
                        </p>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="sort_order" value="Display order" />
                        <x-text-input id="sort_order" type="number" name="sort_order" x-model="sort_order"
                            min="1" required class="mt-2 block w-full" />
                        <p class="mt-2 text-xs text-charcoal/60 dark:text-ivory/60">
                            1 appears first. If this number is already taken, the other categories move down to make
                            room.
                        </p>
                        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                    <x-primary-button>
                        <span x-text="editing ? 'Save changes' : 'Create category'"></span>
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
