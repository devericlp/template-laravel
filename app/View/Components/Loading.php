<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Loading extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'BLADE'
        <div  wire:loading>
        <div class="fixed h-screen w-full inset-0 z-[99] flex flex-col items-center justify-center">
            <flux:icon.loading class="w-[60px]" />
                <div class="mt-2 text-lg">
                    {{ __('messages.loading') }}...
                </div>
            </div>
        </div>
        BLADE;
    }
}
