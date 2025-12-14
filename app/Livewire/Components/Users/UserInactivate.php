<?php

namespace App\Livewire\Components\Users;

use App\Enums\Status;
use App\Models\User;
use App\Traits\Livewire\{HasConfirmation, HasToast};
use Closure;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class UserInactivate extends Component
{
    use HasConfirmation;
    use HasToast;

    #[On('confirm-inactivate-selected-users')]
    public function confirmbulkActivateUsers(array $selected)
    {
        $this->openConfirmation(
            modalId: 'confirm-inactivate-user-modal',
            type: 'warning',
            title: __('messages.inactivate_selected'),
            message: __('messages.are_you_sure_you_want_to_inactivate_the_selected_records'),
            callback: 'bulkInactivateUsers',
            params: [$selected]
        );
    }

    public function bulkInactivateUsers(array $selected)
    {
        User::query()
            ->withTrashed()
            ->whereIn('id', $selected)
            ->update([
                'status' => Status::INACTIVE->value
            ]);

        $this->success(__('messages.selected_records_successfully_inactivated'));

        $this->dispatch('bulk-action::completed');
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <div>
                <x-modal-confirmation
                    wire:model="showConfirmation"
                    id="confirm-inactivate-user-modal"
                    :type="$typeConfirmation"
                    :title="$titleConfirmation"
                    :message="$messageConfirmation"
                    :cancel-text="$cancelTextConfirmation"
                    :confirm-text="$confirmTextConfirmation"
                />
            </div>
        HTML;
    }
}
