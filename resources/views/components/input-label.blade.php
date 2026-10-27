@props(['value'])

<label
    {{ $attributes->merge(['class' => 'block text-xs font-semibold uppercase tracking-widest text-navy dark:text-ivory']) }}>
    {{ $value ?? $slot }}
</label>
