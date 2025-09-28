<?php

use Database\Seeders\LocationSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Artisan::call('db:seed', [
            'class' => LocationSeeder::class
        ]);
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
