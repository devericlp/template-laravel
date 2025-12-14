<?php

namespace App\Livewire\Pages\Auth;

use App\Events\SendNewCode;
use App\Notifications\WelcomeNotification;
use App\Rules\CodeValidation;
use App\Traits\Livewire\HasToast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\{Layout, On, Validate};
use Livewire\Component;

class EmailValidation extends Component
{
    use HasToast;

    #[Validate(['required', new CodeValidation])]
    public ?string $code = null;

    public ?Carbon $dateResendAllowed = null;

    public bool $welcome = false;

    public function handle(): void
    {
        $this->validate();

        try {

            $user = Auth::user();

            $user->email_verified_at = now();
            $user->validation_code = null;
            $user->save();

            if ($this->welcome) {
                $user->notify(new WelcomeNotification);
            }

            $this->redirect(route('home'));
        } catch (\Exception $e) {
            $error_message = __('messages.invalid_code');
            $this->addError('invalidCode', $error_message);
            $this->warning($error_message);
        }
    }

    public function sendNewCode(): void
    {
        if ($this->dateResendAllowed && now()->isBefore($this->dateResendAllowed)) {
            $error_message = __('messages.resend_code_attempt', ['number' => '3']);
            $this->addError('dateResendNotAllowed', $error_message);
            $this->warning($error_message);

            return;
        }

        SendNewCode::dispatch(Auth::user());
        $this->success(__('messages.code_sent_successfully'));

        $this->dateResendAllowed = now()->addMinutes(3);
    }

    #[On('resend-allowed')]
    public function resendAllowed(): void
    {
        $this->dateResendAllowed = null;
    }

    public function mount(): void
    {
        if (isset(request()->welcome)) {
            $this->welcome = true;
        }
    }

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.email-validation');
    }
}
