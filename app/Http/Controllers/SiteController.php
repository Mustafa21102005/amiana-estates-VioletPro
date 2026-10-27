<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Category;
use App\Models\ContentSection;
use App\Models\Media;
use App\Models\PropertyType;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    /** How many gallery photos show before the "See all" button. */
    private const GALLERY_PREVIEW = 6;

    public function home(): View
    {
        $residences = PropertyType::with('media')->orderBy('sort_order')->get();
        $sections = ContentSection::with('media')->get()->keyBy('slug');

        // Only the featured residence shows its features, so only it loads them
        $featured = $residences->firstWhere('is_featured', true)?->load('features.category');

        return view('site.home', [
            'featured' => $featured,
            'residences' => $residences->where('is_featured', false)->values(),
            'amenities' => Amenity::orderBy('sort_order')->get(),
            'story' => $this->storySections($sections),
            ...$this->heroData($sections->get('hero'), $featured),
            ...$this->showcaseData($featured),
        ]);
    }

    public function residence(PropertyType $propertyType): View
    {
        $propertyType->load(['media', 'features']);

        $images = $propertyType->media->where('type', 'image')->values();
        $features = $propertyType->features->groupBy('category_id');

        return view('site.residence', [
            'propertyType' => $propertyType,
            'cover' => $propertyType->cover_image,
            'images' => $images,
            'videoItems' => $propertyType->media->where('type', 'video')
                ->map(fn(Media $media) => ['src' => $media->url, 'caption' => $media->caption])
                ->values()
                ->all(),
            // the lightbox's data, built once here and handed to Alpine in the view
            'slides' => $images->map(fn(Media $media) => [
                'src' => $media->url,
                'alt' => $media->alt_text ?: $propertyType->name,
                'caption' => $media->caption,
            ])->all(),
            'galleryPreview' => self::GALLERY_PREVIEW,
            'metaDescription' => $propertyType->tagline
                ?: Str::limit(strip_tags((string) $propertyType->description), 160),
            'features' => $features,
            // only the categories this residence actually uses, in the admin's order
            'categories' => Category::orderBy('sort_order')->get()
                ->filter(fn(Category $category) => $features->has($category->id))
                ->values(),
            'others' => PropertyType::with('media')
                ->whereKeyNot($propertyType->getKey())
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    /** What the hero shows: its own photo or video, or the featured cover as a fallback. */
    private function heroData(?ContentSection $hero, ?PropertyType $featured): array
    {
        $media = $hero?->media->first();

        return [
            'heroVideo' => $media?->isVideo() ? $media : null,
            // a photo hero uses its own photo; a video hero uses the featured cover as poster and fallback
            'heroImage' => ($media?->isImage() ? $media : null) ?? $featured?->cover_image,
            'heroTitle' => $hero?->title ?: config('app.name'),
            'heroSubtitle' => $hero?->subtitle ?: Setting::read('tagline'),
        ];
    }

    /** The penthouse showcase: cover, two side photos, the top highlights and a short excerpt. */
    private function showcaseData(?PropertyType $featured): array
    {
        if (! $featured) {
            return [
                'featuredCover' => null,
                'featuredExtras' => collect(),
                'highlights' => collect(),
                'featuredExcerpt' => null,
            ];
        }

        $images = $featured->media->where('type', 'image')->values();

        return [
            'featuredCover' => $images->first(),
            'featuredExtras' => $images->slice(1, 2)->values(),
            'highlights' => $featured->features
                ->sortBy([['category.sort_order', 'asc'], ['sort_order', 'asc']])
                ->take(8)
                ->values(),
            'featuredExcerpt' => Str::limit(strip_tags((string) $featured->description), 420),
        ];
    }

    /** The home page's text sections that exist, in display order, each with its photo. */
    private function storySections(Collection $sections): Collection
    {
        return collect(ContentSection::PLACEMENT)
            ->except('hero')
            ->map(function (array $info, string $slug) use ($sections) {
                $section = $sections->get($slug);

                return $section ? [
                    'slug' => $slug,
                    'eyebrow' => $info[0],
                    'section' => $section,
                    'image' => $section->media->firstWhere('type', 'image'),
                ] : null;
            })
            ->filter()
            ->values();
    }
}
