<?php

namespace App\Http\Requests;

use App\Enums\NtbRegency;
use App\Rules\NoHtml;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePublicReportRequest extends PublicReporterRequest
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
        $rules = [
            ...parent::rules(),
            'verification_id' => ['required', 'uuid'],
            'incident_type' => ['required', Rule::in([
                'kupva_tanpa_izin',
                'transaksi_mencurigakan',
                'pelanggaran_kurs',
                'penolakan_rupiah',
                'lainnya',
            ])],
            'business_name' => ['required', 'string', 'max:255', new NoHtml],
            'incident_date' => ['required', 'date', 'before_or_equal:today'],
            'incident_time' => ['required', 'date_format:H:i'],
            'description' => ['required', 'string', 'min:20', 'max:5000', new NoHtml],
            'is_ongoing' => ['exclude'],
            'regency' => ['required', Rule::enum(NtbRegency::class)],
            'district' => ['nullable', 'string', 'max:120', new NoHtml],
            'village' => ['nullable', 'string', 'max:120', new NoHtml],
            'address' => ['nullable', 'string', 'max:1000', new NoHtml],
            'latitude' => ['required', 'numeric', 'between:-11,-8'],
            'longitude' => ['required', 'numeric', 'between:115,120'],
            'location_accuracy' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'location_confirmed' => ['accepted'],
            'evidence' => ['required', 'array', 'min:1', 'max:5'],
            'evidence.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'extensions:jpg,jpeg,png,webp,pdf', 'max:10240'],
            'good_faith' => ['accepted'],
            'website' => ['prohibited'],
        ];

        return $rules;
    }

    /** @return array<callable> */
    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->hasAny(['verification_id', 'reporter_name', 'reporter_email', 'reporter_phone'])) {
                return;
            }
            $verification = $this->session()->get('report_verifications.'.$this->input('verification_id'));

            if (! is_array($verification)
                || ($verification['expires_at'] ?? 0) <= now()->getTimestamp()
                || ! hash_equals((string) ($verification['identity'] ?? ''), $this->identityFingerprint())) {
                $validator->errors()->add('verification_id', 'Silakan selesaikan verifikasi di tahap Data pelapor terlebih dahulu.');
            }
        }];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            ...parent::attributes(),
            'verification_id' => 'verifikasi data pelapor',
            'incident_type' => 'jenis laporan',
            'business_name' => 'nama tempat/usaha',
            'reporter_phone' => 'nomor HP pelapor',
            'incident_date' => 'tanggal kejadian',
            'incident_time' => 'waktu kejadian',
            'description' => 'kronologi',
            'regency' => 'kabupaten/kota',
            'district' => 'kecamatan',
            'village' => 'desa/kelurahan',
            'address' => 'alamat',
            'latitude' => 'titik lokasi',
            'longitude' => 'titik lokasi',
            'location_confirmed' => 'konfirmasi lokasi',
            'evidence' => 'bukti pendukung',
            'evidence.*' => 'berkas bukti',
            'good_faith' => 'pernyataan itikad baik',
            'cf-turnstile-response' => 'verifikasi keamanan',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            ...parent::messages(),
            'business_name.required' => 'Isi nama usaha atau ciri tempat kejadian.',
            'incident_time.required' => 'Isi perkiraan waktu kejadian.',
            'location_confirmed.accepted' => 'Pastikan penanda sesuai lokasi kejadian, lalu centang konfirmasi lokasi.',
        ];
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->merge([
            'business_name' => $this->filled('business_name') ? trim((string) $this->input('business_name')) : null,
            'district' => $this->filled('district') ? trim((string) $this->input('district')) : null,
            'village' => $this->filled('village') ? trim((string) $this->input('village')) : null,
            'address' => $this->filled('address') ? trim((string) $this->input('address')) : null,
            'description' => trim((string) $this->input('description')),
        ]);
    }
}
