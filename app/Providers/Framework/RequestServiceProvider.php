<?php

namespace Providers\Framework;

use Core\Container;
use Core\Http\Request;
use Support\Contracts\ServiceProviderContract;

class RequestServiceProvider implements ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService('request', new Request);
    }

    public function boot($service)
    {
        //
    }
}
?>