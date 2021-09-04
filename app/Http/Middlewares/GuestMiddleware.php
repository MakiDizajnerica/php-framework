<?php

namespace Http\Middlewares;

use Closure;
use RuntimeException;
use Support\Contracts\MiddlewareContract;

class GuestMiddleware implements MiddlewareContract
{
    public function handle($request, Closure $next)
    {
        //

        return $next($request);
    }
}
?>