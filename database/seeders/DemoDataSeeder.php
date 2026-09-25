<?php

namespace Database\Seeders;

use App\Enums\NtbDemoLocation;
use App\Enums\ReportStatus;
use App\Enums\UserRole;
use App\Models\Kupva;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public const TRACKING_PIN = '123456';

    public function run(): void
    {
        $superAdmin = User::query()
            ->where('role', UserRole::SuperAdmin)
            ->oldest('id')
            ->first();

        $trackingPinHash = Hash::make(self::TRACKING_PIN);

        DB::transaction(function () use ($superAdmin, $trackingPinHash): void {
            $createdKupvas = $this->seedKupvas();
            $createdReports = $this->seedReports($superAdmin, $trackingPinHash);

            $this->command?->info(
                "Data demo siap: {$createdReports} laporan baru dan {$createdKupvas} KUPVA baru."
            );
        });
    }

    private function seedKupvas(): int
    {
        $createdCount = 0;

        foreach ($this->kupvaRecords() as $record) {
            $licenseNumber = $record['license_number'];
            unset($record['license_number']);

            $kupva = Kupva::query()->updateOrCreate(
                ['license_number' => $licenseNumber],
                $record,
            );

            if ($kupva->wasRecentlyCreated) {
                $createdCount++;
            }
        }

        return $createdCount;
    }

    private function seedReports(?User $superAdmin, string $trackingPinHash): int
    {
        $createdCount = 0;
        $statuses = ReportStatus::cases();
        $locations = NtbDemoLocation::cases();
        $incidentTypes = $this->incidentTypes();
        $descriptions = $this->descriptions();

        for ($number = 1; $number <= 28; $number++) {
            $publicCode = sprintf('LKP-DEMO-%04d', $number);

            $statusPosition = ($number - 1) % count($statuses);
            $status = $statuses[$statusPosition];
            $location = $locations[($number - 1) % count($locations)];
            $createdAt = now()->subDays($number * 3)->startOfDay()->addHours(9);
            $statusTimestamps = $this->statusTimestamps($statusPosition, $createdAt);

            $report = Report::query()->updateOrCreate([
                'public_code' => $publicCode,
            ], [
                'tracking_pin_hash' => $trackingPinHash,
                'status' => $status,
                'incident_type' => $incidentTypes[($number - 1) % count($incidentTypes)],
                'business_name' => sprintf('Demo Valas %s %02d', $location->areaName(), $number),
                'incident_date' => $createdAt->toDateString(),
                'incident_time' => sprintf('%02d:%02d:00', 8 + ($number % 10), ($number * 7) % 60),
                'description' => $descriptions[($number - 1) % count($descriptions)],
                'is_ongoing' => $number % 3 !== 0,
                'province' => 'Nusa Tenggara Barat',
                ...$location->attributes(),
                'location_accuracy' => 15 + $number,
                'public_update' => $status->description(),
                'internal_notes' => 'DATA DEMO — bukan laporan masyarakat yang sebenarnya.',
                ...$statusTimestamps,
            ]);

            $report->forceFill([
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays($statusPosition),
            ])->saveQuietly();

            if ($report->wasRecentlyCreated) {
                $this->seedStatusHistories($report, $superAdmin, $statuses, $statusPosition, $createdAt);
                $createdCount++;
            }
        }

        return $createdCount;
    }

    /**
     * @param  list<ReportStatus>  $statuses
     */
    private function seedStatusHistories(
        Report $report,
        ?User $superAdmin,
        array $statuses,
        int $statusPosition,
        Carbon $createdAt,
    ): void {
        foreach (array_slice($statuses, 0, $statusPosition + 1) as $position => $status) {
            $history = $report->statusHistories()->create([
                'user_id' => $position === 0 ? null : $superAdmin?->getKey(),
                'from_status' => $position === 0 ? null : $statuses[$position - 1],
                'to_status' => $status,
                'public_note' => $status->description(),
                'internal_note' => 'Riwayat simulasi untuk data demo.',
            ]);

            $historyTimestamp = $createdAt->copy()->addDays($position);
            $history->forceFill([
                'created_at' => $historyTimestamp,
                'updated_at' => $historyTimestamp,
            ])->saveQuietly();
        }
    }

    /** @return array<string, Carbon|null> */
    private function statusTimestamps(int $statusPosition, Carbon $createdAt): array
    {
        $timestampColumns = [
            'received_at',
            'coordinated_at',
            'field_action_at',
            'result_reported_at',
            'completed_at',
        ];
        $timestamps = [];

        foreach ($timestampColumns as $position => $column) {
            $timestamps[$column] = $statusPosition > $position
                ? $createdAt->copy()->addDays($position + 1)
                : null;
        }

        return $timestamps;
    }

    /** @return list<string> */
    private function incidentTypes(): array
    {
        return [
            'kupva_tanpa_izin',
            'transaksi_mencurigakan',
            'pelanggaran_kurs',
            'penolakan_rupiah',
            'lainnya',
        ];
    }

    /** @return list<string> */
    private function descriptions(): array
    {
        return [
            'Terlihat aktivitas penukaran valuta asing pada tempat usaha yang tidak menampilkan papan izin secara jelas.',
            'Pelapor menemukan layanan penukaran uang dengan informasi kurs yang tidak ditampilkan secara transparan.',
            'Tempat usaha diduga melayani transaksi valuta asing secara rutin tanpa identitas KUPVA yang mudah dilihat.',
            'Terdapat penawaran penukaran valuta asing kepada wisatawan tanpa keterangan izin resmi di lokasi usaha.',
            'Petugas usaha tidak memberikan bukti transaksi maupun informasi nilai tukar secara terbuka kepada pelanggan.',
        ];
    }

    /**
     * @return list<array{
     *     name: string,
     *     license_number: string,
     *     license_status: string,
     *     address: string,
     *     regency: string,
     *     district: string,
     *     village: string,
     *     latitude: float,
     *     longitude: float,
     *     license_expires_at: string,
     *     is_active: bool
     * }>
     */
    private function kupvaRecords(): array
    {
        $locations = NtbDemoLocation::cases();
        $records = [];

        for ($number = 1; $number <= 12; $number++) {
            $location = $locations[($number - 1) % count($locations)];
            $licenseStatus = match ($number) {
                10 => 'expired',
                11 => 'suspended',
                default => 'active',
            };

            $records[] = [
                'name' => sprintf('KUPVA Demo %s %02d', $location->areaName(), $number),
                'license_number' => sprintf('DEMO-NTB-%04d', $number),
                'license_status' => $licenseStatus,
                ...$location->attributes(),
                'license_expires_at' => now()->addMonths(24 - $number)->toDateString(),
                'is_active' => $licenseStatus === 'active',
            ];
        }

        return $records;
    }
}
