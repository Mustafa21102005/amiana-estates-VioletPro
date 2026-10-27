<?php

namespace App\Support;

final class AmenityIcons
{
    public const DEFAULT = 'star';

    /**
     * name => [label, SVG shapes on a 24x24 grid].
     * These strings are hard-coded here and never come from user input.
     */
    private const ICONS = [
        'star' => [
            'General',
            '<path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1L3.2 9.5l6.1-.9L12 3Z"/>'
        ],
        'shield' => [
            'Security',
            '<path d="M12 3 4.5 6v5.5c0 4.4 3.1 8.1 7.5 9.5 4.4-1.4 7.5-5.1 7.5-9.5V6L12 3Z"/><path d="m9 12 2.2 2.2L15.5 10"/>'
        ],
        'camera' => [
            'CCTV',
            '<rect x="2.5" y="7" width="14" height="10" rx="1.5"/><path d="m16.5 10.5 5-2.5v8l-5-2.5"/>'
        ],
        'key' => [
            'Access control',
            '<circle cx="8" cy="15" r="4"/><path d="m10.8 12.2 9.2-9.2M16 7l3 3"/>'
        ],
        'gate' => [
            'Gated entrance',
            '<path d="M4 21V10a8 8 0 0 1 16 0v11"/><path d="M2.5 21h19M12 2v19M7.5 21v-7M16.5 21v-7"/>'
        ],
        'flame' => [
            'Fire safety',
            '<path d="M12 3c1 3.5 5.5 6 5.5 11a5.5 5.5 0 0 1-11 0c0-2 1-3.5 2-4.5.2 1.5.8 2.5 1.8 3C10 9 11 6 12 3Z"/>'
        ],
        'car' => [
            'Parking',
            '<path d="M5.5 12l1.8-4.4a1.5 1.5 0 0 1 1.4-.9h6.6a1.5 1.5 0 0 1 1.4.9L18.5 12"/><path d="M5 17H3.5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h17a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H19M9.5 17h5"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/>'
        ],
        'bolt' => [
            'Power backup',
            '<path d="M13 2.5 5 13.5h6l-1 8 8-11h-6l1-8Z"/>'
        ],
        'droplet' => [
            'Water supply',
            '<path d="M12 3s6 6.2 6 10.5a6 6 0 0 1-12 0C6 9.2 12 3 12 3Z"/>'
        ],
        'elevator' => [
            'Lifts',
            '<rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="m9 10 3-3 3 3M9 14l3 3 3-3"/>'
        ],
        'snowflake' => [
            'Air conditioning',
            '<path d="M12 2.5v19M3.8 7.2l16.4 9.6M3.8 16.8 20.2 7.2"/><path d="m9.5 4.5 2.5 2 2.5-2M9.5 19.5l2.5-2 2.5 2"/>'
        ],
        'wifi' => [
            'Internet',
            '<path d="M2.5 9.5a14 14 0 0 1 19 0M5.5 13a9.5 9.5 0 0 1 13 0M8.7 16.3a5 5 0 0 1 6.6 0"/><path d="M12 20h.01"/>'
        ],
        'smart-home' => [
            'Smart home',
            '<path d="M3.5 11 12 4l8.5 7M5.5 9.5V20h13V9.5"/><path d="M9.5 15.5a3.5 3.5 0 0 1 5 0M8 13.5a5.5 5.5 0 0 1 8 0"/><path d="M12 18h.01"/>'
        ],
        'dumbbell' => [
            'Fitness',
            '<path d="M6.5 8v8M17.5 8v8M3.5 10v4M20.5 10v4M6.5 12h11"/>'
        ],
        'waves' => [
            'Swimming pool',
            '<path d="M3 8q1.5-2 3 0t3 0 3 0 3 0 3 0 3 0M3 13q1.5-2 3 0t3 0 3 0 3 0 3 0 3 0M3 18q1.5-2 3 0t3 0 3 0 3 0 3 0 3 0"/>'
        ],
        'spa' => [
            'Spa & wellness',
            '<path d="M12 3.5c2.2 2.8 3.2 5.4 3.2 7.8S14 15.8 12 17c-2-1.2-3.2-3.3-3.2-5.7S9.8 6.3 12 3.5Z"/><path d="M9 10.5c-3 0-5.5 2-5.5 6 3 0 5.5-1 6.5-3M15 10.5c3 0 5.5 2 5.5 6-3 0-5.5-1-6.5-3"/><path d="M5 20.5h14"/>'
        ],
        'leaf' => [
            'Gardens',
            '<path d="M5 19c0-8 5-13 14-14 0 9-5 14-13 14"/><path d="M5 19c3-5 6-8 10-10"/>'
        ],
        'sun' => [
            'Rooftop terrace',
            '<circle cx="12" cy="12" r="4"/><path d="M12 2.5v2M12 19.5v2M2.5 12h2M19.5 12h2M5.3 5.3l1.4 1.4M17.3 17.3l1.4 1.4M5.3 18.7l1.4-1.4M17.3 6.7l1.4-1.4"/>'
        ],
        'eye' => [
            'Panoramic views',
            '<path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/>'
        ],
        'building' => [
            'Residential tower',
            '<path d="M5 21V5a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v16M15 10h3a1 1 0 0 1 1 1v10M3 21h18M8.5 8h3M8.5 12h3M8.5 16h3"/>'
        ],
        'bell' => [
            'Concierge',
            '<path d="M4 17a8 8 0 0 1 16 0H4Z"/><path d="M2.5 20h19M12 9V6.5M10 6.5h4"/>'
        ],
        'sofa' => [
            'Lounge',
            '<path d="M5 11V8a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v3"/><path d="M3 13a2 2 0 0 1 4 0v1h10v-1a2 2 0 0 1 4 0v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-4Z"/><path d="M6 18v2M18 18v2"/>'
        ],
        'utensils' => [
            'Dining',
            '<path d="M6 3v6a2 2 0 0 0 4 0V3M8 11v10"/><path d="M17 21V3c-2.5 1.5-3.5 4-3.5 7.5 0 1.5.8 2.5 3.5 2.5"/>'
        ],
        'briefcase' => [
            'Business centre',
            '<rect x="3" y="7" width="18" height="13" rx="1.5"/><path d="M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2M3 13h18"/>'
        ],
        'film' => [
            'Cinema',
            '<rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="M7 5v14M17 5v14M3 9.5h4M3 14.5h4M17 9.5h4M17 14.5h4"/>'
        ],
        'sparkles' => [
            'Housekeeping',
            '<path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3Z"/><path d="M19 16v4M17 18h4"/>'
        ],
        'smile' => [
            'Children\'s play',
            '<circle cx="12" cy="12" r="9"/><path d="M8.5 14.5a4.5 4.5 0 0 0 7 0M9 9.5h.01M15 9.5h.01"/>'
        ],
    ];

    /** Extra words people might type when looking for an icon. */
    private const KEYWORDS = [
        'star' => 'default other',
        'shield' => 'safe guard protection',
        'camera' => 'cctv surveillance video',
        'key' => 'lock card entry',
        'gate' => 'entrance door arch',
        'flame' => 'fire alarm',
        'car' => 'garage vehicle',
        'bolt' => 'generator electricity energy',
        'droplet' => 'water borehole tank',
        'elevator' => 'lift',
        'snowflake' => 'ac cooling hvac',
        'wifi' => 'internet fibre broadband',
        'smart-home' => 'automation',
        'dumbbell' => 'gym workout exercise',
        'waves' => 'pool swim',
        'spa' => 'sauna massage lotus',
        'leaf' => 'garden landscape green plants',
        'sun' => 'roof sky terrace',
        'eye' => 'view panorama',
        'building' => 'tower apartment',
        'bell' => 'reception service',
        'sofa' => 'club couch',
        'utensils' => 'restaurant food cafe kitchen',
        'briefcase' => 'office conference work',
        'film' => 'theatre theater movie',
        'sparkles' => 'cleaning maintenance',
        'smile' => 'kids play playground',
    ];

    public static function keywords(string $name): string
    {
        return self::KEYWORDS[$name] ?? '';
    }

    /** @return list<string> */
    public static function names(): array
    {
        return array_keys(self::ICONS);
    }

    /** @return array<string, string> name => label, in display order */
    public static function options(): array
    {
        return array_map(fn(array $icon) => $icon[0], self::ICONS);
    }

    /** The shapes for an icon. A missing or unknown name gives the default icon, never an error. */
    public static function svg(?string $name): string
    {
        return (self::ICONS[$name] ?? self::ICONS[self::DEFAULT])[1];
    }
}
