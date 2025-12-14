<?php

namespace App\View\Components;

use Illuminate\View\{Component, View};

/**
 * Class ImageUpload
 *
 * This class represents a Blade component for image uploads with three visual states:
 * 1. Temporary uploaded file (user-selected file)
 * 2. Initial image (existing image in edit forms)
 * 3. Placeholder / empty (clickable default image)
 *
 * The component works with Alpine.js to handle local removal of the initial image
 * without calling any backend logic until the form is submitted.
 *
 * The Blade handles rendering the overlay, hover text, and conditional UI.
 */
class ImageUpload extends Component
{
    /**
     * Create a new component instance.
     *
     * @param  string  $model  The property name for the input binding.
     * @param  string  $removeAction  The remove action for temporary uploads (Livewire).
     * @param  string  $accept  Accepted file types (default: image/*).
     * @param  string|null  $initialImage  The URL of the initial image.
     * @param  string|null  $placeholder  The URL of the placeholder image.
     * @param  string  $heigthClass  The CSS class for height (default: h-40).
     */
    public function __construct(
        public string $model,
        public string $removeAction = 'removeImage',
        public string $accept = 'image/*',
        public ?string $initialImage = null,
        public ?string $placeholder = null,
        public string $heigthClass = 'h-40',
    ) {
        //
    }

    public function render(): View
    {
        return view('components.image-upload');
    }
}
