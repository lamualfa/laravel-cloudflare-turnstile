<?php

namespace RyanChandler\LaravelCloudflareTurnstile;

use Illuminate\Support\Facades\Http;
use RyanChandler\LaravelCloudflareTurnstile\Contracts\ClientInterface;
use RyanChandler\LaravelCloudflareTurnstile\Responses\SiteverifyResponse;

class Client implements ClientInterface
{
    public function __construct(
        protected string $secret,
    ) {
    }

    public function siteverify(string $response): SiteverifyResponse
    {
        $httpResponse = Http::retry(3, 100)
            ->asForm()
            ->acceptJson()
            ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $this->secret,
                'response' => $response,
            ]);

        if (!$httpResponse->ok()) {
            return SiteverifyResponse::failure(['bad-request']);
        }

        $data = $httpResponse->json();

        if ($data['success'] ?? false) {
            return SiteverifyResponse::success();
        }

        return SiteverifyResponse::failure($data['error-codes'] ?? ['unknown-error']);
    }

    public function dummy(): string
    {
        return ClientInterface::RESPONSE_DUMMY_TOKEN;
    }
}
