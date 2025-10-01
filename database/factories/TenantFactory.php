<?php

namespace Database\Factories;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'social_reason'         => fake()->company,
            'identification_number' => fake()->unique()->regexify('[0-9]{14}'),
            'subdomain'             => fake()->unique()->domainWord . fake()->numberBetween(1, 9999),
            'logo'                  => fake()->imageUrl(200, 200, 'business', true, 'logo'),
            'zipcode'               => fake()->postcode,
            'street'                => fake()->address,
            'neighborhood'          => fake()->streetName,
            'city'                  => fake()->city,
            'state'                 => fake()->stateAbbr, // @phpstan-ignore-line
            'country'               => fake()->country,
            'number'                => fake()->randomElement([fake()->buildingNumber, null]),
            'complement'            => fake()->randomElement([fake()->sentence(3), null]),
            'cellphone'             => fake()->phoneNumber,
            'email'                 => fake()->unique()->safeEmail,
            'status'                => Status::random(),
        ];
    }
}
