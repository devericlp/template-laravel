<?php

namespace App\Livewire\Pages\Auth;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate(['required', 'min:3', 'max:255'])]
    public ?string $name = null;

    #[Validate(['required', 'email', 'lowercase', 'unique:App\Models\User,email', 'max:255'])]
    public ?string $email = null;

    #[Validate(['required', 'min:8', 'confirmed'])]
    public ?string $password = null;

    #[Validate(['required'])]
    public ?string $password_confirmation = null;

    public function submit(): void
    {
        $this->validate();

        $user = User::query()->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $user->assignRole(Roles::USER->value);

        auth()->login($user);

        Event::dispatch(new Registered($user));

        $this->redirect(route('email-validation') . '?welcome=1');
    }

    public function render(): View
    {
        return view('livewire.auth.register')
            ->title('Sign up to your account')
            ->layout('components.layouts.guest');
    }
}
