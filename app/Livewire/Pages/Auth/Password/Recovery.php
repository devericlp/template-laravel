<?php

namespace App\Livewire\Pages\Auth\Password;

use Flux\Flux;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Recovery extends Component
{
    #[Validate(['required', 'email'])]
    public ?string $email = null;

    public function submit(): void
    {
        $this->validate();

        Password::sendResetLink($this->only('email'));

        Flux::toast(
            text: __('messages.success'),
            heading: __('messages.password_recovery_sent_link'),
            variant: 'success'
        );

        $this->reset('email');
    }

    public function render(): view
    {
        return view('livewire.auth.password.recovery')
            ->title('Password recovery')
            ->layout('components.layouts.guest');
    }
}
