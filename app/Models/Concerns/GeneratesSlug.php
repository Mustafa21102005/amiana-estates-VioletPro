<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait GeneratesSlug
{
    protected static function bootGeneratesSlug(): void
    {
        static::creating(function (Model $model) {
            // Only fill it if nobody set one, so seeders can still choose their own slug
            if (blank($model->slug)) {
                $model->slug = static::makeUniqueSlug((string) $model->name);
            }
        });

        static::updating(function (Model $model) {
            // Rebuild the slug only when the name actually changed
            if ($model->syncsSlugOnRename() && $model->isDirty('name')) {
                $model->slug = static::makeUniqueSlug((string) $model->name, $model->getKey());
            }
        });
    }

    /** Models can override this and return false to keep their slug fixed after creation. */
    public function syncsSlugOnRename(): bool
    {
        return true;
    }

    protected static function makeUniqueSlug(string $name, mixed $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'item';
        $slug = $base;
        $suffix = 2;

        while (
            static::where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->whereKeyNot($ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }

    /** Route model binding looks records up by slug instead of id. */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
