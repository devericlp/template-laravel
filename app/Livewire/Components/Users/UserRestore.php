<?php

namespace App\Livewire\Components\Users;

use App\Models\User;
use App\Notifications\UserRestoredNotification;
use App\Traits\Livewire\{HasConfirmation, HasToast};
use Closure;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class UserRestore extends Component
{
    use HasConfirmation;
    use HasToast;

    public ?User $user = null;

    #[On('confirm-restore-user')]
    public function confirmRestore(?int $userId = null): void
    {

        if ($userId) {
            $this->setUser($userId);
        }

        $this->openConfirmation(
            modalId: 'confirm-restore-user-modal',
            type: 'warning',
            title: __('messages.restore_user'),
            message: __('messages.are_you_sure_you_want_to_restore_the_user', ['user' => $this->user->name]),
            callback: 'restoreUser',
        );
    }

    public function restoreUser(): void
    {
        $this->user->restore();
        $this->user->deleted_by = null;
        $this->user->restored_at = now();
        $this->user->restored_by = auth()->user()->id;
        $this->user->save();

        $this->user->notify(new UserRestoredNotification);

        $this->success(__('messages.user_restored_successfully'));

        $this->dispatch('user::restored');
    }

    #[On('confirm-restore-selected-users')]
    public function confirmbulkRestoreUsers(array $selected)
    {
        $this->openConfirmation(
            modalId: 'confirm-restore-user-modal',
            type: 'danger',
            title: __('messages.restore_selected'),
            message: __('messages.are_you_sure_you_want_to_restore_the_selected_records'),
            callback: 'bulkRestoreUsers',
            params: [$selected]
        );
    }

    public function bulkRestoreUsers(array $selected)
    {
        User::query()
            ->withTrashed()
            ->whereIn('id', $selected)
            ->whereNotNull('deleted_at')
            ->update([
                'deleted_at' => null,
                'deleted_by' => null,
                'restored_at' => now(),
                'restored_by' => auth()->user()->id,
            ]);

        $this->success(__('messages.selected_records_successfully_restored'));

        $this->dispatch('bulk-action::completed');
    }

    public function setUser(int $userId): void
    {
        $this->user = User::withTrashed()->find($userId);
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
                    id="confirm-restore-user-modal"
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
