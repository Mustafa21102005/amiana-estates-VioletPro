@props(['on' => 'auto', 'mark' => false])

@php
    $base = 'images/logo/' . ($mark ? 'mark' : 'logo');
    $light = asset($base . '-on-light.png'); // navy artwork, for light backgrounds
    $dark = asset($base . '-on-dark.png'); // white artwork, for dark backgrounds
@endphp

@if ($on === 'auto')
    {{-- Follows the theme: the page's `dark` class decides which image shows --}}
    <span {{ $attributes->class(['inline-block']) }}>
        <img src="{{ $light }}" alt="Amiana Estates" class="block h-full w-auto dark:hidden">
        <img src="{{ $dark }}" alt="" aria-hidden="true" class="hidden h-full w-auto dark:block">
    </span>
@else
    {{-- Forced: always the same artwork, for backgrounds that never change --}}
    <img src="{{ $on === 'dark' ? $dark : $light }}" alt="Amiana Estates" {{ $attributes->class(['block', 'w-auto']) }}>
@endif
