<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\ReportEvidence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReportEvidence>
 */
class ReportEvidenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'report_id' => Report::factory(),
            'path' => 'report-evidence/'.fake()->uuid().'.jpg',
            'original_name' => 'bukti.jpg',
            'mime_type' => 'image/jpeg',
            'size' => fake()->numberBetween(50_000, 2_000_000),
        ];
    }
}
