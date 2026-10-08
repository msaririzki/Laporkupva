<?php

namespace App\Http\Requests;

use App\Rules\ValidTurnstileToken;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyPublicReporterRequest extends PublicReporterRequest
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
            ...parent::rules(),
            'website' => ['prohibited'],
            'cf-turnstile-response' => app()->isProduction() || (filled(config('services.turnstile.site_key')) && filled(config('services.turnstile.secret_key')))
                ? ['required', 'string', 'max:2048', new ValidTurnstileToken]
                : ['exclude'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [...parent::attributes(), 'cf-turnstile-response' => 'verifikasi keamanan'];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [...parent::messages(), 'cf-turnstile-response.required' => 'Selesaikan verifikasi keamanan untuk melanjutkan.'];
    }
}
