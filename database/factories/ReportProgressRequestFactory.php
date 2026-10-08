<?php

namespace Database\Factories;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\ReportProgressRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ReportProgressRequest> */
class ReportProgressRequestFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'report_id' => Report::factory()->received(),
            'requested_by' => User::factory(),
            'from_status' => ReportStatus::Received,
            'to_status' => ReportStatus::Coordination,
            'status' => 'pending',
            'public_note' => 'Laporan sedang dikoordinasikan dengan aparat penegak hukum.',
            'activity_photos' => [],
            'activity_photo_names' => [],
        ];
    }
}
