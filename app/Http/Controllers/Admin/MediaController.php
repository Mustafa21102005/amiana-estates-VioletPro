<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Models\Media;
use App\Services\MediaUploader;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class MediaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaRequest $request, MediaUploader $uploader): RedirectResponse
    {
        $class = Relation::getMorphedModel($request->validated('owner_type'));
        $owner = $class::findOrFail($request->validated('owner_id'));

        $files = collect($request->file('files'));

        // A single-file spot (a section's photo) takes just one file
        if ($owner->hasSingleMedia()) {
            $files = $files->take(1);
        }

        $hasVideo = $files->contains(fn($file) => str_starts_with((string) $file->getMimeType(), 'video/'));

        if ($hasVideo && ! $owner->acceptsVideo()) {
            return $this->backToGallery()->with('error', 'This spot accepts photos only.');
        }

        $group = [
            'mediable_type' => $owner->getMorphClass(),
            'mediable_id' => $owner->getKey(),
        ];
        $previous = $owner->hasSingleMedia() ? $owner->media()->get() : collect();

        $stored = 0;
        $failed = [];

        foreach ($files as $file) {
            try {
                $uploader->store($file, $owner);
                $stored++;
            } catch (Throwable $e) {
                // One bad file (corrupt image, say) must not lose the others
                report($e);
                $failed[] = $file->getClientOriginalName();
            }
        }

        // Replace: the old file is removed only after the new one is safely stored
        if ($stored > 0 && $previous->isNotEmpty()) {
            $previous->each->delete();
            Media::reorder($group);
        }

        $response = $this->backToGallery();

        if ($stored > 0) {
            $response->with('success', $stored . ' ' . Str::plural('file', $stored) . ' uploaded.');
        }

        if ($failed !== []) {
            $response->with('error', 'These files could not be processed: ' . implode(', ', $failed));
        }

        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMediaRequest $request, Media $media): RedirectResponse
    {
        $validated = $request->validated();
        $newOrder = $validated['sort_order'];

        unset($validated['sort_order']);

        DB::transaction(function () use ($media, $validated, $newOrder) {
            $media->update($validated);
            $media->moveToOrder($newOrder);
        });

        return $this->backToGallery()->with('success', 'Saved.');
    }

    /** Position 1 is the cover, so "set as cover" simply moves the photo to the front. */
    public function cover(Media $media): RedirectResponse
    {
        abort_unless($media->isImage(), 422, 'Only photos can be the cover.');

        $media->moveToOrder(1);

        return $this->backToGallery()->with('success', 'Cover photo updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media): RedirectResponse
    {
        // The button is hidden for single-file spots, but the server must enforce the rule too
        if ($media->mediable && ! $media->mediable->canDeleteMedia()) {
            abort(403, 'This file can only be replaced, not deleted.');
        }

        $group = [
            'mediable_type' => $media->mediable_type,
            'mediable_id' => $media->mediable_id,
        ];

        DB::transaction(function () use ($media, $group) {
            $media->delete();   // the model's own hook removes the files from disk
            Media::reorder($group);
        });

        return $this->backToGallery()->with('success', 'File deleted.');
    }

    private function backToGallery(): RedirectResponse
    {
        return redirect(url()->previous() . '#gallery');
    }
}
