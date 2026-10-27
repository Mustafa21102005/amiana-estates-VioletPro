<?php

namespace App\View\Components;

use App\Models\Media;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Illuminate\View\Component;

class MediaGallery extends Component
{
    public bool $single;

    public bool $canDelete;

    public Collection $shown;

    public Collection $uploadErrors;

    public string $noun;

    public string $heading;

    public string $accept;

    public string $inputLabel;

    public string $hint;

    public string $buttonLabel;

    /** @var array{image: int, video: int, imageLabel: string, videoLabel: string} */
    public array $limits;

    private ?Media $cover;

    /**
     * Create a new component instance.
     */
    public function __construct(public Model $owner)
    {
        $this->single = $owner->hasSingleMedia();
        $this->canDelete = $owner->canDeleteMedia();
        $allowsVideo = $owner->acceptsVideo();

        $items = $owner->media;

        $this->shown = $this->single ? $items->take(1) : $items;
        $this->cover = $this->single ? null : $owner->cover_image;

        $this->noun = $allowsVideo ? 'photo or video' : 'photo';
        $this->heading = $this->single ? ucfirst($this->noun) : 'Photos & videos';

        $this->accept = $allowsVideo
            ? 'image/jpeg,image/png,image/webp,video/mp4,video/webm'
            : 'image/jpeg,image/png,image/webp';

        $this->inputLabel = match (true) {
            $this->single && $items->isNotEmpty() => "Choose a new {$this->noun}",
            $this->single => "Choose a {$this->noun}",
            $allowsVideo => 'Add photos or videos',
            default => 'Add photos',
        };

        $imageBytes = config('media.image_kb') * 1024;
        $videoBytes = config('media.video_kb') * 1024;

        $this->limits = [
            'image' => $imageBytes,
            'video' => $videoBytes,
            'imageLabel' => Number::fileSize($imageBytes),
            'videoLabel' => Number::fileSize($videoBytes),
        ];

        $imageLimit = $this->limits['imageLabel'];
        $videoLimit = $this->limits['videoLabel'];

        $types = $allowsVideo
            ? "JPG, PNG or WebP photos (up to {$imageLimit} each) and MP4 or WebM videos (up to {$videoLimit} each)"
            : "JPG, PNG or WebP photos (up to {$imageLimit} each)";

        $this->hint = $types . ($this->single ? '.' : ', 20 files at a time.');

        $this->buttonLabel = $this->single && $items->isNotEmpty() ? 'Replace' : 'Upload';

        // Only the upload-related errors (the page also contains other forms)
        $messages = session('errors')?->getBag('default')->getMessages() ?? [];

        $this->uploadErrors = collect($messages)
            ->filter(fn($list, $key) => str_starts_with($key, 'files') || str_starts_with($key, 'owner_'))
            ->flatten()
            ->unique()
            ->values();
    }

    public function isCover(Media $item): bool
    {
        return $this->cover !== null && $item->is($this->cover);
    }

    public function canSetCover(Media $item): bool
    {
        return ! $this->single && $item->isImage() && ! $this->isCover($item);
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.media-gallery');
    }
}
