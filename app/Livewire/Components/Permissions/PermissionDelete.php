<?php

namespace App\Livewire\Components\Permissions;

use App\Models\Permission;
use App\Traits\Livewire\{HasConfirmation, HasToast};
use Closure;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class PermissionDelete extends Component
{
    use HasConfirmation;
    use HasToast;

    public ?Permission $permission = null;

    #[On('confirm-delete-permission')]
    public function confirmDelete(?int $permissionId = null): void
    {
        if ($permissionId) {
            $this->setPermission($permissionId);
        }

        $this->openConfirmation(
            modalId: 'confirm-delete-permission-modal',
            type: 'danger',
            title: __('messages.delete_permission'),
            message: __('messages.are_you_sure_you_want_to_delete_the_permission', ['permission' => $this->permission->name]),
            callback: 'deletePermission',
        );
    }

    public function deletePermission(): void
    {
        if ($this->permission->roles()->count() > 0) {
            $this->addError('permission_belongs_to_roles', __('messages.cannot_delete_permission_with_assigned_to_roles'));
            $this->danger(__('messages.cannot_delete_permission_with_assigned_to_roles'));
            return;
        }

        $this->permission->delete();

        $this->success(__('messages.permission_deleted_successfully'));
        $this->dispatch('permission::deleted');
    }

    public function setPermission(int $permissionId): void
    {
        $this->permission = Permission::find($permissionId);
    }

    public function mount(?int $permissionId = null): void
    {
        if ($permissionId) {
            $this->setPermission($permissionId);
        }
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <div>
                <x-modal-confirmation
                    wire:model="showConfirmation"
                    id="confirm-delete-permission-modal"
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
