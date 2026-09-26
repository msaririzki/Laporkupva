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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_ongoing' => $this->boolean('is_ongoing'),
            'business_name' => $this->filled('business_name') ? trim((string) $this->input('business_name')) : null,
            'district' => $this->filled('district') ? trim((string) $this->input('district')) : null,
            'village' => $this->filled('village') ? trim((string) $this->input('village')) : null,
            'address' => $this->filled('address') ? trim((string) $this->input('address')) : null,
            'description' => trim((string) $this->input('description')),
        ]);
    }
}
