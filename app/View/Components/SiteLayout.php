<?php

namespace App\View\Components;

use App\Models\Amenity;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SiteLayout extends Component
{
    public string $siteName;

    public ?string $tagline;

    public string $fullTitle;

    public ?string $metaDescription;

    /** @var array<string, string|null> */
    public array $settings;

    /** @var list<array{0: string, 1: string}> */
    public array $nav;

    /** The nav without "Contact", for the footer (the footer is the contact section). */
    public array $footerNav;

    /** @var array<string, string> label => url */
    public array $social;

    public ?string $phoneHref;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $image = null,
    ) {
        $this->settings = Setting::map();
        $this->siteName = config('app.name');
        $this->tagline = $this->settings['tagline'] ?? null;

        $this->fullTitle = match (true) {
            filled($title) => "{$title} | {$this->siteName}",
            filled($this->tagline) => "{$this->siteName} | {$this->tagline}",
            default => $this->siteName,
        };

        $this->metaDescription = $description ?: $this->tagline;

        $home = url('/');

        $this->footerNav = array_values(array_filter([
            ['Residences', "{$home}#residences"],
            Amenity::exists() ? ['Amenities', "{$home}#amenities"] : null,
        ]));

        $this->nav = [...$this->footerNav, ['Contact', "{$home}#contact"]];

        $whatsapp = preg_replace('/\D+/', '', $this->settings['whatsapp'] ?? '');

        // array_filter drops every entry that is empty, so unset links never render
        $this->social = array_filter([
            'Facebook' => $this->settings['facebook'] ?? null,
            'Instagram' => $this->settings['instagram'] ?? null,
            'WhatsApp' => $whatsapp !== '' ? "https://wa.me/{$whatsapp}" : null,
        ]);

        $phone = $this->settings['phone'] ?? null;
        $this->phoneHref = filled($phone) ? 'tel:' . preg_replace('/[^\d+]/', '', $phone) : null;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.site');
    }
}
