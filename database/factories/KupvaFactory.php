<?php

namespace Database\Factories;

use App\Models\Kupva;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kupva>
 */
class KupvaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' Money Changer',
            'license_number' => fake()->unique()->bothify('KUPVA-NTB-####'),
            'license_status' => 'active',
            'address' => fake()->streetAddress(),
            'regency' => fake()->randomElement(['Kota Mataram', 'Kabupaten Lombok Barat', 'Kabupaten Lombok Tengah', 'Kota Bima']),
            'district' => fake()->citySuffix(),
            'village' => fake()->streetName(),
            'latitude' => fake()->latitude(-9.0, -8.0),
            'longitude' => fake()->longitude(115.8, 119.3),
            'license_expires_at' => fake()->dateTimeBetween('now', '+2 years'),
            'is_active' => true,
        ];
    }
}
