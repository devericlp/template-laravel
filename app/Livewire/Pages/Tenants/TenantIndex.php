<?php

namespace App\Livewire\Pages\Tenants;

use App\Enums\{FilterType, Status};
use App\Models\Tenant;
use App\Support\Table\{Filter, Header};
use App\Traits\Livewire\HasTable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\{Component};

class TenantIndex extends Component
{
    use HasTable;

    public array $statuses = [];

    protected function tableQuery(): Builder
    {
        return Tenant::query()
            ->when($this->filters['status'], fn (Builder $q) => $q->where('status', '=', $this->filters['status']))
            ->when($this->filters['start_date'], fn (Builder $q) => $q->where('created_at', '>=', Carbon::parse($this->filters['start_date'])->startOfDay()))
            ->when($this->filters['end_date'], fn (Builder $q) => $q->where('created_at', '<=', Carbon::parse($this->filters['end_date'])->endOfDay()));

    }

    protected function tableHeaders(): array
    {
        return [
            Header::make('id', 'ID', true, true, false, 'center'),
            Header::make('social_reason', __('messages.social_reason'), true, true),
            Header::make('identification_number', __('messages.identification_number'), true, true),
            Header::make('subdomain', __('messages.subdomain'), true, true),
            Header::make('status', __('messages.status'), true, true),
            Header::make('created_at', __('messages.created_at'), true, true),
            Header::make('actions', __('messages.actions'), false, false, true),
        ];
    }

    public function tableFilters(): array
    {
        return [
            Filter::make(
                'start_date',
                FilterType::DATE,
                resolver: function ($value) {
                    return Carbon::parse($value)->format('d/m/Y');
                }
            ),
            Filter::make(
                'end_date',
                FilterType::DATE,
                resolver: function ($value) {
                    return Carbon::parse($value)->format('d/m/Y');
                }
            ),
            Filter::make(
                key: 'status',
                type: FilterType::SELECT,
                resolver: function ($value) {
                    $status = collect($this->statuses)->firstWhere('value', $value)->name;

                    return __('messages.' . strtolower($status));
                }
            ),
        ];
    }

    public function mount()
    {
        $this->statuses = Status::cases();
    }

    public function render(): View
    {
        return view('livewire.pages.tenants.tenant-index');
    }
}
