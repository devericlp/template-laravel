<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\{Arr, Str};
use Illuminate\View\Component;

class Datatable extends Component
{
    public string $id;

    public string $search;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?LengthAwarePaginator $items,
        public ?array                $headers,
        public ?array                $filters = null,
        public ?array                $pageLengths = null,
        public ?int                  $perPage = null,
        public ?string               $sortBy = null,
        public ?string               $sortDirection = null,
        public string                $recordKey = 'id',
        string               $search = '',
        public ?string              $link = null,
        public ?bool                $noHover = false,
        public ?bool                $selectable = false,
        public ?array               $rowDecoration = [],
        // slots
        public mixed                $fields = null,
        public mixed                $bulkActions = null,
    ) {
        $this->search = $search;
        $this->id = md5(uniqid((string)time(), true));
        $ids = $this->getAllIds();
    }

    public function hasLink(mixed $header): bool
    {
        return $this->link && empty($header['disableLink']);
    }

    public function redirectLink(mixed $row): string
    {
        $link = $this->link;

        // Transform from `route()` pattern
        $link = Str::of($link)->replace('%5B', '{')->replace('%5D', '}');

        // Extract tokens like {id}, {city.name} ...
        $tokens = Str::of($link)->matchAll('/\{(.*?)\}/');

        // Replace tokens by actual row values
        $tokens->each(function (string $token) use ($row, &$link) {
            $link = Str::of($link)->replace("{" . $token . "}", data_get($row, $token))->toString();
        });

        return $link;
    }

    public function getAllIds(): array
    {
        if (is_array($this->items)) {
            return collect($this->items)->pluck($this->recordKey)->all();
        }

        return $this->items->pluck($this->recordKey)->all() ?? [];
    }

    public function rowClasses(mixed $row): ?string
    {
        $classes = [];

        foreach ($this->rowDecoration as $class => $condition) {
            if ($condition($row)) {
                $classes[] = $class;
            }
        }

        return Arr::join($classes, ' ');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datatable');
    }
}
