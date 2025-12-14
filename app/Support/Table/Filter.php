<?php

namespace App\Support\Table;

use App\Enums\FilterType;
use Closure;

readonly class Filter
{
    public function __construct(
        public string     $key,
        public FilterType $type,
        public ?string     $label = null,
        public ?Closure   $resolver = null,
    ) {}

    public static function make(
        string     $key,
        FilterType $type,
        ?string     $label = null,
        ?Closure   $resolver = null
    ): self {
        return new self($key, $type, $label, $resolver);
    }
}
