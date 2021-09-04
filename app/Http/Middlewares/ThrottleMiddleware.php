<?php

namespace Http\Middlewares;

use Closure;
use RuntimeException;
use Support\Contracts\MiddlewareContract;

class ThrottleMiddleware implements MiddlewareContract
{
    public function handle($request, Closure $next)
    {
        //

        return $next($request);
    }
}
?>