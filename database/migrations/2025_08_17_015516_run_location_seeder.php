<?php

use Database\Seeders\LocationSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\{Artisan, DB, Schema};

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!app()->environment('testing')) {
            Artisan::call('db:seed', [
                'class' => LocationSeeder::class
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('countries')) {
            DB::table('countries')->truncate();
        }

        if (Schema::hasTable('states')) {
            DB::table('states')->truncate();
        }

        if (Schema::hasTable('cities')) {
            DB::table('cities')->truncate();
        }
    }
};
