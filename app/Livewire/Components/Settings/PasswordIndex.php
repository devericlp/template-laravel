<?php

namespace App\Livewire\Components\Settings;

use App\Traits\Livewire\HasToast;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PasswordIndex extends Component
{
    use HasToast;

    #[Validate(['required'])]
    public ?string $current_password = null;

    #[Validate(['required', 'min:8', 'confirmed'])]
    public ?string $password = null;

    #[Validate(['required'])]
    public ?string $password_confirmation = null;

    public function changePassword(): void
    {
        $this->validate();

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', __('messages.invalid_current_password'));
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset();

        $this->success(__('messages.password_updated_successfully'));
    }

    public function render(): View
    {
        return view('livewire.components.settings.password-index');
    }
}
