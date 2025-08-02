<?php

namespace App\Traits\Models;

use App\Models\Scopes\TenantScope;
use App\Observers\TenantObserver;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);
        static::observe(new TenantObserver);
    }
}
