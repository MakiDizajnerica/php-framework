<?php

namespace Support\Contracts;

use Closure;

interface MiddlewareContract
{
    public function handle($request, Closure $next);
}
?>