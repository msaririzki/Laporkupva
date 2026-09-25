<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NoHtml implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        $containsHtmlTag = preg_match('/<\s*\/?\s*[a-z][^>]*>/iu', $value) === 1;
        $containsUnsafeControlCharacter = preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', $value) === 1;

        if ($containsHtmlTag || $containsUnsafeControlCharacter) {
            $fail('Kolom :attribute tidak boleh berisi tag HTML atau karakter berbahaya.');
        }
    }
}
