<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'subtitle', 'body'])]
class ContentSection extends Model
{
    /** @use HasFactory<\Database\Factories\ContentSectionFactory> */
    use HasFactory, HasMedia;

    /**
     * The sections the website expects, in the order they appear: slug => [label, where it shows].
     * These slugs are fixed names the home page looks up, so sections are edited, never created or deleted.
     */
    public const PLACEMENT = [
        'hero' => ['Hero', 'The large banner at the top of the home page'],
        'estate-experience' => ['The Estate Experience', 'Home page, after the residences'],
        'design-philosophy' => ['Design Philosophy', 'Home page, below the estate experience'],
        'lifestyle' => ['Lifestyle', 'Home page, below the design philosophy'],
        'location' => ['Location Advantages', 'Home page, below the lifestyle section'],
        'amiana-difference' => ['The Amiana Difference', 'Home page, before the amenities'],
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function label(): string
    {
        return self::PLACEMENT[$this->slug][0] ?? $this->title;
    }

    public function placement(): string
    {
        return self::PLACEMENT[$this->slug][1] ?? '';
    }

    public function hasSingleMedia(): bool
    {
        return true;
    }

    /** Only the hero can show a background video. */
    public function acceptsVideo(): bool
    {
        return $this->slug === 'hero';
    }

    public static function positionOf(string $slug): int
    {
        $index = array_search($slug, array_keys(self::PLACEMENT), true);

        return $index === false ? PHP_INT_MAX : $index;
    }

    /** A section's photo or video can be replaced but not removed. */
    public function canDeleteMedia(): bool
    {
        return false;
    }
}
