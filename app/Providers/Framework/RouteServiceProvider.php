<?php

namespace Providers\Framework;

use Core\Container;
use Core\Http\Route;
use Support\Contracts\ServiceProviderContract;

class RouteServiceProvider implements ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService('route', new Route);
    }

    public function boot($service)
    {
        $service->loadAllRoutes()($service);
    }
}
?>