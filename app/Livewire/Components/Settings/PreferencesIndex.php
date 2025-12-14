<?php

namespace App\Livewire\Components\Settings;

use App\Traits\Livewire\HasToast;
use Illuminate\Support\Facades\{App, Cookie, Session};
use Illuminate\View\View;
use Livewire\Component;

class PreferencesIndex extends Component
{
    use HasToast;

    public ?string $locale = null;

    public array $supported_locales = [];

    public function updatedLocale($value): void
    {
        if (!in_array($value, $this->supported_locales)) {
            $this->addError('locale', __('messages.invalid_locale'));
            $this->warning(__('messages.invalid_locale'));

            return;
        }

        $this->resetErrorBag();
        $this->locale = $value;
        $this->setLocale($value);
    }

    public function setLocale(string $locale): void
    {
        App::setLocale($locale);
        Session::put('locale', $locale);
        Cookie::queue('locale', $locale, 60 * 24 * 365);
        $this->redirect(request()->header('Referer') ?? url()->current());
    }

    public function mount(): void
    {
        $this->supported_locales = config('app.supported_locales', []);
        $this->locale = App::getLocale();
    }

    public function render(): View
    {
        return view('livewire.components.settings.preferences-index');
    }
}
