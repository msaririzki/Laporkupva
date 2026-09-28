<?php

namespace App\Filament\Resources\Kupvas;

use App\Enums\NtbRegency;
use App\Models\Kupva;
use App\Rules\NoHtml;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use League\Csv\Reader;

final class KupvaCsvImporter
{
    /** @var list<string> */
    private const REQUIRED_HEADERS = [
        'id',
        'nama_usaha',
        'nomor_izin',
        'status_izin',
        'kabupaten_kota',
        'kecamatan',
        'desa_kelurahan',
        'alamat',
        'latitude',
        'longitude',
        'berlaku_sampai',
        'beroperasi',
    ];

    /**
     * @return array{created: int, updated: int}
     *
     * @throws ValidationException
     */
    public function import(UploadedFile $file): array
    {
        $reader = Reader::from($file->getRealPath(), 'r');
        $reader->setDelimiter($this->detectDelimiter($file->getRealPath()));
        $reader->setHeaderOffset(0);

        $headerMap = collect($reader->getHeader())
            ->mapWithKeys(fn (string $header): array => [$this->normalizeHeader($header) => $header])
            ->all();

        $missingHeaders = array_values(array_diff(self::REQUIRED_HEADERS, array_keys($headerMap)));

        if ($missingHeaders !== []) {
            throw ValidationException::withMessages([
                'file' => 'Kolom template tidak lengkap: '.implode(', ', $missingHeaders).'. Unduh dan gunakan template CSV terbaru.',
            ]);
        }

        $rows = [];
        $validationMessages = [];
        $licenseNumbers = [];

        $rowNumber = 1;

        foreach ($reader->getRecords() as $record) {
            $rowNumber++;
            $row = $this->normalizeRow($record, $headerMap);

            if ($this->isEmptyRow($row)) {
                continue;
            }

            if (count($rows) >= 1000) {
                throw ValidationException::withMessages([
                    'file' => 'Maksimal 1.000 data dalam satu kali impor.',
                ]);
            }

            $validator = Validator::make($row, [
                'id' => ['nullable', 'integer', 'exists:kupvas,id'],
                'name' => ['required', 'string', 'max:255', new NoHtml],
                'license_number' => ['nullable', 'required_without:id', 'string', 'max:255', new NoHtml],
                'license_status' => ['required', Rule::in(['active', 'expired', 'suspended'])],
                'regency' => ['required', Rule::enum(NtbRegency::class)],
                'district' => ['nullable', 'string', 'max:120', new NoHtml],
                'village' => ['nullable', 'string', 'max:120', new NoHtml],
                'address' => ['nullable', 'string', 'max:2000', new NoHtml],
                'latitude' => ['nullable', 'numeric', 'between:-11,-8'],
                'longitude' => ['nullable', 'numeric', 'between:115,120'],
                'license_expires_at' => ['nullable', 'date_format:Y-m-d'],
                'is_active' => ['required', 'boolean'],
            ], [
                'id.exists' => 'ID KUPVA tidak ditemukan',
                'license_number.required_without' => 'nomor izin wajib diisi untuk data baru',
                'license_status.in' => 'status izin harus Aktif, Kedaluwarsa, atau Dibekukan',
                'regency.enum' => 'kabupaten/kota tidak termasuk wilayah NTB yang didukung',
                'license_expires_at.date_format' => 'tanggal berlaku harus memakai format YYYY-MM-DD',
                'is_active.boolean' => 'status beroperasi harus Ya atau Tidak',
            ], [
                'id' => 'ID',
                'name' => 'nama usaha',
                'license_number' => 'nomor izin',
                'license_status' => 'status izin',
                'regency' => 'kabupaten/kota',
                'district' => 'kecamatan',
                'village' => 'desa/kelurahan',
                'address' => 'alamat',
                'license_expires_at' => 'berlaku sampai',
                'is_active' => 'beroperasi',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $message) {
                    $validationMessages[] = "Baris {$rowNumber}: {$message}";
                }

                continue;
            }

            $rowKey = filled($row['id'])
                ? 'id:'.$row['id']
                : 'license:'.Str::lower($row['license_number']);

            if (isset($licenseNumbers[$rowKey])) {
                $validationMessages[] = "Baris {$rowNumber}: data sama dengan baris {$licenseNumbers[$rowKey]}.";

                continue;
            }

            $licenseNumbers[$rowKey] = $rowNumber;
            $rows[] = $validator->validated();
        }

        if ($rows === [] && $validationMessages === []) {
            throw ValidationException::withMessages([
                'file' => 'Berkas CSV belum berisi data KUPVA.',
            ]);
        }

        if ($validationMessages !== []) {
            throw ValidationException::withMessages([
                'file' => array_slice($validationMessages, 0, 8),
            ]);
        }

        return DB::transaction(function () use ($rows): array {
            $created = 0;
            $updated = 0;

            foreach ($rows as $row) {
                $kupva = filled($row['id'])
                    ? Kupva::query()->findOrFail($row['id'])
                    : Kupva::query()->firstOrNew(['license_number' => $row['license_number']]);

                unset($row['id']);

                $kupva->fill($row);
                $kupva->save();

                $kupva->wasRecentlyCreated ? $created++ : $updated++;
            }

            return compact('created', 'updated');
        });
    }

    private function detectDelimiter(string $path): string
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            return ',';
        }

        $firstLine = fgets($handle) ?: '';
        fclose($handle);

        return substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';
    }

    private function normalizeHeader(string $header): string
    {
        return Str::of($header)
            ->replace("\xEF\xBB\xBF", '')
            ->lower()
            ->ascii()
            ->replaceMatches('/[^a-z0-9]+/', '_')
            ->trim('_')
            ->toString();
    }

    /**
     * @param  array<string, string|null>  $record
     * @param  array<string, string>  $headerMap
     * @return array<string, mixed>
     */
    private function normalizeRow(array $record, array $headerMap): array
    {
        $value = fn (string $header): string => trim((string) ($record[$headerMap[$header]] ?? ''));

        return [
            'id' => $value('id') ?: null,
            'name' => $value('nama_usaha'),
            'license_number' => $value('nomor_izin'),
            'license_status' => $this->normalizeLicenseStatus($value('status_izin')),
            'regency' => $value('kabupaten_kota'),
            'district' => $value('kecamatan') ?: null,
            'village' => $value('desa_kelurahan') ?: null,
            'address' => $value('alamat') ?: null,
            'latitude' => $value('latitude') ?: null,
            'longitude' => $value('longitude') ?: null,
            'license_expires_at' => $value('berlaku_sampai') ?: null,
            'is_active' => $this->normalizeOperationalStatus($value('beroperasi')),
        ];
    }

    private function normalizeLicenseStatus(string $status): string
    {
        return match (Str::lower($status)) {
            'aktif', 'active' => 'active',
            'kedaluwarsa', 'kadaluarsa', 'expired' => 'expired',
            'dibekukan', 'suspended' => 'suspended',
            default => Str::lower($status),
        };
    }

    private function normalizeOperationalStatus(string $status): bool|string
    {
        return match (Str::lower($status)) {
            'ya', 'yes', '1', 'aktif', 'beroperasi' => true,
            'tidak', 'no', '0', 'nonaktif', 'tidak beroperasi' => false,
            default => $status,
        };
    }

    /** @param array<string, mixed> $row */
    private function isEmptyRow(array $row): bool
    {
        return collect($row)
            ->except(['license_status', 'is_active'])
            ->filter(fn (mixed $value): bool => filled($value))
            ->isEmpty();
    }
}
