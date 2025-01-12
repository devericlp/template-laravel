<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Attributes\{On, Rule};
use Livewire\Component;

class Impersonate extends Component
{
    #[Rule(['accepted'])]
    public bool $confirmedImpersonation = false;

    public ?User $user = null;

    public bool $modal = false;

    public function render()
    {
        return view('livewire.admin.users.impersonate');
    }

    public function impersonate(): void
    {
        $this->validate();
        session()->put('impersonate', $this->user->id);
        session()->put('impersonator', auth()->user()->id);
        $this->redirect(route('dashboard'));
    }

    #[On('user::impersonation')]
    public function openConfimation(int $userId): void
    {
        $this->user  = User::select('id', 'name')->find($userId);
        $this->modal = true;
    }

    public function confirmImpersonation(): void
    {
        $this->confirmedImpersonation = true;
        $this->impersonate();
    }
}
