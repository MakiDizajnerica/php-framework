<?php

namespace Http\Middlewares\Framework;

use Closure;
use RuntimeException;
use Support\Contracts\MiddlewareContract;

class CheckMaintenanceMiddleware implements MiddlewareContract
{
    public function handle($request, Closure $next)
    {
        echo 1;

        return $next($request);
    }
}
?>