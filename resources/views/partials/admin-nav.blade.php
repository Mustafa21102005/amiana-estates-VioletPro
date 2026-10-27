@php
    // [label, route name, pattern that marks the link active, icon shapes, badge count]
    $items = [
        [
            'Dashboard',
            'dashboard',
            'dashboard',
            '<rect x="3.5" y="3.5" width="7" height="7" rx="1"/><rect x="13.5" y="3.5" width="7" height="7" rx="1"/><rect x="3.5" y="13.5" width="7" height="7" rx="1"/><rect x="13.5" y="13.5" width="7" height="7" rx="1"/>',
        ],
        [
            'Messages',
            'admin.messages.index',
            'admin.messages.*',
            '<rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="m3.5 7 8.5 6 8.5-6"/>',
            $unreadMessages ?? 0,
        ],
        [
            'Property Types',
            'admin.property-types.index',
            'admin.property-types.*',
            '<path d="M4 21V5a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v16M14 9h5a1 1 0 0 1 1 1v11M3 21h18M8 8h2M8 12h2M8 16h2"/>',
        ],
        [
            'Feature Categories',
            'admin.categories.index',
            'admin.categories.*',
            '<path d="M12 3 3 8l9 5 9-5-9-5Z"/><path d="m3 13 9 5 9-5"/>',
        ],
        [
            'Amenities',
            'admin.amenities.index',
            'admin.amenities.*',
            '<path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1L3.2 9.5l6.1-.9L12 3Z"/>',
        ],
        [
            'Content Sections',
            'admin.sections.index',
            'admin.sections.*',
            '<path d="M7 3h7l5 5v13H7V3Z"/><path d="M14 3v5h5M10 13h6M10 17h6"/>',
        ],
        [
            'Contact & Settings',
            'admin.settings.edit',
            'admin.settings.*',
            '<path d="M4 7h10M18 7h2M4 17h2M10 17h10"/><circle cx="16" cy="7" r="2"/><circle cx="8" cy="17" r="2"/>',
        ],
        ['My Account', 'profile.edit', 'profile.*', '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>'],
    ];
@endphp

<nav class="space-y-1">
    @foreach ($items as $item)
        @php
            [$label, $routeName, $pattern, $icon] = $item;
            $badge = $item[4] ?? 0;
            $active = request()->routeIs($pattern);
        @endphp

        @continue(!Route::has($routeName))

        <a href="{{ route($routeName) }}" x-bind:title="collapsed ? @js($label) : null"
            @if ($active) aria-current="page" @endif @class([
                'relative flex items-center gap-3 whitespace-nowrap border-l-2 px-5 py-3 text-xs font-medium uppercase tracking-[0.18em] transition',
                'lg:[.sidebar-collapsed_&]:justify-center lg:[.sidebar-collapsed_&]:gap-0 lg:[.sidebar-collapsed_&]:px-0',
                'border-gold bg-white/5 text-gold' => $active,
                'border-transparent text-ivory/70 hover:bg-white/5 hover:text-ivory' => !$active,
            ])>
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">{!! $icon !!}</svg>
            <span class="lg:[.sidebar-collapsed_&]:sr-only">{{ $label }}</span>

            @if ($badge > 0)
                <span
                    class="ms-auto rounded-full bg-gold px-2 py-0.5 text-[0.65rem] font-semibold text-navy lg:[.sidebar-collapsed_&]:absolute lg:[.sidebar-collapsed_&]:right-2 lg:[.sidebar-collapsed_&]:top-1.5 lg:[.sidebar-collapsed_&]:ms-0 lg:[.sidebar-collapsed_&]:px-1.5">
                    {{ $badge }}<span class="sr-only"> unread</span>
                </span>
            @endif
        </a>
    @endforeach
</nav>
