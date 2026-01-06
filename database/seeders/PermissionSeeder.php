<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'users.viewAny']);
        Permission::firstOrCreate(['name' => 'users.view']);
        Permission::firstOrCreate(['name' => 'users.create']);
        Permission::firstOrCreate(['name' => 'users.update']);
        Permission::firstOrCreate(['name' => 'users.delete']);
        Permission::firstOrCreate(['name' => 'users.restore']);
        Permission::firstOrCreate(['name' => 'users.impersonate']);

        Permission::firstOrCreate(['name' => 'roles.viewAny']);
        Permission::firstOrCreate(['name' => 'roles.view']);
        Permission::firstOrCreate(['name' => 'roles.create']);
        Permission::firstOrCreate(['name' => 'roles.update']);
        Permission::firstOrCreate(['name' => 'roles.delete']);

        Permission::firstOrCreate(['name' => 'permissions.viewAny']);
        Permission::firstOrCreate(['name' => 'permissions.create']);
        Permission::firstOrCreate(['name' => 'permissions.update']);
        Permission::firstOrCreate(['name' => 'permissions.delete']);
    }
}
