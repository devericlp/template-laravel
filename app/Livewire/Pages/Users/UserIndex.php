<?php

namespace App\Livewire\Pages\Users;

use App\Enums\{FilterType, RecordVisibility, Roles, Status};
use App\Models\{Role, User};
use App\Support\Table\{Filter, Header};
use App\Traits\Livewire\HasTable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class UserIndex extends Component
{
    use HasTable;

    public array $statuses = [];

    public array $visibilities = [];

    public array $roles = [];

    protected function tableHeaders(): array
    {
        return [
            Header::make('name', __('messages.user'), true, true),
            Header::make('email', __('messages.email'), true, true),
            Header::make('role_name', __('messages.role'), false, false),
            Header::make('created_at', __('messages.created_at'), true, true),
            Header::make('status', __('messages.status')),
            Header::make('actions', __('messages.actions')),
        ];
    }

    protected function tableQuery(): Builder
    {
        return User::query()
            ->with(['roles'])
            ->addSelect([
                'role_name' => Role::select('name')
                    ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->whereColumn('model_has_roles.model_id', 'users.id')
                    ->where('model_has_roles.model_type', '=', User::class)
                    ->limit(1),
            ])
            ->when($this->filters['visibility'], fn (Builder $q) => match ($this->filters['visibility']) {
                RecordVisibility::WITH_DELETED->value => $q->withTrashed(),
                RecordVisibility::ONLY_DELETED->value => $q->onlyTrashed(),
            })
            ->when(
                $this->filters['role_id'],
                fn (Builder $q) => $q->whereHas('roles', fn ($query) => $query->where('roles.id', $this->filters['role_id']))
            )
            ->when($this->filters['status'], fn (Builder $q) => $q->where('status', '=', $this->filters['status']))
            ->when($this->filters['start_date'], fn (Builder $q) => $q->where('created_at', '>=', Carbon::parse($this->filters['start_date'])->startOfDay()))
            ->when($this->filters['end_date'], fn (Builder $q) => $q->where('created_at', '<=', Carbon::parse($this->filters['end_date'])->endOfDay()));
    }

    public function tableFilters(): array
    {
        return [
            Filter::make(
                key: 'visibility',
                type: FilterType::SELECT,
                label: 'visibility',
                resolver: function ($value) {
                    return collect($this->visibilities)->firstWhere('id', $value)['name'];
                }
            ),
            Filter::make(
                key: 'role_id',
                label: 'role',
                type: FilterType::SELECT,
                resolver: function ($value) {
                    return collect($this->roles)->firstWhere('id', $value)['name'];
                }
            ),
            Filter::make(
                key: 'status',
                type: FilterType::SELECT,
                resolver: function ($value) {
                    return collect($this->statuses)->firstWhere('id', $value)['name'];
                }
            ),
            Filter::make(
                key: 'start_date',
                type: FilterType::DATE,
                resolver: function ($value) {
                    return Carbon::parse($value)->format('d/m/Y');
                }
            ),
            Filter::make(
                key: 'end_date',
                type: FilterType::DATE,
                resolver: function ($value) {
                    return Carbon::parse($value)->format('d/m/Y');
                }
            ),
        ];
    }

    public function mount()
    {
        $this->statuses = collect(Status::options())->wherein('id', [Status::ACTIVE->value, Status::INACTIVE->value])->toArray();
        $this->visibilities = RecordVisibility::options();
        $this->roles = Roles::options();
    }

    #[On('bulk-action::completed')]
    #[On('user::restored')]
    #[On('user::deleted')]
    #[On('user::updated')]
    #[On('user::created')]
    public function render(): View
    {
        return view('livewire.pages.users.user-index');
    }
}
