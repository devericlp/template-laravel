<?php

namespace App\Livewire\Components\Roles;

use App\Models\Role;
use App\Traits\Livewire\{HasConfirmation, HasToast};
use Closure;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class RoleDelete extends Component
{
    use HasConfirmation;
    use HasToast;

    public ?Role $role = null;

    #[On('confirm-delete-role')]
    public function confirmDelete(?int $roleId = null): void
    {
        if ($roleId) {
            $this->setRole($roleId);
        }

        $this->openConfirmation(
            modalId: 'confirm-delete-role-modal',
            type: 'danger',
            title: __('messages.delete_role'),
            message: __('messages.are_you_sure_you_want_to_delete_the_role', ['role' => $this->role->name]),
            callback: 'deleteRole',
        );
    }

    public function deleteRole(): void
    {
        if ($this->role->users()->count() > 0) {
            $this->addError('role_has_users', __('messages.cannot_delete_role_with_assigned_users'));
            $this->danger(__('messages.cannot_delete_role_with_assigned_users'));
            return;
        }

        $this->role->delete();

        $this->success(__('messages.role_deleted_successfully'));
        $this->dispatch('role::deleted');
    }

    public function setRole(int $roleId): void
    {
        $this->role = Role::find($roleId);
    }

    public function mount(?int $roleId = null): void
    {
        if ($roleId) {
            $this->setRole($roleId);
        }
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <div>
                <x-modal-confirmation
                    wire:model="showConfirmation"
                    id="confirm-delete-role-modal"
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
