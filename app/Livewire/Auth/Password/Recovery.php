<?php

namespace App\Livewire\Auth\Password;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Recovery extends Component
{
    #[Rule(['required', 'email'])]
    public ?string $email = null;

    public ?string $message = null;

    public function render()
    {
        return view('livewire.auth.password.recovery')
            ->title('Password recovery')
            ->layout('components.layouts.guest');
    }

    public function sendEmail()
    {
        $this->validate();

        Password::sendResetLink($this->only('email'));

        $this->message = "You will receive an email with the password recovery link.";
        $this->reset('email');
    }
}
