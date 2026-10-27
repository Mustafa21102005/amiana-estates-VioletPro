<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class MediaUploader
{
    private const LARGE = 2000; // longest side of the full-size copy, in pixels
    private const THUMB = 640;  // width of the small copy used in grids and cards

    public function store(UploadedFile $file, Model $owner): Media
    {
        $disk = Storage::disk('public');
        $directory = 'media/' . Str::kebab(class_basename($owner)) . '/' . $owner->getKey();
        $name = (string) Str::uuid();
        $isImage = str_starts_with((string) $file->getMimeType(), 'image/');

        if ($isImage) {
            $path = "{$directory}/{$name}.webp";

            $image = (new ImageManager(new Driver()))->read($file->getRealPath());

            // scaleDown only ever shrinks, so a small photo is never blown up
            $image->scaleDown(width: self::LARGE, height: self::LARGE);
            $disk->put($path, (string) $image->toWebp(quality: 82));

            $image->scaleDown(width: self::THUMB);
            $disk->put("{$directory}/{$name}-thumb.webp", (string) $image->toWebp(quality: 78));
        } else {
            // Videos are stored untouched
            $path = $file->storeAs($directory, "{$name}.{$file->extension()}", 'public');
        }

        return $owner->media()->create([
            'type' => $isImage ? 'image' : 'video',
            'path' => $path,
            'sort_order' => Media::nextOrder([
                'mediable_type' => $owner->getMorphClass(),
                'mediable_id' => $owner->getKey(),
            ]),
        ]);
    }
}
