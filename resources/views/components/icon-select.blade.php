<div x-data="iconSelect(@js($options))" x-modelable="value" x-id="['icon-select']" x-on:click.outside="close()"
    x-on:keydown.escape="if (open) { $event.stopPropagation(); close(true) }" x-on:keydown.tab="close()"
    {{ $attributes }}>

    {{-- The value that is actually submitted with the form --}}
    <input type="hidden" name="{{ $name }}" x-bind:value="value">

    <x-input-label :for="$name" :value="$label" />

    <div class="relative mt-2">
        {{-- Trigger: the chosen icon and its name --}}
        <button type="button" id="{{ $name }}" x-ref="trigger" x-on:click="toggle()"
            x-on:keydown.arrow-down.prevent="openList()" aria-haspopup="listbox" x-bind:aria-expanded="open.toString()"
            class="flex w-full items-center gap-3 rounded-sm border border-mist/60 bg-white px-3 py-2.5 text-start text-sm text-charcoal shadow-sm transition focus:border-gold focus:outline-none focus:ring-1 focus:ring-gold dark:border-mist/30 dark:bg-white/5 dark:text-ivory">
            @foreach ($options as $option)
                <span x-show="value === '{{ $option['value'] }}'" x-cloak class="text-navy dark:text-gold">
                    <x-amenity-icon :name="$option['value']" class="h-5 w-5" />
                </span>
            @endforeach

            <span class="flex-1 truncate" x-text="selectedLabel"></span>

            <svg class="h-4 w-4 shrink-0 text-mist transition-transform" x-bind:class="open ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                viewBox="0 0 24 24" aria-hidden="true">
                <path d="m6 9 6 6 6-6" />
            </svg>
        </button>

        {{-- Panel: search box and the list --}}
        <div x-show="open" x-cloak x-transition.opacity.duration.150ms
            class="absolute inset-x-0 top-full z-20 mt-2 bg-white shadow-xl ring-1 ring-mist/30 dark:bg-navy dark:ring-white/15">

            <div class="border-b border-mist/20 p-2 dark:border-white/10">
                <input type="text" x-ref="search" x-model="query" x-on:input="onSearch()"
                    x-on:keydown.arrow-down.prevent="move(1)" x-on:keydown.arrow-up.prevent="move(-1)"
                    x-on:keydown.enter.prevent="choose(activeName)" role="combobox" aria-autocomplete="list"
                    aria-label="Search icons" autocomplete="off" placeholder="Search icons…"
                    x-bind:aria-expanded="open.toString()" x-bind:aria-controls="$id('icon-select')"
                    x-bind:aria-activedescendant="activeName ? $id('icon-select') + '-' + activeName : null"
                    class="block w-full border-0 bg-transparent px-2 py-2 text-sm text-charcoal placeholder-mist focus:ring-0 dark:text-ivory">
            </div>

            <ul x-ref="list" role="listbox" x-bind:id="$id('icon-select')" class="max-h-64 overflow-y-auto py-1">
                @foreach ($options as $option)
                    <li x-show="shows('{{ $option['value'] }}')" role="option"
                        x-bind:id="$id('icon-select') + '-{{ $option['value'] }}'"
                        x-bind:aria-selected="(value === '{{ $option['value'] }}').toString()"
                        x-bind:data-active="(activeName === '{{ $option['value'] }}').toString()"
                        x-bind:class="activeName === '{{ $option['value'] }}' ? 'bg-gold/20' : ''"
                        x-on:click="choose('{{ $option['value'] }}')"
                        x-on:mousemove="activeName = '{{ $option['value'] }}'"
                        class="flex cursor-pointer items-center gap-3 px-4 py-2.5 text-sm text-charcoal dark:text-ivory">
                        <x-amenity-icon :name="$option['value']" class="h-5 w-5 shrink-0 text-navy dark:text-gold" />
                        <span class="flex-1">{{ $option['label'] }}</span>
                        <svg x-show="value === '{{ $option['value'] }}'" class="h-4 w-4 shrink-0 text-gold"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m5 12 5 5 9-10" />
                        </svg>
                    </li>
                @endforeach

                <li x-show="filtered.length === 0" x-cloak
                    class="px-4 py-6 text-center text-sm text-charcoal/60 dark:text-ivory/60">
                    No icon matches “<span x-text="query"></span>”.
                </li>
            </ul>
        </div>
    </div>
</div>
