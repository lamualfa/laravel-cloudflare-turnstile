<?php

namespace RyanChandler\LaravelCloudflareTurnstile\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;
use RyanChandler\LaravelCloudflareTurnstile\Facades\Turnstile as Facade;

class Turnstile implements Rule
{
    protected array $messages = [];
    protected array $debugInfo = [];

    public function passes($attribute, $value): bool
    {
        // Log the validation attempt for debugging
        $this->debugInfo = [
            'attribute' => $attribute,
            'has_value' => !empty($value),
            'value_length' => $value ? strlen($value) : 0,
            'turnstile_secret_configured' => !empty(config('services.turnstile.secret')),
            'turnstile_key_configured' => !empty(config('services.turnstile.key')),
        ];

        try {
            $response = Facade::siteverify($value);

            // Log the response for debugging
            $this->debugInfo['response_success'] = $response->success;
            $this->debugInfo['response_error_codes'] = $response->errorCodes;

            if ($response->success) {
                Log::debug('Turnstile validation passed', $this->debugInfo);
                return true;
            }

            // Log the failure with debug information
            Log::warning('Turnstile validation failed', array_merge($this->debugInfo, [
                'error_codes' => $response->errorCodes,
            ]));

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
        } catch (\Exception $e) {
            // Log any exceptions during validation
            Log::error('Turnstile validation exception', array_merge($this->debugInfo, [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]));

            $this->messages = [__('cloudflare-turnstile::errors.unexpected')];
            return false;
        }
    }

    public function message(): string|array
    {
        // Return the first error message, or a default message if no specific errors
        if (empty($this->messages)) {
            return __('cloudflare-turnstile::errors.unexpected');
        }

        // If debug mode is enabled, append debug information
        if (config('app.debug') && !empty($this->debugInfo)) {
            $message = $this->messages[0];

            // Add helpful debug information
            if (!$this->debugInfo['turnstile_secret_configured']) {
                $message .= ' [DEBUG: Turnstile secret not configured]';
            }
            if (!$this->debugInfo['turnstile_key_configured']) {
                $message .= ' [DEBUG: Turnstile site key not configured]';
            }
            if (!$this->debugInfo['has_value']) {
                $message .= ' [DEBUG: No turnstile response provided]';
            }
            if (!empty($this->debugInfo['response_error_codes'])) {
                $message .= ' [DEBUG: Error codes: ' . implode(', ', $this->debugInfo['response_error_codes']) . ']';
            }

            return $message;
        }

        return $this->messages[0];
    }

    /**
     * Get debug information for the last validation attempt
     */
    public function getDebugInfo(): array
    {
        return $this->debugInfo;
    }
}
