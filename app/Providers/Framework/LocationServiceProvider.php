<?php

namespace Providers\Framework;

use Core\Container;
use Support\Utility\Location;
use Providers\DeferredServiceProvider;
use Support\Contracts\ServiceProviderContract;

class LocationServiceProvider implements DeferredServiceProvider, ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService(
            'location', new Location(
                $container->getService('client'),
                $container->getService('session'),
                $container->getService('request')->ip()
            )
        );
    }

    public function boot($service)
    {
        $service->collect();
    }
}
?>