<?php

namespace Providers\Framework;

use Core\Container;
use Support\Utility\Session;
use Support\Contracts\ServiceProviderContract;

class SessionServiceProvider implements ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService('session', new Session);
    }

    public function boot($service)
    {
        $service->start();
    }
}
?>