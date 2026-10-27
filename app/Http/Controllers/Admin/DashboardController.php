<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\ContentSection;
use App\Models\Feature;
use App\Models\Media;
use App\Models\Message;
use App\Models\PropertyType;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $unread = Message::unread()->count();
        $features = Feature::count();
        $videos = Media::where('type', 'video')->count();

        return view('dashboard', [
            'stats' => [
                [
                    'label' => 'Unread messages',
                    'value' => $unread,
                    'note' => Message::count() . ' in total',
                    'href' => route('admin.messages.index'),
                    'highlight' => $unread > 0,
                ],
                [
                    'label' => 'Residences',
                    'value' => PropertyType::count(),
                    'note' => $features . ' ' . Str::plural('feature', $features) . ' listed',
                    'href' => route('admin.property-types.index'),
                    'highlight' => false,
                ],
                [
                    'label' => 'Amenities',
                    'value' => Amenity::count(),
                    'note' => null,
                    'href' => route('admin.amenities.index'),
                    'highlight' => false,
                ],
                [
                    'label' => 'Photos & videos',
                    'value' => Media::count(),
                    'note' => $videos . ' ' . Str::plural('video', $videos),
                    'href' => null,   // files are managed inside each residence and section
                    'highlight' => false,
                ],
            ],
            'latestUnread' => Message::unread()->latest()->orderByDesc('id')->limit(5)->get(),
            'unreadTotal' => $unread,
            'attention' => $this->attention(),
        ]);
    }

    private function attention(): array
    {
        $items = [];

        $overdue = Message::overdue()->count();

        if ($overdue > 0) {
            $oldest = Message::overdue()->oldest()->first();
            $days = Message::OVERDUE_DAYS;

            $items[] = [
                'text' => ($overdue === 1
                    ? "An unread message has been waiting for more than {$days} days."
                    : "{$overdue} unread messages have been waiting for more than {$days} days.")
                    . ' The oldest arrived ' . $oldest->created_at->diffForHumans() . '.',
                // opening the oldest one marks it read, and the alert moves on to the next if more are overdue
                'url' => route('admin.messages.show', $oldest),
                'label' => 'Read now',
                'urgent' => true,
            ];
        }

        if (PropertyType::doesntExist()) {
            $items[] = [
                'text' => 'No residences yet. The website has nothing to show.',
                'url' => route('admin.property-types.create'),
            ];
        } else {
            if (! PropertyType::where('is_featured', true)->exists()) {
                $items[] = [
                    'text' => 'No residence is featured, so the penthouse showcase is hidden on the home page.',
                    'url' => route('admin.property-types.index'),
                ];
            }

            PropertyType::whereDoesntHave('media', fn($query) => $query->where('type', 'image'))
                ->orderBy('sort_order')
                ->get()
                ->each(function (PropertyType $propertyType) use (&$items) {
                    $items[] = [
                        'text' => "{$propertyType->name} has no photos yet.",
                        'url' => route('admin.property-types.edit', $propertyType) . '#gallery',
                    ];
                });
        }

        $missingContact = collect(['phone' => 'phone number', 'email' => 'email address', 'address' => 'address'])
            ->filter(fn(string $label, string $key) => blank(Setting::read($key)))
            ->values();

        if ($missingContact->isNotEmpty()) {
            $items[] = [
                'text' => 'Contact details are incomplete. Add the ' . $missingContact->join(', ', ' and ') . '.',
                'url' => route('admin.settings.edit'),
            ];
        }

        if (Amenity::doesntExist()) {
            $items[] = [
                'text' => 'No amenities yet, so that section is hidden on the home page.',
                'url' => route('admin.amenities.index'),
            ];
        }

        $missingSections = array_diff(array_keys(ContentSection::PLACEMENT), ContentSection::pluck('slug')->all());

        if ($missingSections !== []) {
            $items[] = [
                'text' => count($missingSections) . ' content ' . Str::plural('section', count($missingSections))
                    . ' missing from the database: ' . implode(', ', $missingSections) . '.',
                'url' => route('admin.sections.index'),
            ];
        }

        return $items;
    }
}
