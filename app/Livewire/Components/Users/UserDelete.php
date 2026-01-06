<?php

namespace App\Livewire\Components\Users;

use App\Models\User;
use App\Notifications\UserDeletedNotification;
use App\Traits\Livewire\{HasConfirmation, HasToast};
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class UserDelete extends Component
{
    use HasConfirmation;
    use HasToast;

    public ?User $user = null;

    #[On('confirm-delete-user')]
    public function confirmDelete(?int $userId = null): void
    {

        if ($userId) {
            $this->setUser($userId);
        }

        $this->openConfirmation(
            modalId: 'confirm-delete-user-modal',
            type: 'danger',
            title: __('messages.delete_user'),
            message: __('messages.are_you_sure_you_want_to_delete_the_user', ['user' => $this->user->name]),
            callback: 'deleteUser',
        );
    }

    #[On('confirm-delete-selected-users')]
    public function confirmbulkDeleteUsers(array $selected)
    {
        $this->openConfirmation(
            modalId: 'confirm-delete-user-modal',
            type: 'danger',
            title: __('messages.delete_selected'),
            message: __('messages.are_you_sure_you_want_to_delete_the_selected_records'),
            callback: 'bulkDeleteUsers',
            params: [$selected]
        );
    }

    public function bulkDeleteUsers(array $selected)
    {
        User::query()->whereIn('id', $selected)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'deleted_by' => Auth::user()->id,
                'restored_at' => null,
                'restored_by' => null,
            ]);

        $this->success(__('messages.selected_records_successfully_deleted'));

        $this->dispatch('bulk-action::completed');
    }

    public function deleteUser(): void
    {
        $this->user->delete();
        $this->user->deleted_by = Auth::user()->id;
        $this->user->restored_at = null;
        $this->user->restored_by = null;
        $this->user->save();

        $this->user->notify(new UserDeletedNotification);

        $this->success(__('messages.user_deleted_successfully'));

        $this->dispatch('user::deleted');
    }

    public function setUser(int $userId): void
    {
        $this->user = User::find($userId);
    }

    public function mount(?int $userId = null): void
    {
        if ($userId) {
            $this->setUser($userId);
        }
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <div>
                <x-modal-confirmation
                    wire:model="showConfirmation"
                    id="confirm-delete-user-modal"
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
