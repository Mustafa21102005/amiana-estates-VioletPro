<x-app-layout>
    <x-slot name="title">Amenities</x-slot>

    <div x-data="{
        editing: @js(old('_editing')),
        title: @js(old('title', '')),
        description: @js(old('description', '')),
        icon: @js(old('icon', 'star')),
        sort_order: @js(old('sort_order', $nextOrder)),
        nextOrder: @js($nextOrder),
        storeUrl: @js(route('admin.amenities.store')),
        updateUrl: @js(route('admin.amenities.update', '__id__')),
        get action() {
            return this.editing ? this.updateUrl.replace('__id__', this.editing) : this.storeUrl;
        },
        openCreate() {
            this.editing = null;
            this.title = '';
            this.description = '';
            this.icon = 'star';
            this.sort_order = this.nextOrder;
            this.$dispatch('open-modal', 'amenity-form');
        },
        openEdit(amenity) {
            this.editing = amenity.id;
            this.title = amenity.title;
            this.description = amenity.description ?? '';
            this.icon = amenity.icon || 'star';
            this.sort_order = amenity.sort_order;
            this.$dispatch('open-modal', 'amenity-form');
        },
    }" x-init="if (@js($errors->any())) $nextTick(() => $dispatch('open-modal', 'amenity-form'))">

        <div class="flex flex-wrap items-center justify-between gap-4">
            <p class="max-w-xl text-sm text-charcoal/70 dark:text-ivory/70">
                The estate's amenities, shown in their own section on the website, such as security, parking or the spa.
            </p>
            <x-primary-button type="button" x-on:click="openCreate()">Add amenity</x-primary-button>
        </div>

        <div class="mt-6 overflow-x-auto bg-white ring-1 ring-mist/20 dark:bg-white/5 dark:ring-white/10">
            @if ($amenities->isEmpty())
                <p class="px-6 py-12 text-center text-sm text-charcoal/60 dark:text-ivory/60">
                    No amenities yet. Add your first one and the Amenities section will appear on the website.
                </p>
            @else
                <table class="min-w-full text-left text-sm">
                    <thead
                        class="border-b border-mist/20 text-xs uppercase tracking-widest text-mist dark:border-white/10">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Order</th>
                            <th class="px-6 py-4 font-semibold">Amenity</th>
                            <th class="hidden px-6 py-4 font-semibold md:table-cell">Description</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-mist/10 dark:divide-white/10">
                        @foreach ($amenities as $amenity)
                            <tr>
                                <td class="px-6 py-4">{{ $amenity->sort_order }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <span
                                            class="flex h-10 w-10 shrink-0 items-center justify-center border border-mist/40 text-navy dark:border-white/15 dark:text-gold">
                                            <x-amenity-icon :name="$amenity->icon" class="h-5 w-5" />
                                        </span>
                                        <span class="font-medium text-navy dark:text-ivory">{{ $amenity->title }}</span>
                                    </div>
                                </td>
                                <td class="hidden max-w-md px-6 py-4 text-charcoal/70 md:table-cell dark:text-ivory/70">
                                    {{ \Illuminate\Support\Str::limit($amenity->description, 90) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div
                                        class="flex items-center justify-end gap-5 text-xs font-semibold uppercase tracking-[0.18em]">
                                        <button type="button" x-on:click="openEdit(@js(['id' => $amenity->id, 'title' => $amenity->title, 'description' => $amenity->description, 'icon' => $amenity->icon, 'sort_order' => $amenity->sort_order]))"
                                            class="text-navy transition hover:text-gold dark:text-ivory">Edit</button>

                                        <button type="button"
                                            x-on:click="$dispatch('confirm-delete', {
                                                action: @js(route('admin.amenities.destroy', $amenity)),
                                                title: 'Delete amenity?',
                                                message: @js('“' . $amenity->title . '” will be permanently removed from the website.'),
                                            })"
                                            class="text-red-600 transition hover:text-red-400">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="{{ $amenities->hasPages() ? 'px-5 py-2' : '' }}">
                    {{ $amenities->links() }}
                </div>
            @endif
        </div>

        {{-- One modal, used for both creating and editing --}}
        <x-modal name="amenity-form" maxWidth="lg" focusable>
            <form method="POST" x-bind:action="action" class="p-6 sm:p-8">
                @csrf
                <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editing">
                <input type="hidden" name="_editing" x-bind:value="editing" x-bind:disabled="!editing">

                <h2 class="font-serif text-2xl text-navy dark:text-ivory"
                    x-text="editing ? 'Edit amenity' : 'Add amenity'"></h2>
                <div class="mt-4 h-px w-10 bg-gold"></div>

                <div class="mt-6 space-y-6">
                    <div>
                        <x-input-label for="title" value="Title" />
                        <x-text-input id="title" name="title" x-model="title" maxlength="120" required
                            class="mt-2 block w-full" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-icon-select x-model="icon" />
                        <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <x-textarea id="description" name="description" x-model="description" rows="4"
                            maxlength="500" class="mt-2 block w-full"></x-textarea>
                        <p class="mt-2 text-xs text-charcoal/60 dark:text-ivory/60">
                            One or two sentences. Maximum 500 characters.
                        </p>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="sort_order" value="Display order" />
                        <x-text-input id="sort_order" type="number" name="sort_order" x-model="sort_order"
                            min="1" required class="mt-2 block w-full" />
                        <p class="mt-2 text-xs text-charcoal/60 dark:text-ivory/60">
                            1 appears first. If this number is already taken, the others move down to make room.
                        </p>
                        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                    <x-primary-button>
                        <span x-text="editing ? 'Save changes' : 'Create amenity'"></span>
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
