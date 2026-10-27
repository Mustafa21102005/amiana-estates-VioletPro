<x-card id="gallery" x-data="{}">
    <h2 class="font-serif text-2xl text-navy dark:text-ivory">{{ $heading }}</h2>
    <div class="mt-4 h-px w-10 bg-gold"></div>
    <p class="mt-4 text-sm text-charcoal/70 dark:text-ivory/70">
        @if ($single)
            This is the only {{ $noun }} shown for this spot. Uploading a new one replaces the current one.
        @else
            The first photo is the cover shown on the website. Photos are resized automatically, so you can upload them
            straight from the camera.
        @endif
    </p>

    {{-- Upload --}}
    <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" x-data="uploadForm(@js($limits))"
        x-on:submit="submit($event)" class="mt-6 space-y-4 border border-mist/20 p-5 dark:border-white/10">
        @csrf
        <input type="hidden" name="owner_type" value="{{ $owner->getMorphClass() }}">
        <input type="hidden" name="owner_id" value="{{ $owner->getKey() }}">

        <div>
            <x-input-label for="files" :value="$inputLabel" />
            <input id="files" type="file" name="files[]" accept="{{ $accept }}"
                @unless ($single) multiple @endunless x-on:change="check($event.target.files)"
                class="mt-2 block w-full text-sm file:mr-4 file:cursor-pointer file:border-0 file:bg-navy file:px-5 file:py-3 file:text-xs file:font-semibold file:uppercase file:tracking-[0.2em] file:text-ivory hover:file:bg-gold hover:file:text-navy dark:file:bg-gold dark:file:text-navy">
            <p class="mt-2 text-xs text-charcoal/60 dark:text-ivory/60">{{ $hint }}</p>

            {{-- Files over the limit, found in the browser before anything is uploaded --}}
            <template x-for="(problem, i) in problems" :key="i">
                <p class="mt-2 text-sm text-red-600 dark:text-red-400" x-text="problem"></p>
            </template>

            {{-- Errors sent back by the server --}}
            @foreach ($uploadErrors as $message)
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @endforeach
        </div>

        <x-primary-button x-bind:disabled="busy || count === 0 || problems.length > 0">
            <span x-text="busy ? 'Uploading…' : @js($buttonLabel)">{{ $buttonLabel }}</span>
        </x-primary-button>
    </form>

    {{-- Errors from the per-file forms --}}
    @if ($errors->mediaUpdate->any())
        <div class="mt-4 border-l-4 border-red-500 bg-red-500/10 px-4 py-3 text-sm">
            @foreach ($errors->mediaUpdate->all() as $message)
                <p>{{ $message }}</p>
            @endforeach
        </div>
    @endif

    {{-- Existing files --}}
    @if ($shown->isEmpty())
        <p class="mt-8 text-sm text-charcoal/60 dark:text-ivory/60">Nothing uploaded yet.</p>
    @else
        <ul @class([
            'mt-8 grid gap-6',
            'sm:grid-cols-2' => !$single,
            'max-w-md' => $single,
        ])>
            @foreach ($shown as $item)
                <li class="flex flex-col border border-mist/20 dark:border-white/10">
                    <div class="relative bg-navy/5 dark:bg-black/20">
                        @if ($item->isImage())
                            <img src="{{ $item->thumb_url }}" alt="{{ $item->alt_text }}" loading="lazy"
                                class="aspect-[4/3] w-full object-cover">
                        @else
                            {{-- #t=0.1 makes the browser show a frame instead of a black box --}}
                            <video src="{{ $item->url }}#t=0.1" preload="metadata" controls muted
                                class="aspect-[4/3] w-full bg-black object-contain"></video>
                        @endif

                        <span
                            class="absolute left-2 top-2 bg-navy/80 px-2 py-1 text-[0.65rem] uppercase tracking-widest text-ivory">
                            {{ $item->isVideo() ? 'Video' : 'Photo' }}
                        </span>

                        @if ($isCover($item))
                            <span
                                class="absolute right-2 top-2 bg-gold px-2 py-1 text-[0.65rem] font-semibold uppercase tracking-widest text-navy">
                                Cover
                            </span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.media.update', $item) }}" class="space-y-3 p-4">
                        @csrf
                        @method('PUT')

                        <x-text-input name="alt_text" :value="$item->alt_text" maxlength="200"
                            placeholder="Alt text: describe what is shown" aria-label="Alt text"
                            class="block w-full text-sm" />

                        @if ($single)
                            <input type="hidden" name="sort_order" value="{{ $item->sort_order }}">

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="text-xs font-semibold uppercase tracking-[0.18em] text-gold transition hover:text-navy dark:hover:text-ivory">Save</button>
                            </div>
                        @else
                            <x-text-input name="caption" :value="$item->caption" maxlength="200"
                                placeholder="Caption (optional)" aria-label="Caption" class="block w-full text-sm" />

                            <div class="flex items-center gap-3">
                                <label for="position-{{ $item->id }}"
                                    class="text-xs font-semibold uppercase tracking-widest text-mist">Position</label>
                                <x-text-input id="position-{{ $item->id }}" type="number" name="sort_order"
                                    :value="$item->sort_order" min="1" class="block w-20 text-sm" />
                                <button type="submit"
                                    class="ms-auto text-xs font-semibold uppercase tracking-[0.18em] text-gold transition hover:text-navy dark:hover:text-ivory">
                                    Save
                                </button>
                            </div>
                        @endif
                    </form>

                    {{-- The action bar only exists when it has something to offer --}}
                    @if ($canSetCover($item) || $canDelete)
                        <div
                            class="mt-auto flex items-center justify-between border-t border-mist/20 px-4 py-3 text-xs font-semibold uppercase tracking-[0.18em] dark:border-white/10">
                            @if ($canSetCover($item))
                                <form method="POST" action="{{ route('admin.media.cover', $item) }}">
                                    @csrf
                                    <button type="submit" class="text-navy transition hover:text-gold dark:text-ivory">
                                        Set as cover
                                    </button>
                                </form>
                            @else
                                <span></span>
                            @endif

                            @if ($canDelete)
                                <button type="button"
                                    x-on:click="$dispatch('confirm-delete', {
                                        action: @js(route('admin.media.destroy', $item)),
                                        title: @js($item->isVideo() ? 'Delete video?' : 'Delete photo?'),
                                        message: 'The file will be permanently removed from the website and the server.',
                                    })"
                                    class="text-red-600 transition hover:text-red-400">Delete</button>
                            @endif
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</x-card>
