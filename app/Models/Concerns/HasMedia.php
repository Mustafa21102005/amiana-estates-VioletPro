<?php

namespace App\Models\Concerns;

use App\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Media> $media
 * @property-read Media|null $cover_image
 */
trait HasMedia
{
    public static function bootHasMedia(): void
    {
        // The database cannot cascade a polymorphic relation, so it is done here.
        // Each file is deleted as a model so its own cleanup (removing the file from disk) runs.
        static::deleting(function (Model $model) {
            $model->media()->get()->each->delete();
        });
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
    }

    /** The first image in the gallery: used as the cover everywhere. */
    protected function coverImage(): Attribute
    {
        return Attribute::get(fn() => $this->media->firstWhere('type', 'image'));
    }

    /** Does this record hold a single file? When true, a new upload replaces the current one. */
    public function hasSingleMedia(): bool
    {
        return false;
    }

    /** Can videos be attached to this record? */
    public function acceptsVideo(): bool
    {
        return true;
    }

    /** Can the admin delete this record's files? */
    public function canDeleteMedia(): bool
    {
        return true;
    }
}
