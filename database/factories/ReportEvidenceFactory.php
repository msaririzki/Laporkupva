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
            'report_status_history_id' => null,
            'uploaded_by_user_id' => null,
            'source' => 'reporter_submission',
            'path' => 'report-evidence/'.fake()->uuid().'.jpg',
            'original_name' => 'bukti.jpg',
            'mime_type' => 'image/jpeg',
            'size' => fake()->numberBetween(50_000, 2_000_000),
            'caption' => null,
        ];
    }
}
