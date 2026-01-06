<?php

namespace Database\Factories;

use App\Enums\{Roles, Status};
use App\Models\{User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->userName() . fake()->numberBetween(1, 9999) . '@example.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => fake()->dateTime(),
            'status' => fake()->randomElement([Status::ACTIVE->value, Status::INACTIVE->value]),
        ];
    }

    /**
     * Hook after the model is created.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->syncRoles(Roles::USER->label());
        });
    }

    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->syncRoles(Roles::ADMIN->label());
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Assign permissions to the user.
     */
    public function withPermission(array|string $permission): static
    {
        return $this->afterCreating(function (User $user) use ($permission) {
            $user->givePermissionTo($permission);
        });
    }

    /**
     * Indicate that the model should have a validation code.
     */
    public function withValidationCode(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
            'validation_code' => random_int(100000, 999999),
        ]);
    }

    /**
     * Indicate that the model is deleted.
     */
    public function deleted($deleted_by = null): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
            'deleted_by' => $deleted_by ?? User::factory()->create()->id,
        ]);
    }
}
