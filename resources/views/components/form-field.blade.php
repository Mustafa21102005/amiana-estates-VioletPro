@props([
    'name',
    'label',
    'value' => null,
    'type' => 'text',
    'textarea' => false,
    'rows' => 3,
    'hint' => null,
    'placeholder' => null,
])

<div {{ $attributes }}>
    <x-input-label :for="$name" :value="$label" />

    @if ($textarea)
        <x-textarea :id="$name" :name="$name" :rows="$rows" :placeholder="$placeholder"
            class="mt-2 block w-full">{{ old($name, $value) }}</x-textarea>
    @else
        <x-text-input :id="$name" :type="$type" :name="$name" :value="old($name, $value)" :placeholder="$placeholder"
            class="mt-2 block w-full" />
    @endif

    @if ($hint)
        <p class="mt-2 text-xs text-charcoal/60 dark:text-ivory/60">{{ $hint }}</p>
    @endif

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
