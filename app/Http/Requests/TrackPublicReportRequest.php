<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use JsonException;

class TrackPublicReportRequest extends FormRequest
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
            'tracking_code' => ['required', 'string', 'max:20', 'regex:/^LKP-[A-Z0-9]{4}-[A-Z0-9]{4}$/'],
            'access_token' => ['nullable', 'string', 'max:4096'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'tracking_code' => 'nomor laporan',
        ];
    }

    protected function prepareForValidation(): void
    {
        $credentials = $this->credentialsFromAccessToken();

        $this->merge([
            'tracking_code' => strtoupper(trim((string) ($credentials['code'] ?? $this->input('tracking_code')))),
        ]);
    }

    /** @return array{code: string}|null */
    private function credentialsFromAccessToken(): ?array
    {
        $accessToken = trim((string) $this->input('access_token'));

        if ($accessToken === '' || strlen($accessToken) > 4096) {
            return null;
        }

        try {
            $credentials = json_decode(Crypt::decryptString($accessToken), true, 3, JSON_THROW_ON_ERROR);
        } catch (DecryptException|JsonException) {
            return null;
        }

        if (! is_array($credentials) || ! in_array($credentials['version'] ?? null, [1, 2], true)) {
            return null;
        }

        $code = $credentials['code'] ?? null;

        if (! is_string($code)) {
            return null;
        }

        return ['code' => $code];
    }
}
