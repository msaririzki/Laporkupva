<?php

namespace Database\Seeders;

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

            $kupva = Kupva::query()->firstOrCreate(
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
        $locations = $this->locations();
        $incidentTypes = $this->incidentTypes();

        for ($number = 1; $number <= 28; $number++) {
            $publicCode = sprintf('LKP-DEMO-%04d', $number);

            if (Report::query()->where('public_code', $publicCode)->exists()) {
                continue;
            }

            $statusPosition = ($number - 1) % count($statuses);
            $status = $statuses[$statusPosition];
            $location = $locations[($number - 1) % count($locations)];
            $createdAt = now()->subDays($number * 3)->startOfDay()->addHours(9);
            $statusTimestamps = $this->statusTimestamps($statusPosition, $createdAt);

            $report = Report::query()->create([
                'public_code' => $publicCode,
                'tracking_pin_hash' => $trackingPinHash,
                'status' => $status,
                'incident_type' => $incidentTypes[($number - 1) % count($incidentTypes)],
                'business_name' => sprintf('Demo Money Changer %02d', $number),
                'incident_date' => $createdAt->toDateString(),
                'incident_time' => sprintf('%02d:%02d:00', 8 + ($number % 10), ($number * 7) % 60),
                'description' => 'Data simulasi laporan masyarakat untuk demonstrasi alur penanganan TAMBORA.',
                'is_ongoing' => $number % 3 !== 0,
                'province' => 'Nusa Tenggara Barat',
                'regency' => $location['regency'],
                'district' => $location['district'],
                'village' => $location['village'],
                'address' => $location['address'],
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
                'location_accuracy' => 15 + $number,
                'public_update' => $status->description(),
                'internal_notes' => 'DATA DEMO — bukan laporan masyarakat yang sebenarnya.',
                ...$statusTimestamps,
            ]);

            $report->forceFill([
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays($statusPosition),
            ])->saveQuietly();

            $this->seedStatusHistories($report, $superAdmin, $statuses, $statusPosition, $createdAt);
            $createdCount++;
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
            'KUPVA tanpa izin',
            'Dugaan pelanggaran kurs',
            'Tidak menampilkan papan izin',
            'Transaksi mencurigakan',
            'Praktik penukaran valuta asing ilegal',
        ];
    }

    /**
     * @return list<array{
     *     regency: string,
     *     district: string,
     *     village: string,
     *     address: string,
     *     latitude: float,
     *     longitude: float
     * }>
     */
    private function locations(): array
    {
        return [
            ['regency' => 'Kota Mataram', 'district' => 'Selaparang', 'village' => 'Rembiga', 'address' => 'Area demo Jalan Adi Sucipto, Mataram', 'latitude' => -8.5705, 'longitude' => 116.1068],
            ['regency' => 'Kota Mataram', 'district' => 'Cakranegara', 'village' => 'Cilinaya', 'address' => 'Area demo pusat perdagangan Cakranegara', 'latitude' => -8.5901, 'longitude' => 116.1322],
            ['regency' => 'Lombok Barat', 'district' => 'Batu Layar', 'village' => 'Senggigi', 'address' => 'Area demo kawasan wisata Senggigi', 'latitude' => -8.4948, 'longitude' => 116.0475],
            ['regency' => 'Lombok Tengah', 'district' => 'Praya', 'village' => 'Praya', 'address' => 'Area demo pusat Kota Praya', 'latitude' => -8.7053, 'longitude' => 116.2702],
            ['regency' => 'Lombok Timur', 'district' => 'Selong', 'village' => 'Selong', 'address' => 'Area demo pusat Kota Selong', 'latitude' => -8.6507, 'longitude' => 116.5319],
            ['regency' => 'Lombok Utara', 'district' => 'Tanjung', 'village' => 'Tanjung', 'address' => 'Area demo pusat Kecamatan Tanjung', 'latitude' => -8.3562, 'longitude' => 116.1564],
            ['regency' => 'Sumbawa', 'district' => 'Sumbawa', 'village' => 'Seketeng', 'address' => 'Area demo pusat Sumbawa Besar', 'latitude' => -8.4931, 'longitude' => 117.4202],
            ['regency' => 'Sumbawa Barat', 'district' => 'Taliwang', 'village' => 'Kuang', 'address' => 'Area demo pusat Kota Taliwang', 'latitude' => -8.7449, 'longitude' => 116.8532],
            ['regency' => 'Dompu', 'district' => 'Dompu', 'village' => 'Bada', 'address' => 'Area demo pusat Kabupaten Dompu', 'latitude' => -8.5364, 'longitude' => 118.4634],
            ['regency' => 'Kota Bima', 'district' => 'Rasanae Barat', 'village' => 'Paruga', 'address' => 'Area demo pusat Kota Bima', 'latitude' => -8.4606, 'longitude' => 118.7267],
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
        $locations = $this->locations();
        $records = [];

        for ($number = 1; $number <= 12; $number++) {
            $location = $locations[($number - 1) % count($locations)];
            $licenseStatus = match ($number) {
                10 => 'expired',
                11 => 'suspended',
                default => 'active',
            };

            $records[] = [
                'name' => sprintf('Demo KUPVA Berizin %02d', $number),
                'license_number' => sprintf('DEMO-NTB-%04d', $number),
                'license_status' => $licenseStatus,
                'address' => $location['address'],
                'regency' => $location['regency'],
                'district' => $location['district'],
                'village' => $location['village'],
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
                'license_expires_at' => now()->addMonths(24 - $number)->toDateString(),
                'is_active' => $licenseStatus === 'active',
            ];
        }

        return $records;
    }
}
