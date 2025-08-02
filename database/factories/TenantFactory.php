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
            'fantasy_name'          => $this->faker->companySuffix,
            'identification_number' => $this->faker->unique()->regexify('[0-9]{14}'),
            'subdomain'             => $this->faker->unique()->domainWord,
            'logo'                  => $this->faker->imageUrl(200, 200, 'business', true, 'logo'),
            'zipcode'               => $this->faker->postcode,
            'street'                => $this->faker->streetName,
            'city'                  => $this->faker->city,
            'state'                 => $this->faker->stateAbbr,
            'number'                => $this->faker->buildingNumber,
            'complement'            => $this->faker->secondaryAddress,
            'cellphone'             => $this->faker->phoneNumber,
            'email'                 => $this->faker->unique()->safeEmail,
            'status'             => Status::random()
        ];
    }
}
