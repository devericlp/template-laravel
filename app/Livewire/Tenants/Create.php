<?php

namespace App\Livewire\Tenants;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Actions\{SearchAddress, SearchCities, SearchStates};
use App\Models\Tenant;
use App\Traits\Livewire\HasToast;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\{Component, WithFileUploads};

class Create extends Component
{
    use HasToast;
    use WithFileUploads;

    #[Validate(['nullable', 'image', 'max:1024'])]
    public $logo;

    #[Validate(['required', 'max:255', 'unique:tenants,identification_number'])]
    public ?string $identification_number = null;

    #[Validate(['required', 'max:255', 'unique:tenants,social_reason'])]
    public ?string $social_reason = null;

    #[Validate(['required', 'max:255', 'unique:tenants,subdomain'])]
    public ?string $subdomain = null;

    #[Validate(['required', 'max:255'])]
    public ?string $zipcode = null;

    #[Validate(['required', 'max:255'])]
    public ?string $street = null;

    #[Validate(['required', 'max:255'])]
    public ?string $neighborhood = null;

    #[Validate(['required', 'max:255'])]
    public ?string $city = null;

    #[Validate(['required', 'max:255'])]
    public ?string $state = null;

    #[Validate(['required', 'max:255'])]
    public ?string $country = null;

    #[Validate(['max:255'])]
    public ?int $number = null;

    public ?string $complement = null;

    #[Validate(['required', 'max:255', 'email'])]
    public ?string $email = null;

    #[Validate(['required', 'max:255'])]
    public ?string $cellphone = null;

    // selects

    public array $states = [];

    public array $cities = [];

    public array $countries = [];

    public function store(): void
    {
        $this->validate();

        $tenant = new Tenant;

        $tenant->social_reason         = $this->social_reason;
        $tenant->identification_number = only_numerics($this->identification_number);
        $tenant->subdomain             = $this->subdomain;
        $tenant->zipcode               = only_numerics($this->zipcode);
        $tenant->street                = $this->street;
        $tenant->neighborhood          = $this->neighborhood;
        $tenant->city                  = $this->city;
        $tenant->state                 = $this->state;
        $tenant->country               = $this->country;
        $tenant->number                = $this->number ?? null;
        $tenant->complement            = $this->complement ?? null;
        $tenant->cellphone             = only_numerics($this->cellphone);
        $tenant->email                 = strtolower($this->email);

        if (! is_null($this->logo)) {
            $path         = $this->logo->store('tenants', 'public');
            $tenant->logo = $path;
        }

        $tenant->save();

        $this->success(__('messages.tenant_created_successfully'));

        $this->redirect(route('tenants.index'));
    }

    public function searchAddress(): void
    {
        $this->resetErrorBag();
        $address = (new SearchAddress)->handle(only_numerics($this->zipcode));

        if ($address) {
            $this->street       = $address['street'];
            $this->neighborhood = $address['neighborhood'];
            $this->city         = $address['city'];
            $this->state        = $address['state'];
            $this->complement   = $address['complement'];
            $this->country      = $address['country'];

            $this->searchCities();
        } else {
            $this->warning(__('messages.address_not_found'));
            $this->reset('street', 'neighborhood', 'city', 'state', 'country', 'cities', 'complement');
        }
    }

    public function searchCities(): void
    {
        $this->resetErrorBag();

        if (is_null($this->state)) {
            return;
        }

        $state = collect($this->states)->firstWhere('code', $this->state);
        $this->cities = City::query()->where('state_id', $state['id'])->get()->toArray();
    }

    public function removeLogo(): void
    {
        $this->reset('logo');
    }

    public function mount(): void
    {
        $this->states = State::all()->toArray();
        $this->countries = Country::all()->toArray();
    }

    public function render(): View
    {
        return view('livewire.tenants.create');
    }
}
