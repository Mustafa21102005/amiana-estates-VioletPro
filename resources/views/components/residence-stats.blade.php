@props(['residence'])

<dl {{ $attributes->class(['flex flex-wrap gap-x-10 gap-y-4']) }}>
    @if ($residence->size_sqm)
        <div>
            <dt class="text-[0.65rem] uppercase tracking-[0.25em] text-mist">Size</dt>
            <dd class="mt-1 font-serif text-2xl">{{ number_format($residence->size_sqm) }} m²</dd>
        </div>
    @endif
    @if (!is_null($residence->bedrooms))
        <div>
            <dt class="text-[0.65rem] uppercase tracking-[0.25em] text-mist">Bedrooms</dt>
            <dd class="mt-1 font-serif text-2xl">{{ $residence->bedrooms }}</dd>
        </div>
    @endif
    <div>
        <dt class="text-[0.65rem] uppercase tracking-[0.25em] text-mist">Residences</dt>
        <dd class="mt-1 font-serif text-2xl">{{ $residence->units_count }}</dd>
    </div>
</dl>
