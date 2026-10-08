<?php

namespace App\Http\Requests;

use App\Rules\NoHtml;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PublicReporterRequest extends FormRequest
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
            'reporter_name' => ['required', 'string', 'max:120', new NoHtml],
            'reporter_email' => ['required', 'string', 'email', 'max:254'],
            'reporter_phone' => ['nullable', 'string', 'max:20', 'regex:/^\+628[1-9][0-9]{6,11}$/'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'reporter_name' => 'nama pelapor',
            'reporter_email' => 'email pelapor',
            'reporter_phone' => 'nomor HP pelapor',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'reporter_name.required' => 'Isi nama pelapor terlebih dahulu.',
            'reporter_phone.regex' => 'Masukkan nomor HP pelapor yang valid, misalnya 0812 3456 7890.',
        ];
    }

    public function identityFingerprint(): string
    {
        return hash('sha256', json_encode($this->only([
            'reporter_name', 'reporter_email', 'reporter_phone',
        ]), JSON_THROW_ON_ERROR));
    }

    protected function prepareForValidation(): void
    {
        $phone = $this->input('reporter_phone');
        if (is_string($phone)) {
            $phone = preg_replace('/[\s().-]+/', '', trim($phone)) ?? '';
        }
        $phone = match (true) {
            is_string($phone) && str_starts_with($phone, '08') => '+62'.substr($phone, 1),
            is_string($phone) && str_starts_with($phone, '8') => '+62'.$phone,
            is_string($phone) && str_starts_with($phone, '628') => '+'.$phone,
            default => $phone,
        };

        $this->merge([
            'reporter_name' => is_string($this->input('reporter_name')) ? trim($this->input('reporter_name')) : $this->input('reporter_name'),
            'reporter_email' => is_string($this->input('reporter_email')) ? mb_strtolower(trim($this->input('reporter_email'))) : $this->input('reporter_email'),
            'reporter_phone' => $phone !== '' ? $phone : null,
        ]);
    }
}
