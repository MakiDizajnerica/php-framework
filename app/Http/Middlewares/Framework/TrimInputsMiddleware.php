<?php

namespace Http\Middlewares\Framework;

use Closure;
use RuntimeException;
use Support\Contracts\MiddlewareContract;

class TrimInputsMiddleware implements MiddlewareContract
{
    public function handle($request, Closure $next)
    {
        echo 2;

        return $next($request);
    }
}
?>