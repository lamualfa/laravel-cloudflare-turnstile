<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Rules;

use Illuminate\Contracts\Validation\Rule;
use RyanChandler\LaravelCloudflareTurnstile\Facades\Turnstile as Facade;

class Turnstile implements Rule
{
    protected array $messages = [];

    public function passes($attribute, $value): bool
    {
        $response = Facade::siteverify($value);

        if ($response->success) {
            return true;
        }

        $this->messages = [];
        foreach ($response->errorCodes as $errorCode) {
            $this->messages[] = match ($errorCode) {
                'missing-input-secret' => __('cloudflare-turnstile::errors.missing-input-secret'),
                'invalid-input-secret' => __('cloudflare-turnstile::errors.invalid-input-secret'),
                'missing-input-response' => __('cloudflare-turnstile::errors.missing-input-response'),
                'invalid-input-response' => __('cloudflare-turnstile::errors.invalid-input-response'),
                'bad-request' => __('cloudflare-turnstile::errors.bad-request'),
                'timeout-or-duplicate' => __('cloudflare-turnstile::errors.timeout-or-duplicate'),
                'internal-error' => __('cloudflare-turnstile::errors.internal-error'),
                default => __('cloudflare-turnstile::errors.unexpected'),
            };
        }

        return false;
    }

    public function message(): string|array
    {
        return $this->messages;
    }
}
