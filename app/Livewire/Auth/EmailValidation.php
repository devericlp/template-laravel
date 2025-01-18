<?php

namespace App\Livewire\Auth;

use App\Events\SendNewCode;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use closure;
use Illuminate\View\View;
use Livewire\Component;

class EmailValidation extends Component
{
    public ?string $code = null;

    public function render(): View
    {
        return view('livewire.auth.email-validation');
    }

    public function handle(): void
    {
        $this->validate([
            'code' => function (string $attribute, mixed $value, Closure $fail) {
                if ($value != auth()->user()->validation_code) {
                    $fail('Invalid code');
                }
            },
        ]);

        /* @var User $user */
        $user                    = auth()->user();
        $user->email_verified_at = now();
        $user->validation_code   = null;
        $user->save();

        $user->notify(new WelcomeNotification());

        $this->redirect(route('dashboard'));
    }

    public function sendNewCode(): void
    {
        SendNewCode::dispatch(auth()->user());
    }
}
