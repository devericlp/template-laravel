<?php

namespace App\Livewire\Auth;

use App\Traits\Livewire\HasToast;
use Illuminate\Support\Facades\{Auth, RateLimiter};
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\{Layout, On, Validate};
use Livewire\Component;

class Login extends Component
{
    use HasToast;

    #[Validate(['required', 'email', 'max:255'])]
    public ?string $email = null;

    #[Validate(['required', 'max:255'])]
    public ?string $password = null;

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        if ($this->ensureIsNotRateLimiting()) {
            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {

            RateLimiter::hit($this->throttleKey());

            $this->addError('invalidCredentials', __('auth.failed'));
            $this->warning(__('auth.failed'));

            return;
        }

        $this->redirect(route('home'));
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
            $this->warning($error_message);

            return true;
        }

        return false;
    }

    #[On('show-toast')]
    public function showToast(string $type, string $message): void
    {
        $this->$type($message);
    }

    public function mount(): void
    {
        if (session()->has('toast')) {
            $this->dispatch('show-toast', type: session('toast')['type'], message: session('toast')['message']);
        }
    }

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.login')
            ->title(__('messages.sign_in'));
    }
}
