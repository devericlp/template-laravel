<?php

namespace App\Support\Table;

use Closure;

readonly class Header
{
    public function __construct(
        public string   $key,
        public string   $label,
        public bool     $sortable = false,
        public bool     $searchable = false,
        public bool     $disableLink = false,
        public string   $align = 'start',
        public ?Closure $sort = null,
        public ?Closure $filter = null,
    ) {
        //
    }

    public static function make(
        string   $key,
        string   $label,
        bool     $sortable = false,
        bool     $searchable = false,
        bool     $disableLink = false,
        string   $align = 'start',
        ?Closure $sort = null,
        ?Closure $filter = null
    ): self {
        return new self($key, $label, $sortable, $searchable, $disableLink, $align, $sort, $filter);
    }
}
