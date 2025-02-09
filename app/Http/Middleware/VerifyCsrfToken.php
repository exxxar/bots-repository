<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/bot/*',
        '/bot/*/*',
        '/sanctum/token',
        '/integrations/1c/callback',
        '/payment-service-notify/tinkoff',
        '/payment-products-notify/tinkoff/*'
    ];
}
