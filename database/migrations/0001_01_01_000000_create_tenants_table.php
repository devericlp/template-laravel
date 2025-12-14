<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('fantasy_name');
            $table->string('social_reason');
            $table->string('identification_number')->unique();
            $table->string('subdomain')->unique();
            $table->string('logo')->nullable();

            $table->string('zipcode')->nullable();
            $table->string('street');
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->integer('number')->nullable();
            $table->text('complement')->nullable();

            $table->string('cellphone')->nullable();
            $table->string('email')->nullable();

            $table->tinyInteger('status')->default(Status::ACTIVE->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
