<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\{Auth, RateLimiter};
use Flux\Flux;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate(['required', 'email', 'max:255'])]
    public ?string $email = null;

    #[Validate(['required', 'min:8', 'max:255'])]
    public ?string $password = null;

    public function login(): void
    {
        $this->validate();

        if ($this->ensureIsNotRateLimiting()) {
            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {

            RateLimiter::hit($this->throttleKey());

            $this->addError('invalidCredentials', __('auth.failed'));

            Flux::toast(
                text: __('auth.failed'),
                heading: __('messages.warning'),
                variant: 'warning'
            );

            return;
        }

        $this->redirect(route('dashboard'));
    }

    private function throttleKey(): string
    {
        return Str::lower($this->email) . '|' . request()->ip;
    }

    private function ensureIsNotRateLimiting(): bool
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {

            $error_message = __('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey()),
            ]);

            $this->addError('rateLimiter', $error_message);

            Flux::toast(
                text: __('auth.failed'),
                heading: $error_message,
                variant: 'warning'
            );
§
            return true;
        }

        return false;
    }

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.login')
            ->title('Sign in to your account');
    }
}
