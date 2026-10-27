<?php

namespace App\View\Components;

use App\Support\AmenityIcons;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class IconSelect extends Component
{
    /** @var list<array{value: string, label: string, search: string}> */
    public array $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name = 'icon',
        public string $label = 'Icon',
    ) {
        $this->options = collect(AmenityIcons::options())
            ->map(fn(string $text, string $key) => [
                'value' => $key,
                'label' => $text,
                // everything the search box should match: the label, the name and the extra words
                'search' => Str::lower("{$text} {$key} " . AmenityIcons::keywords($key)),
            ])
            ->values()
            ->all();
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.icon-select');
    }
}
