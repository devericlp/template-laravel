<?php

namespace App\Livewire\Pages\Users;

use App\Actions\Users\UpdateUser;
use App\Enums\Roles;
use App\Models\{User};
use App\Traits\Livewire\HasToast;
use Illuminate\View\View;
use Livewire\{Component, Features\SupportFileUploads\TemporaryUploadedFile, WithFileUploads};

class UserUpdate extends Component
{
    use HasToast;
    use WithFileUploads;

    public User $user;

    public ?string $name = null;

    public ?string $email = null;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public ?int $role_id = null;

    public $avatar;

    public array $roles = [];

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'lowercase',
                'max:255',
                'unique:App\Models\User,email,' . $this->user->id,
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required'],
            'avatar' => ['nullable', 'image', 'max:10240'],
        ];
    }

    public function update(): void
    {
        $this->validate();

        (new UpdateUser)->handle($this->user, $this->only('name', 'email', 'password', 'role_id', 'avatar'));

        $this->success(__('messages.user_updated_successfully'));

        $this->redirect(route('users.index'));
    }

    public function removeAvatar(): void
    {
        if ($this->avatar instanceof TemporaryUploadedFile) {
            $this->avatar->delete();
        }
        $this->avatar = null;
    }

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->roles = Roles::options();

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->role_id = $this->user->roles()->first()->id;
    }

    public function render(): View
    {
        return view('livewire.pages.users.user-update');
    }
}
