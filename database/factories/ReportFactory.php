<?php

namespace Database\Factories;

use App\Enums\ReportStatus;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_code' => 'LKP-'.Str::upper(Str::random(4)).'-'.Str::upper(Str::random(4)),
            'tracking_pin_hash' => Hash::make('123456'),
            'status' => ReportStatus::Submitted,
            'incident_type' => fake()->randomElement([
                'kupva_tanpa_izin',
                'transaksi_mencurigakan',
                'pelanggaran_kurs',
                'penolakan_rupiah',
                'lainnya',
            ]),
            'business_name' => fake()->company().' Money Changer',
            'incident_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'incident_time' => fake()->time('H:i'),
            'description' => fake()->paragraph(),
            'is_ongoing' => fake()->boolean(),
            'province' => 'Nusa Tenggara Barat',
            'regency' => fake()->randomElement(['Kota Mataram', 'Kabupaten Lombok Barat', 'Kabupaten Lombok Tengah', 'Kota Bima']),
            'district' => fake()->citySuffix(),
            'village' => fake()->streetName(),
            'address' => fake()->streetAddress(),
            'latitude' => fake()->latitude(-9.0, -8.0),
            'longitude' => fake()->longitude(115.8, 119.3),
            'location_accuracy' => fake()->randomFloat(2, 5, 80),
        ];
    }

    public function received(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReportStatus::Received,
            'received_at' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReportStatus::Completed,
            'received_at' => now()->subDays(5),
            'coordinated_at' => now()->subDays(4),
            'field_action_at' => now()->subDays(3),
            'result_reported_at' => now()->subDays(2),
            'completed_at' => now(),
        ]);
    }
}
