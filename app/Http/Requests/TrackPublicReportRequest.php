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
            'tracking_pin' => ['required', 'digits:6'],
            'access_token' => ['nullable', 'string', 'max:4096'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'tracking_code' => 'kode laporan',
            'tracking_pin' => 'PIN pelacakan',
        ];
    }

    protected function prepareForValidation(): void
    {
        $credentials = $this->credentialsFromAccessToken();

        $this->merge([
            'tracking_code' => strtoupper(trim((string) ($credentials['code'] ?? $this->input('tracking_code')))),
            'tracking_pin' => trim((string) ($credentials['pin'] ?? $this->input('tracking_pin'))),
        ]);
    }

    /** @return array{code: string, pin: string}|null */
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

        if (! is_array($credentials) || ($credentials['version'] ?? null) !== 1) {
            return null;
        }

        $code = $credentials['code'] ?? null;
        $pin = $credentials['pin'] ?? null;

        if (! is_string($code) || ! is_string($pin)) {
            return null;
        }

        return ['code' => $code, 'pin' => $pin];
    }
}
