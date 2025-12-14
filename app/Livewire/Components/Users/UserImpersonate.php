<?php

namespace App\Livewire\Components\Users;

use App\Models\User;
use App\Traits\Livewire\{HasConfirmation, HasToast};
use Closure;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class UserImpersonate extends Component
{
    use HasConfirmation;
    use HasToast;

    public ?User $user = null;

    #[On('confirm-impersonate-user')]
    public function confirmImpersonate(?int $userId = null): void
    {
        if ($userId) {
            $this->setUser($userId);
        }

        if (!$this->user) {
            $errorMessage = __('messages.user_not_found');
            $this->addError('user_not_found', $errorMessage);
            $this->danger($errorMessage);

            return;
        }

        if (session()->has('impersonate')) {
            $errorMessage = __('messages.you_are_already_impersonating_a_user');
            $this->addError('you_are_already_impersonating_a_user', $errorMessage);
            $this->danger($errorMessage);

            return;
        }

        if (auth()->id() === $this->user->id) {
            $errorMessage = __('messages.you_cannot_impersonate_yourself');
            $this->addError('you_cannot_impersonate_yourself', $errorMessage);
            $this->danger($errorMessage);

            return;
        }

        if ($this->user->trashed()) {
            $errorMessage = __('messages.you_cannot_impersonate_a_deleted_user');
            $this->addError('you_cannot_impersonate_a_deleted_user', $errorMessage);
            $this->danger($errorMessage);

            return;
        }

        $this->openConfirmation(
            modalId: 'confirm-impersonate-user-modal',
            type: 'warning',
            title: __('messages.impersonate_user'),
            message: __('messages.are_you_sure_you_want_to_impersonate_the_user', ['user' => $this->user->name]),
            callback: 'impersonateUser',
        );
    }

    public function impersonateUser(): void
    {
        session()->put('impersonate', $this->user->id);
        session()->put('impersonator', auth()->user()->id);
        $this->redirect(route('home'));
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
                    id="confirm-impersonate-user-modal"
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
