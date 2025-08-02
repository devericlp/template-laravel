<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\{Builder, Model, Scope};

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $tenant = request()->attributes->get('tenant');

        if (!$tenant) {
            return;
        }

        $builder->where($model->getTable() . '.tenant_id', $tenant->id);
    }
}
