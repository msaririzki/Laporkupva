<?php

namespace App\Filament\Resources\Kupvas;

use App\Enums\NtbRegency;
use App\Models\Kupva;
use App\Rules\NoHtml;
use DateTimeInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use OpenSpout\Reader\CSV\Options as CsvOptions;
use OpenSpout\Reader\CSV\Reader as CsvReader;
use OpenSpout\Reader\ReaderInterface;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use Throwable;

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

    /** @var array<string, string> */
    private const FIELD_LABELS = [
        'name' => 'Nama usaha',
        'license_number' => 'Nomor izin',
        'license_status' => 'Status izin',
        'regency' => 'Kabupaten/kota',
        'district' => 'Kecamatan',
        'village' => 'Desa/kelurahan',
        'address' => 'Alamat',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'license_expires_at' => 'Berlaku sampai',
        'is_active' => 'Beroperasi',
    ];

    /**
     * @return array{
     *     can_import: bool,
     *     summary: array{new: int, updated: int, unchanged: int, duplicates: int, invalid: int},
     *     items: list<array<string, mixed>>,
     *     errors: list<string>,
     *     import_rows: list<array{status: string, model_id: int|null, values: array<string, mixed>}>
     * }
     */
    public function analyze(UploadedFile $file): array
    {
        $analysis = $this->emptyAnalysis();

        try {
            $spreadsheet = $this->readSpreadsheet($file);
        } catch (Throwable) {
            $analysis['errors'][] = 'Berkas tidak dapat dibaca. Gunakan template Excel terbaru atau berkas CSV yang valid.';
            $analysis['summary']['invalid']++;

            return $analysis;
        }

        $headerMap = collect($spreadsheet['headers'])
            ->mapWithKeys(fn (mixed $header, int $index): array => [$this->normalizeHeader((string) $header) => $index])
            ->all();

        $missingHeaders = array_values(array_diff(self::REQUIRED_HEADERS, array_keys($headerMap)));

        if ($missingHeaders !== []) {
            $analysis['errors'][] = 'Kolom template tidak lengkap: '.implode(', ', $missingHeaders).'. Unduh dan gunakan template Excel terbaru.';
            $analysis['summary']['invalid']++;

            return $analysis;
        }

        /** @var array<string, array{row: int, values: array<string, mixed>}> $seenRows */
        $seenRows = [];

        foreach ($spreadsheet['rows'] as $spreadsheetRow) {
            $rowNumber = $spreadsheetRow['number'];
            $row = $this->normalizeRow($spreadsheetRow['values'], $headerMap);

            if ($this->isEmptyRow($row)) {
                continue;
            }

            if (count($analysis['items']) >= 1000) {
                $analysis['errors'][] = 'Maksimal 1.000 data dalam satu kali impor.';
                $analysis['summary']['invalid']++;

                break;
            }

            $validator = Validator::make($row, $this->rulesFor($row), [
                'id.exists' => 'ID KUPVA tidak ditemukan',
                'license_number.required_without' => 'nomor izin wajib diisi untuk data baru',
                'license_number.unique' => 'nomor izin telah digunakan oleh KUPVA lain',
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
                $messages = $validator->errors()->all();
                $analysis['summary']['invalid']++;
                $analysis['errors'][] = "Baris {$rowNumber}: ".implode('; ', $messages).'.';
                $analysis['items'][] = $this->previewItem($rowNumber, 'invalid', $row, [], implode('; ', $messages));

                continue;
            }

            $validated = $validator->validated();
            $existing = filled($validated['id'])
                ? Kupva::query()->find((int) $validated['id'])
                : Kupva::query()->where('license_number', $validated['license_number'])->first();
            $rowKey = $existing
                ? 'model:'.$existing->getKey()
                : 'license:'.Str::lower($validated['license_number']);

            if (isset($seenRows[$rowKey])) {
                $isIdentical = $this->rowsAreEqual($seenRows[$rowKey]['values'], $validated);
                $message = $isIdentical
                    ? "Sama dengan baris {$seenRows[$rowKey]['row']} dan akan dilewati."
                    : "Nomor izin atau ID juga digunakan pada baris {$seenRows[$rowKey]['row']} dengan isi berbeda.";

                $analysis['summary']['duplicates']++;
                $analysis['items'][] = $this->previewItem($rowNumber, 'duplicate', $validated, [], $message, ! $isIdentical);

                if (! $isIdentical) {
                    $analysis['errors'][] = "Baris {$rowNumber}: {$message}";
                }

                continue;
            }

            $seenRows[$rowKey] = ['row' => $rowNumber, 'values' => $validated];

            if (! $existing) {
                $analysis['summary']['new']++;
                $analysis['items'][] = $this->previewItem($rowNumber, 'new', $validated);
                $analysis['import_rows'][] = ['status' => 'new', 'model_id' => null, 'values' => $validated];

                continue;
            }

            $changes = $this->changesFor($existing, $validated);

            if ($changes === []) {
                $analysis['summary']['unchanged']++;
                $analysis['items'][] = $this->previewItem($rowNumber, 'unchanged', $validated);
                $analysis['import_rows'][] = ['status' => 'unchanged', 'model_id' => $existing->getKey(), 'values' => $validated];

                continue;
            }

            $analysis['summary']['updated']++;
            $analysis['items'][] = $this->previewItem($rowNumber, 'updated', $validated, $changes);
            $analysis['import_rows'][] = ['status' => 'updated', 'model_id' => $existing->getKey(), 'values' => $validated];
        }

        if ($analysis['items'] === [] && $analysis['errors'] === []) {
            $analysis['errors'][] = 'Berkas belum berisi data KUPVA.';
            $analysis['summary']['invalid']++;
        }

        $analysis['can_import'] = $analysis['errors'] === []
            && ($analysis['summary']['new'] + $analysis['summary']['updated'] + $analysis['summary']['unchanged']) > 0;

        return $analysis;
    }

    /**
     * @return array{created: int, updated: int, unchanged: int, duplicates: int}
     *
     * @throws ValidationException
     */
    public function import(UploadedFile $file): array
    {
        $analysis = $this->analyze($file);

        if (! $analysis['can_import']) {
            throw ValidationException::withMessages([
                'file' => array_slice($analysis['errors'], 0, 8),
            ]);
        }

        return DB::transaction(function () use ($analysis): array {
            $created = 0;
            $updated = 0;
            $unchanged = 0;

            foreach ($analysis['import_rows'] as $row) {
                if ($row['status'] === 'unchanged') {
                    $unchanged++;

                    continue;
                }

                $values = $row['values'];
                unset($values['id']);

                $kupva = $row['model_id']
                    ? Kupva::query()->findOrFail($row['model_id'])
                    : new Kupva;

                $kupva->fill($values);
                $kupva->save();

                $kupva->wasRecentlyCreated ? $created++ : $updated++;
            }

            return [
                'created' => $created,
                'updated' => $updated,
                'unchanged' => $unchanged,
                'duplicates' => $analysis['summary']['duplicates'],
            ];
        });
    }

    /**
     * @return array{
     *     can_import: bool,
     *     summary: array{new: int, updated: int, unchanged: int, duplicates: int, invalid: int},
     *     items: list<array<string, mixed>>,
     *     errors: list<string>,
     *     import_rows: list<array{status: string, model_id: int|null, values: array<string, mixed>}>
     * }
     */
    private function emptyAnalysis(): array
    {
        return [
            'can_import' => false,
            'summary' => [
                'new' => 0,
                'updated' => 0,
                'unchanged' => 0,
                'duplicates' => 0,
                'invalid' => 0,
            ],
            'items' => [],
            'errors' => [],
            'import_rows' => [],
        ];
    }

    /**
     * @return array{headers: list<mixed>, rows: list<array{number: int, values: list<mixed>}>}
     */
    private function readSpreadsheet(UploadedFile $file): array
    {
        $path = $file->getRealPath();
        $extension = Str::lower($file->getClientOriginalExtension());

        if ($extension === 'xlsx') {
            $reader = new XlsxReader;
        } else {
            $options = new CsvOptions;
            $options->FIELD_DELIMITER = $this->detectDelimiter($path);
            $reader = new CsvReader($options);
        }

        return $this->readFirstSheet($reader, $path);
    }

    /**
     * @return array{headers: list<mixed>, rows: list<array{number: int, values: list<mixed>}>}
     */
    private function readFirstSheet(ReaderInterface $reader, string $path): array
    {
        $reader->open($path);
        $headers = [];
        $rows = [];

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $index => $spreadsheetRow) {
                    $values = array_values($spreadsheetRow->toArray());

                    if ($index === 1) {
                        $headers = $values;

                        continue;
                    }

                    $rows[] = ['number' => $index, 'values' => $values];
                }

                break;
            }
        } finally {
            $reader->close();
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /** @param array<string, mixed> $row */
    private function rulesFor(array $row): array
    {
        $licenseRules = ['nullable', 'required_without:id', 'string', 'max:255', new NoHtml];

        if (filled($row['id'])) {
            $licenseRules[] = Rule::unique('kupvas', 'license_number')->ignore((int) $row['id']);
        }

        return [
            'id' => ['nullable', 'integer', 'exists:kupvas,id'],
            'name' => ['required', 'string', 'max:255', new NoHtml],
            'license_number' => $licenseRules,
            'license_status' => ['required', Rule::in(['active', 'expired', 'suspended'])],
            'regency' => ['required', Rule::enum(NtbRegency::class)],
            'district' => ['nullable', 'string', 'max:120', new NoHtml],
            'village' => ['nullable', 'string', 'max:120', new NoHtml],
            'address' => ['nullable', 'string', 'max:2000', new NoHtml],
            'latitude' => ['nullable', 'numeric', 'between:-11,-8'],
            'longitude' => ['nullable', 'numeric', 'between:115,120'],
            'license_expires_at' => ['nullable', 'date_format:Y-m-d'],
            'is_active' => ['required', 'boolean'],
        ];
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
     * @param  list<mixed>  $record
     * @param  array<string, int>  $headerMap
     * @return array<string, mixed>
     */
    private function normalizeRow(array $record, array $headerMap): array
    {
        $value = fn (string $header): string => $this->normalizeCellValue($record[$headerMap[$header]] ?? null);

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

    private function normalizeCellValue(mixed $value): string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_bool($value)) {
            return $value ? 'Ya' : 'Tidak';
        }

        return trim((string) ($value ?? ''));
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

    /**
     * @param  array<string, mixed>  $first
     * @param  array<string, mixed>  $second
     */
    private function rowsAreEqual(array $first, array $second): bool
    {
        foreach (array_keys(self::FIELD_LABELS) as $field) {
            if ($this->comparableValue($field, $first[$field] ?? null) !== $this->comparableValue($field, $second[$field] ?? null)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $values
     * @return list<array{field: string, label: string, before: string, after: string}>
     */
    private function changesFor(Kupva $kupva, array $values): array
    {
        $changes = [];

        foreach (self::FIELD_LABELS as $field => $label) {
            $before = $kupva->getAttribute($field);
            $after = $values[$field] ?? null;

            if ($this->comparableValue($field, $before) === $this->comparableValue($field, $after)) {
                continue;
            }

            $changes[] = [
                'field' => $field,
                'label' => $label,
                'before' => $this->displayValue($field, $before),
                'after' => $this->displayValue($field, $after),
            ];
        }

        return $changes;
    }

    private function comparableValue(string $field, mixed $value): string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if ($field === 'is_active') {
            return (bool) $value ? '1' : '0';
        }

        if (in_array($field, ['latitude', 'longitude'], true) && filled($value)) {
            return rtrim(rtrim(number_format((float) $value, 7, '.', ''), '0'), '.');
        }

        return trim((string) ($value ?? ''));
    }

    private function displayValue(string $field, mixed $value): string
    {
        $value = $this->comparableValue($field, $value);

        if ($value === '') {
            return 'Kosong';
        }

        return match ($field) {
            'license_status' => match ($value) {
                'active' => 'Aktif',
                'expired' => 'Kedaluwarsa',
                'suspended' => 'Dibekukan',
                default => $value,
            },
            'is_active' => $value === '1' ? 'Ya' : 'Tidak',
            default => $value,
        };
    }

    /**
     * @param  array<string, mixed>  $values
     * @param  list<array{field: string, label: string, before: string, after: string}>  $changes
     * @return array<string, mixed>
     */
    private function previewItem(
        int $rowNumber,
        string $status,
        array $values,
        array $changes = [],
        ?string $message = null,
        bool $isConflict = false,
    ): array {
        return [
            'row' => $rowNumber,
            'status' => $status,
            'name' => filled($values['name'] ?? null) ? (string) $values['name'] : 'Nama belum tersedia',
            'license_number' => filled($values['license_number'] ?? null) ? (string) $values['license_number'] : 'Nomor izin belum tersedia',
            'changes' => $changes,
            'message' => $message,
            'is_conflict' => $isConflict,
        ];
    }
}
