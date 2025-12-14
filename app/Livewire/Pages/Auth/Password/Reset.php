<?php

namespace App\Livewire\Pages\Auth\Password;

use App\Models\User;
use App\Traits\Livewire\HasToast;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\{DB, Hash, Password};
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\{Validate};
use Livewire\Component;

class Reset extends Component
{
    use HasToast;

    #[Validate(['required'])]
    public ?string $token = null;

    #[Validate(['required'])]
    public ?string $email = null;

    #[Validate(['required', 'confirmed'])]
    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function changePassword(): void
    {
        $this->validate();

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->password = $password;
                $user->remember_token = Str::random(60);
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return;
        }

        session()->flash('toast', [
            'type' => 'success',
            'message' => __('messages.password_updated_successfully')
        ]);
        $this->redirectRoute('login');
    }

    private function tokenIsNotValid(): bool
    {
        $tokens = DB::table('password_reset_tokens')->get();

        foreach ($tokens as $t) {
            if (Hash::check($this->token, $t->token)) {
                return false;
            }
        }

        return true;
    }

    public function mount(?string $token = null, ?string $email = null): void
    {
        $this->token = request('token', $token);
        $this->email = request('email', $email);

        if ($this->tokenIsNotValid()) {
            session()->flash('toast', [
                'type' => 'danger',
                'message' => __('messages.invalid_password_reset_link')
            ]);
            $this->redirectRoute('login');
        }
    }

    public function render(): view
    {
        return view('livewire.auth.password.reset')
            ->title('Reset Password')
            ->layout('components.layouts.guest');
    }
}
