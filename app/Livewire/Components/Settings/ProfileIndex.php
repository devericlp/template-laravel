<?php

namespace App\Livewire\Components\Settings;

use App\Traits\Livewire\HasToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class ProfileIndex extends Component
{
    use HasToast;

    public ?string $name = null;

    public ?string $email = null;

    public function updateProfile(): void
    {
        $this->validate();

        $user = Auth::user();

        $user->name = $this->name;
        $user->email = $this->email;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->success(__('messages.profile_updated_successfully'));
    }

    public function rules(): array
    {
        $user = Auth::user();

        return [
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'lowercase', 'max:255', Rule::unique('users')->ignore($user->id)],
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function render(): View
    {
        return view('livewire.components.settings.profile-index');
    }
}
