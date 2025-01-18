<?php

namespace App\Livewire\Auth;

use App\Events\SendNewCode;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Rules\CodeValidation;
use Illuminate\View\View;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

class EmailValidation extends Component
{
    #[Rule(new CodeValidation())]
    public ?string $code = null;

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.email-validation')->title('Confirm the code sent to your email');
    }

    public function handle(): void
    {
        try {
            $this->validate();

            /* @var User $user */
            $user = auth()->user();

            $user->email_verified_at = now();
            $user->validation_code   = null;
            $user->save();

            $user->notify(new WelcomeNotification());

            $this->redirect(route('dashboard'));
        } catch (\Exception $e) {
            session()->flash('status', $e->getMessage());
        }
    }

    public function sendNewCode(): void
    {
        SendNewCode::dispatch(auth()->user());
    }
}
