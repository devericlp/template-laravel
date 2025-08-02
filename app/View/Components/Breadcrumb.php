<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public array $crumbs = [];

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $routeName = Route::currentRouteName();

        if ($routeName) {
            $segments = explode('.', $routeName);

            foreach ($segments as $index => $segment) {
                $routeKey = implode('.', array_slice($segments, 0, $index + 1));

                $this->crumbs[] = [
                    'label' => $this->formatSegment($segment),
                    'route' => Route::has($routeKey) ? route($routeKey) : null,
                    'active' => $index === array_key_last($segments),
                ];
            }
        }
    }

    private function formatSegment($segment): string
    {
        return ucfirst(str_replace(['-', '_'], ' ', $segment));
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="mt-2">
                <flux:breadcrumbs>
                     <flux:breadcrumbs.item href="{{ route('home') }}">
                            <flux:icon.home class="size-4" />
                     </flux:breadcrumbs.item>
                    @foreach($crumbs as $crumb)
                         <flux:breadcrumbs.item href="{{ $crumb['route'] }}">
                            {{ __('messages.' . strtolower($crumb['label'])) }}
                         </flux:breadcrumbs.item>
                    @endforeach
                </flux:breadcrumbs>
            </div>
        blade;
    }
}
