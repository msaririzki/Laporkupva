<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;

class StoreAnonymousMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $report = $this->route('report');

        if (! $report instanceof Report) {
            return false;
        }

        $expiresAt = (int) $this->session()->get("tracked_reports.{$report->getKey()}", 0);

        return $expiresAt >= now()->getTimestamp();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:2', 'max:2000'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'body' => 'pesan',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'body' => trim((string) $this->input('body')),
        ]);
    }

    protected function failedAuthorization(): void
    {
        throw (new ModelNotFoundException)->setModel(Report::class);
    }
}
