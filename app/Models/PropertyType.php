<?php

namespace App\Models;

use App\Models\Concerns\GeneratesSlug;
use App\Models\Concerns\HasMedia;
use App\Models\Concerns\HasOrderedPosition;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'tagline',
    'description',
    'size_sqm',
    'bedrooms',
    'units_count',
    'is_featured',
    'sort_order'
])]
class PropertyType extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyTypeFactory> */
    use HasFactory, GeneratesSlug, HasOrderedPosition, HasMedia;

    /** The admin can never delete below this many, so the website always has something to show. */
    public const MINIMUM = 1;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['is_featured' => 'boolean'];
    }

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class)->orderBy('sort_order');
    }
}
