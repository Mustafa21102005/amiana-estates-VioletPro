@props(['name' => null])

<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" {{ $attributes }}>
    {!! \App\Support\AmenityIcons::svg($name) !!}
</svg>
