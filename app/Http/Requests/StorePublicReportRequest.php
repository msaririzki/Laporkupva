<?php

namespace App\Http\Requests;

use App\Enums\NtbRegency;
use App\Rules\NoHtml;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePublicReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'incident_type' => ['required', Rule::in([
                'kupva_tanpa_izin',
                'transaksi_mencurigakan',
                'pelanggaran_kurs',
                'penolakan_rupiah',
                'lainnya',
            ])],
            'business_name' => ['nullable', 'string', 'max:255', new NoHtml],
            'reporter_phone' => ['nullable', 'string', 'max:20', 'regex:/^\+628[1-9][0-9]{6,11}$/'],
            'incident_date' => ['required', 'date', 'before_or_equal:today'],
            'incident_time' => ['nullable', 'date_format:H:i'],
            'description' => ['required', 'string', 'min:20', 'max:5000', new NoHtml],
            'is_ongoing' => ['sometimes', 'boolean'],
            'regency' => ['required', Rule::enum(NtbRegency::class)],
            'district' => ['nullable', 'string', 'max:120', new NoHtml],
            'village' => ['nullable', 'string', 'max:120', new NoHtml],
            'address' => ['nullable', 'string', 'max:1000', new NoHtml],
            'latitude' => ['required', 'numeric', 'between:-11,-8'],
            'longitude' => ['required', 'numeric', 'between:115,120'],
            'location_accuracy' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'evidence' => ['required', 'array', 'min:1', 'max:5'],
            'evidence.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'extensions:jpg,jpeg,png,webp,pdf', 'max:10240'],
            'good_faith' => ['accepted'],
            'website' => ['prohibited'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'incident_type' => 'jenis laporan',
            'business_name' => 'nama tempat/usaha',
            'reporter_phone' => 'nomor HP',
            'incident_date' => 'tanggal kejadian',
            'incident_time' => 'waktu kejadian',
            'description' => 'kronologi',
            'regency' => 'kabupaten/kota',
            'district' => 'kecamatan',
            'village' => 'desa/kelurahan',
            'address' => 'alamat',
            'latitude' => 'titik lokasi',
            'longitude' => 'titik lokasi',
            'evidence' => 'bukti pendukung',
            'evidence.*' => 'berkas bukti',
            'good_faith' => 'pernyataan itikad baik',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'reporter_phone.regex' => 'Masukkan nomor HP Indonesia yang valid, misalnya 0812 3456 7890.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_ongoing' => $this->boolean('is_ongoing'),
            'business_name' => $this->filled('business_name') ? trim((string) $this->input('business_name')) : null,
            'reporter_phone' => $this->normalizePhoneNumber(),
            'district' => $this->filled('district') ? trim((string) $this->input('district')) : null,
            'village' => $this->filled('village') ? trim((string) $this->input('village')) : null,
            'address' => $this->filled('address') ? trim((string) $this->input('address')) : null,
            'description' => trim((string) $this->input('description')),
        ]);
    }

    private function normalizePhoneNumber(): ?string
    {
        if (! $this->filled('reporter_phone')) {
            return null;
        }

        $phoneNumber = preg_replace('/[\s().-]+/', '', trim((string) $this->input('reporter_phone'))) ?? '';

        if (str_starts_with($phoneNumber, '08')) {
            return '+62'.substr($phoneNumber, 1);
        }

        if (str_starts_with($phoneNumber, '8')) {
            return '+62'.$phoneNumber;
        }

        if (str_starts_with($phoneNumber, '628')) {
            return '+'.$phoneNumber;
        }

        return $phoneNumber;
    }
}
