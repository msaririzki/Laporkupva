<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
        $this->merge([
            'tracking_code' => strtoupper(trim((string) $this->input('tracking_code'))),
            'tracking_pin' => trim((string) $this->input('tracking_pin')),
        ]);
    }
}
