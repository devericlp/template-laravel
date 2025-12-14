<?php

namespace App\Livewire\Pages\Settings;

use Illuminate\View\View;
use Livewire\Component;

class SettingsIndex extends Component
{
    public string $tab = 'preferences';

    public function mount(string $tab = 'preferences'): void
    {
        if (!in_array($tab, ['preferences', 'password', 'profile'])) {
            abort(404);
        }

        $this->tab = $tab;
    }

    public function render(): View
    {
        return view('livewire.pages.settings.settings-index');
    }
}
