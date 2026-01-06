<?php

namespace App\Livewire\Pages\Users;

use App\Actions\Users\CreateUser;
use App\Enums\Roles;
use App\Traits\Livewire\HasToast;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\{Component, WithFileUploads};

class UserCreate extends Component
{
    use HasToast;
    use WithFileUploads;

    #[Validate(['required', 'min:3', 'max:255'])]
    public ?string $name = null;

    #[Validate(['required', 'email', 'lowercase', 'unique:App\Models\User,email', 'max:255'])]
    public ?string $email = null;

    #[Validate(['required', 'min:8', 'confirmed'])]
    public ?string $password = null;

    #[Validate(['required'])]
    public ?string $password_confirmation = null;

    #[Validate(['required'])]
    public ?int $role_id = null;

    #[Validate(['nullable', 'image', 'max:10240'])]
    public $avatar;

    public array $roles = [];

    public function store(): void
    {
        $this->validate();

        (new CreateUser())->handle($this->only('name', 'email', 'password', 'role_id', 'avatar'));

        $this->success(__('messages.user_created_successfully'));

        $this->redirect(route('users.index'));
    }

    public function removeAvatar(): void
    {
        $this->avatar->delete();
        $this->avatar = null;
    }

    public function mount(): void
    {
        $this->roles = Roles::options();
    }

    public function render(): View
    {
        return view('livewire.pages.users.user-create');
    }
}
