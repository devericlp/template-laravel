<?php

use App\Livewire\Pages\Tenants\TenantCreate;
use App\Models\Tenant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{Http, Storage};
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('renders successfully', function () {
    Livewire::test(TenantCreate::class)
        ->assertStatus(200);
});

it('should allow to create a tenant', function () {
    Livewire::test(TenantCreate::class)
        ->set('social_reason', 'Tenant test')
        ->set('identification_number', '99.999.999/9999-99')
        ->set('subdomain', 'subdomain_test')
        ->set('zipcode', '99.999-99')
        ->set('street', 'street test')
        ->set('neighborhood', 'neighborhood test')
        ->set('city', 'city test')
        ->set('state', 'state test')
        ->set('country', 'country test')
        ->set('number', '123')
        ->set('cellphone', '(99) 9 9999-9999')
        ->set('email', 'tenant-test@email.com')
        ->call('store')
        ->assertHasNoErrors()
        ->assertRedirect(route('tenants.index'));

    assertDatabaseCount('tenants', 1);
    assertDatabaseHas('tenants', [
        'social_reason' => 'Tenant test',
        'identification_number' => '99999999999999',
        'subdomain' => 'subdomain_test',
        'zipcode' => '9999999',
        'street' => 'street test',
        'neighborhood' => 'neighborhood test',
        'city' => 'city test',
        'state' => 'state test',
        'country' => 'country test',
        'number' => '123',
        'cellphone' => '99999999999',
        'email' => 'tenant-test@email.com',
    ]);
});

it('should create a tenant with logo', function () {
    Storage::fake('public');

    $logo = UploadedFile::fake()->image('logo_tenant.png');

    Livewire::test(TenantCreate::class)
        ->set('social_reason', 'Tenant test')
        ->set('identification_number', '99.999.999/9999-99')
        ->set('subdomain', 'subdomain_test')
        ->set('zipcode', '99.999-99')
        ->set('street', 'street test')
        ->set('neighborhood', 'neighborhood test')
        ->set('city', 'city test')
        ->set('state', 'state test')
        ->set('country', 'country test')
        ->set('number', '123')
        ->set('cellphone', '(99) 9 9999-9999')
        ->set('email', 'tenant-test@email.com')
        ->set('logo', $logo)
        ->call('store')
        ->assertHasNoErrors()
        ->assertRedirect(route('tenants.index'));

    assertDatabaseCount('tenants', 1);
    assertDatabaseHas('tenants', [
        'social_reason' => 'Tenant test',
        'identification_number' => '99999999999999',
        'subdomain' => 'subdomain_test',
        'zipcode' => '9999999',
        'street' => 'street test',
        'neighborhood' => 'neighborhood test',
        'city' => 'city test',
        'state' => 'state test',
        'country' => 'country test',
        'number' => '123',
        'cellphone' => '99999999999',
        'email' => 'tenant-test@email.com',
    ]);

    $tenant = Tenant::first();
    expect($tenant)->logo->not->toBe(null);
    Storage::disk('public')->assertExists($tenant->logo);

});

it('should search the address by zipcode', function () {
    Http::fake([
        'https://viacep.com.br/ws/41192320/*' => Http::response([
            'cep' => '41192320',
            'logradouro' => 'street test',
            'bairro' => 'neighborhood test',
            'uf' => 'ES',
            'localidade' => 'city test',
            'complemento' => 'complement test',
        ], 200),
        'https://viacep.com.br/ws/00000000/*' => Http::response([], 400),
    ]);

    $lw = Livewire::test(TenantCreate::class);

    $lw->set('zipcode', '41192320')
        ->call('searchAddress')
        ->assertSet('street', 'street test')
        ->assertSet('neighborhood', 'neighborhood test')
        ->assertSet('state', 'ES')
        ->assertSet('city', 'city test')
        ->assertSet('country', 'Brasil')
        ->assertSet('complement', 'complement test');

    $lw->set('zipcode', '00000000')
        ->call('searchAddress')
        ->assertSet('street', null)
        ->assertSet('neighborhood', null)
        ->assertSet('state', null)
        ->assertSet('city', null)
        ->assertSet('country', null)
        ->assertSet('complement', null);

});

test('validate required fields', function ($field) {
    Livewire::test(TenantCreate::class)
        ->set($field, '')
        ->assertHasErrors([$field => 'required']);
})->with([
    'social_reason',
    'identification_number',
    'subdomain',
    'zipcode',
    'street',
    'neighborhood',
    'city',
    'state',
    'country',
    'cellphone',
    'email',
]);

test('validate unique fields', function ($field) {
    $existent_tenant = Tenant::factory()->create([$field => 'existent-value']);
    Livewire::test(TenantCreate::class)
        ->set($field, 'existent-value')
        ->call('store')
        ->assertHasErrors([$field => 'unique']);
})->with([
    'social_reason',
    'identification_number',
    'subdomain',
]);
