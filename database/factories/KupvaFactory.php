<?php

namespace Database\Factories;

use App\Enums\NtbDemoLocation;
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
        $location = fake()->randomElement(NtbDemoLocation::cases());

        return [
            'name' => fake()->randomElement([
                'KUPVA Nusa Valas',
                'KUPVA Rinjani Exchange',
                'KUPVA Samawa Valuta',
                'KUPVA Tambora Exchange',
                'KUPVA Mandalika Valas',
            ]).' (Data Demo)',
            'license_number' => fake()->unique()->bothify('KUPVA-NTB-####'),
            'license_status' => 'active',
            ...$location->attributes(),
            'license_expires_at' => fake()->dateTimeBetween('now', '+2 years'),
            'is_active' => true,
        ];
    }
}
