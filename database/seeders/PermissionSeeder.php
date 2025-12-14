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
        Permission::firstOrCreate(['name' => 'view-user']);
        Permission::firstOrCreate(['name' => 'create-user']);
        Permission::firstOrCreate(['name' => 'edit-user']);
        Permission::firstOrCreate(['name' => 'delete-user']);
        Permission::firstOrCreate(['name' => 'restore-user']);
        Permission::firstOrCreate(['name' => 'impersonate-user']);
    }
}
