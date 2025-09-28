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
            'social_reason'         => $this->faker->company,
            'identification_number' => $this->faker->unique()->regexify('[0-9]{14}'),
            'subdomain'             => $this->faker->unique()->domainWord,
            'logo'                  => $this->faker->imageUrl(200, 200, 'business', true, 'logo'),
            'zipcode'               => $this->faker->postcode,
            'street'                => $this->faker->address,
            'neighborhood'          => $this->faker->streetName,
            'city'                  => $this->faker->city,
            'state'                 => $this->faker->stateAbbr, // @phpstan-ignore-line
            'country'               => $this->faker->country,
            'number'                => fake()->randomElement([$this->faker->buildingNumber, null]),
            'complement'            => fake()->randomElement([$this->faker->sentence(3), null]),
            'cellphone'             => $this->faker->phoneNumber,
            'email'                 => $this->faker->unique()->safeEmail,
            'status'                => Status::random(),
        ];
    }
}
