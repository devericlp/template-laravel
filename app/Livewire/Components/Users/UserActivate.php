<?php

namespace App\Livewire\Components\Users;

use App\Enums\Status;
use App\Models\User;
use App\Traits\Livewire\{HasConfirmation, HasToast};
use Closure;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class UserActivate extends Component
{
    use HasConfirmation;
    use HasToast;

    #[On('confirm-activate-selected-users')]
    public function confirmbulkActivateUsers(array $selected)
    {
        $this->openConfirmation(
            modalId: 'confirm-activate-user-modal',
            type: 'warning',
            title: __('messages.activate_selected'),
            message: __('messages.are_you_sure_you_want_to_activate_the_selected_records'),
            callback: 'bulkActivateUsers',
            params: [$selected]
        );
    }

    public function bulkActivateUsers(array $selected)
    {
        User::query()
            ->withTrashed()
            ->whereIn('id', $selected)
            ->update([
                'status' => Status::ACTIVE->value
            ]);

        $this->success(__('messages.selected_records_successfully_activated'));

        $this->dispatch('bulk-action::completed');
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <div>
                <x-modal-confirmation
                    wire:model="showConfirmation"
                    id="confirm-activate-user-modal"
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
