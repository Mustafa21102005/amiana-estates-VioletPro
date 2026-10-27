<?php

namespace App\Models;

use App\Models\Concerns\HasOrderedPosition;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[Fillable(['mediable_type', 'mediable_id', 'type', 'collection', 'path', 'alt_text', 'caption', 'sort_order'])]
class Media extends Model
{
    /** @use HasFactory<\Database\Factories\MediaFactory> */
    use HasFactory, HasOrderedPosition;

    /** Files are numbered 1, 2, 3... within the record they belong to. */
    protected function orderGroup(): array
    {
        return [
            'mediable_type' => $this->mediable_type,
            'mediable_id' => $this->mediable_id,
        ];
    }

    protected static function booted(): void
    {
        static::deleted(function (Media $media) {
            $files = array_filter([$media->path, $media->thumbPath()]);

            // Wait until any surrounding transaction commits, so a rollback never leaves a record without its file
            DB::afterCommit(fn() => Storage::disk('public')->delete($files));
        });
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    /** Photos have a small sibling file: abc.webp -> abc-thumb.webp */
    public function thumbPath(): ?string
    {
        return $this->isImage() ? preg_replace('/\.webp$/', '-thumb.webp', $this->path) : null;
    }

    // $media->url
    protected function url(): Attribute
    {
        return Attribute::get(fn() => asset('storage/' . $this->path));
    }

    // $media->thumb_url (falls back to the full file for videos)
    protected function thumbUrl(): Attribute
    {
        return Attribute::get(fn() => $this->thumbPath() ? asset('storage/' . $this->thumbPath()) : $this->url);
    }
}
