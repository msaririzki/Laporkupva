<?php

namespace Database\Seeders;

use App\Enums\ReportStatus;
use App\Enums\UserRole;
use App\Models\Kupva;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $email = config('tambora.seed_super_admin_email');
        $password = config('tambora.seed_super_admin_password');

        if (! is_string($email) || ! is_string($password) || $email === '' || $password === '') {
            $this->command?->warn('Akun Super Admin tidak dibuat. Isi SEED_SUPER_ADMIN_EMAIL dan SEED_SUPER_ADMIN_PASSWORD terlebih dahulu.');

            return;
        }

        $superAdmin = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin TAMBORA',
                'password' => $password,
                'role' => UserRole::SuperAdmin,
                'email_verified_at' => now(),
            ],
        );

        if (! app()->isLocal() || Report::query()->exists()) {
            return;
        }

        Kupva::factory()->count(12)->create();

        $statuses = ReportStatus::cases();

        foreach (range(1, 28) as $index) {
            $status = $statuses[$index % count($statuses)];
            $createdAt = now()->subDays(random_int(0, 24))->subHours(random_int(0, 18));
            $timestamps = $this->statusTimestamps($status, $createdAt);

            $report = Report::factory()->create([
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $timestamps['completed_at'] ?? $timestamps['result_reported_at'] ?? $timestamps['field_action_at'] ?? $timestamps['coordinated_at'] ?? $timestamps['received_at'] ?? $createdAt,
                ...$timestamps,
            ]);

            foreach ($statuses as $position => $historyStatus) {
                if ($position > array_search($status, $statuses, true)) {
                    break;
                }

                $report->statusHistories()->create([
                    'user_id' => $position === 0 ? null : $superAdmin->getKey(),
                    'from_status' => $position === 0 ? null : $statuses[$position - 1],
                    'to_status' => $historyStatus,
                    'public_note' => $historyStatus->description(),
                    'created_at' => $createdAt->copy()->addHours($position * 10),
                    'updated_at' => $createdAt->copy()->addHours($position * 10),
                ]);
            }
        }
    }

    /** @return array<string, mixed> */
    private function statusTimestamps(ReportStatus $status, mixed $createdAt): array
    {
        $statuses = ReportStatus::cases();
        $currentPosition = array_search($status, $statuses, true);
        $columns = [
            ReportStatus::Received->value => 'received_at',
            ReportStatus::Coordination->value => 'coordinated_at',
            ReportStatus::FieldAction->value => 'field_action_at',
            ReportStatus::ResultReport->value => 'result_reported_at',
            ReportStatus::Completed->value => 'completed_at',
        ];
        $timestamps = [];

        foreach ($statuses as $position => $candidate) {
            if ($position === 0 || $position > $currentPosition) {
                continue;
            }

            $timestamps[$columns[$candidate->value]] = $createdAt->copy()->addHours($position * 10);
        }

        return $timestamps;
    }
}
