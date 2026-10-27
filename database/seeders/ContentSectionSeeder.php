<?php

namespace Database\Seeders;

use App\Models\ContentSection;
use Illuminate\Database\Seeder;

class ContentSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'slug' => 'hero',
                'title' => 'Amiana Estate',
                'subtitle' => 'A refined standard of contemporary living in Aberdeen, Sierra Leone.',
                'body' => null,
            ],
            [
                'slug' => 'estate-experience',
                'title' => 'The Estate Experience',
                'subtitle' => 'A better standard of estate living',
                'body' => 'Discover a thoughtfully planned residential estate where contemporary design, quality construction, and everyday convenience come together. Every detail is considered to create an environment that feels welcoming, comfortable, and refined.',
            ],
            [
                'slug' => 'design-philosophy',
                'title' => 'Design Philosophy',
                'subtitle' => 'Designed around the way you live',
                'body' => 'We believe exceptional design begins with understanding how people live. Our approach combines contemporary architecture, functional layouts, and carefully considered details to create homes that balance visual appeal with lasting practicality.',
            ],
            [
                'slug' => 'lifestyle',
                'title' => 'Lifestyle',
                'subtitle' => 'Space to live, relax, and thrive',
                'body' => 'Life at Amiana is designed around comfort, convenience, and a sense of belonging. Thoughtfully planned residences and accessible amenities create opportunities to enjoy everyday moments while maintaining a balanced and comfortable lifestyle.',
            ],
            [
                'slug' => 'location',
                'title' => 'Location Advantages',
                'subtitle' => 'Connected to what matters',
                'body' => 'Located in Aberdeen, Sierra Leone, Amiana offers a residential setting with access to the surrounding city and its everyday destinations. The location provides a convenient base for balancing work, leisure, and daily responsibilities.',
            ],
            [
                'slug' => 'amiana-difference',
                'title' => 'The Amiana Difference',
                'subtitle' => 'Quality in every detail',
                'body' => 'Amiana brings together considered design, attention to construction quality, and a commitment to the resident experience. Our goal is to create a distinctive residential environment where comfort, functionality, and enduring value remain central to every detail.',
            ],
        ];

        foreach ($sections as $section) {
            ContentSection::updateOrCreate(
                ['slug' => $section['slug']],
                [
                    'title' => $section['title'],
                    'subtitle' => $section['subtitle'],
                    'body' => $section['body'],
                ]
            );
        }
    }
}
