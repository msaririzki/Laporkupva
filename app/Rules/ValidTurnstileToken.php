<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Translation\PotentiallyTranslatedString;
use Throwable;

class ValidTurnstileToken implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = (string) config('services.turnstile.secret_key');
        $verificationUrl = (string) config('services.turnstile.verify_url');

        if ($secretKey === '' || $verificationUrl === '') {
            $fail('Verifikasi keamanan belum tersedia. Silakan coba beberapa saat lagi.');

            return;
        }

        try {
            $response = Http::asForm()
                ->acceptJson()
                ->connectTimeout(5)
                ->timeout(8)
                ->retry(
                    [250, 750],
                    fn (Throwable $exception, PendingRequest $request): bool => $exception instanceof ConnectionException,
                    throw: false,
                )
                ->post($verificationUrl, [
                    'secret' => $secretKey,
                    'response' => (string) $value,
                ]);
        } catch (ConnectionException $exception) {
            report($exception);
            $fail('Verifikasi keamanan sedang mengalami gangguan. Silakan coba lagi.');

            return;
        }

        if (! $response->successful()
            || $response->json('success') !== true
            || $response->json('action') !== 'submit_report') {
            $fail('Verifikasi keamanan gagal atau sudah kedaluwarsa. Silakan ulangi verifikasi.');
        }
    }
}
