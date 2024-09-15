<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Login extends Component
{
    #[Rule('required')]
    public ?string $email = null;

    #[Rule('required')]
    public ?string $password = null;

    public function render(): View
    {
        return view('livewire.auth.login')
            ->title('Sign in to your account')
            ->layout('components.layouts.guest');
    }

    public function login(): void
    {
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {

            $this->addError('invalidCredentials', __('auth.failed'));
            return;
        }

        $this->redirect(route('dashboard'));
    }
}
