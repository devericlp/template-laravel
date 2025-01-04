<?php

use App\Enums\Can;
use App\Livewire\Admin\Users\Index;
use App\Models\{Permission, User};
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

it("should be able to access the route admin/users", function () {
    actingAs(User::factory()->admin()->create());

    get(route('admin.users'))->assertOk();
});

test("making sure that the route is protected by the permission BE_AN_ADMIN", function () {
    actingAs(User::factory()->create());

    get(route('admin.users'))->assertForbidden();
});

it("let's create a livewire component to list all users in the page", function () {
    actingAs(User::factory()->admin()->create());

    $users = User::factory()->count(10)->create();

    $lw = Livewire::test(Index::class);

    $lw->assertSet('users', function ($users) {
        expect($users)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(11);

        return true;
    });

    foreach ($users as $user) {
        $lw->assertSee($user->name);
    }
});

test('check the table headers', function () {
    actingAs(User::factory()->admin()->create());

    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permissions', 'label' => 'Permissions'],
        ]);
});

it('should be able to filter by name and email', function () {
    $admin      = User::factory()->admin()->create(['name' => 'John Doe', 'email' => 'john@doe.com']);
    $searchUser = User::factory()->create(['name' => 'Search Guy', 'email' => 'search-guy@email.com']);

    actingAs($admin);

    Livewire::test(Index::class)
        ->assertSet('users', function ($users) {
            expect($users)->toHaveCount(2);

            return true;
        })
        ->set('search', 'Search Guy')
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(1)
                ->first()->name->toBe('Search Guy');

            return true;
        })
        ->set('search', 'search-guy@email.com')
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(1)
                ->first()->email->toBe('search-guy@email.com');

            return true;
        });
});

it('should be able to filter by permission key', function () {
    $admin      = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'joe@doe.com']);
    $searchUser = User::factory()->create(['name' => 'Search Guy', 'email' => 'search-guy@email.com']);
    $permission = Permission::where('key', Can::BE_AN_ADMIN->value)->first();

    actingAs($admin);

    Livewire::test(Index::class)
        ->assertSet('users', function ($users) {
            expect($users)->toHaveCount(2);

            return true;
        })
        ->set('search_permissions', [$permission->id])
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(1)
                ->first()->name->toBe('Joe Doe');

            return true;
        });
});
