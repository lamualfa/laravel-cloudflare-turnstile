<?php

return [
    'missing-input-secret' => 'The Turnstile secret key is not configured. Please check your services.turnstile.secret configuration.',
    'invalid-input-secret' => 'The Turnstile secret key is invalid or does not exist. Please verify your secret key in the Cloudflare dashboard.',
    'missing-input-response' => 'No Turnstile response was received. Please ensure the form includes the Turnstile widget.',
    'invalid-input-response' => 'The Turnstile response is invalid or has expired. Please refresh the page and try again.',
    'bad-request' => 'The Turnstile verification request was malformed. Please try again.',
    'timeout-or-duplicate' => 'The Turnstile response has expired or has already been used. Please refresh the widget.',
    'internal-error' => 'Cloudflare Turnstile service is experiencing issues. Please try again later.',
    'unexpected' => 'Turnstile verification failed. Please check your configuration and try again.',
];
