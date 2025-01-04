<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use App\Models\{Permission, User};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\{Attributes\Rule, Component, WithPagination};

/**
 * @property-read LengthAwarePaginator|User[] $users
 * @property-read  array $headers
 */
class Index extends Component
{
    use WithPagination;

    public ?string $search = null;

    #[Rule('exists:permissions,id')]
    public array $search_permissions = [];

    public bool $search_trash = false;

    public Collection $permissions_to_search;

    public string $sortColumnBy = 'id';

    public string $sortDirection = 'asc';

    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
        $this->filterPermissions();
    }

    public function render(): View
    {
        return view('livewire.admin.users.index');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        $this->validate();

        return User::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q
                    ->where(DB::raw('lower(name)'), 'like', '%' . strtolower($this->search) . '%')
                    ->orWHere(DB::raw('lower(email)'), 'like', '%' . strtolower($this->search) . '%')
            )
            ->when(
                $this->search_permissions,
                fn (Builder $q) => $q->whereHas('permissions', function (Builder $query) {
                    $query->whereIn('id', $this->search_permissions);
                })
            )
            ->when(
                $this->search_trash,
                fn (Builder $q) => $q->onlyTrashed() /** @phpstan-ignore-line  */
            )
           ->orderBy($this->sortColumnBy, $this->sortDirection)
            ->paginate();
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'created_at', 'label' => 'Created at'],
            ['key' => 'permissions', 'label' => 'Permissions'],
        ];
    }

    public function filterPermissions(?string $value = null): void
    {
        $this->permissions_to_search = Permission::query()
            ->when(
                $value,
                fn (Builder $q) => $q->where('key', 'like', '%' . $value . '%')
            )
            ->orderBy('key')
            ->get();
    }
}
