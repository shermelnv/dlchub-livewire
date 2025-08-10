<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies = '*';  // trust all proxies, or set specific IPs

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int|string|null
     */
    protected $headers = 'X-Forwarded-For, X-Forwarded-Host, X-Forwarded-Port, X-Forwarded-Proto, X-Forwarded-Prefix';
}
