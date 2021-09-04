<?php

namespace Http\Middlewares;

use Closure;
use RuntimeException;
use Support\Contracts\MiddlewareContract;

class AuthMiddleware implements MiddlewareContract
{
    public function handle($request, Closure $next)
    {
        echo ' - auth - ';

        return $next($request);
    }
}
?>