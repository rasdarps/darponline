<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ResponsiveImage extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct( public Media $media,
                                    public string $class = '',
                                    public string $alt = '',
                                    public string $collection = 'images')
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.responsive-image');
    }
}
