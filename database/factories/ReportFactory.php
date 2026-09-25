<?php

namespace Database\Factories;

use App\Enums\NtbDemoLocation;
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
        $location = fake()->randomElement(NtbDemoLocation::cases());

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
            'business_name' => fake()->randomElement([
                'Nusa Valas',
                'Rinjani Exchange',
                'Samawa Valuta',
                'Tambora Money Exchange',
                'Mandalika Valas',
            ]).' (Data Demo)',
            'incident_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'incident_time' => fake()->time('H:i'),
            'description' => fake()->randomElement([
                'Terlihat aktivitas penukaran valuta asing pada tempat usaha yang tidak menampilkan papan izin secara jelas.',
                'Pelapor menemukan layanan penukaran uang dengan informasi kurs yang tidak ditampilkan secara transparan.',
                'Tempat usaha diduga melayani transaksi valuta asing secara rutin tanpa identitas KUPVA yang mudah dilihat.',
                'Terdapat penawaran penukaran valuta asing kepada wisatawan tanpa keterangan izin resmi di lokasi usaha.',
                'Petugas usaha tidak memberikan bukti transaksi maupun informasi nilai tukar secara terbuka kepada pelanggan.',
            ]),
            'is_ongoing' => fake()->boolean(),
            'province' => 'Nusa Tenggara Barat',
            ...$location->attributes(),
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
