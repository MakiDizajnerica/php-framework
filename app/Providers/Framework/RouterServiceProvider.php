<?php

namespace Providers\Framework;

use Core\Container;
use Core\Http\Router;
use Support\Contracts\ServiceProviderContract;

class RouterServiceProvider implements ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService(
            'router', new Router(
                $container->getService('request'),
                $container->getService('response'),
                $container->getService('route')->getAllRoutes()
            )
        );
    }

    public function boot($service)
    {
        //
    }
}
?>