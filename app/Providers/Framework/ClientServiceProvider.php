<?php

namespace Providers\Framework;

use Core\Container;
use Support\Utility\Client;
use Providers\DeferredServiceProvider;
use Support\Contracts\ServiceProviderContract;

class ClientServiceProvider implements DeferredServiceProvider, ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService('client', new Client);
    }

    public function boot($service)
    {
        //
    }
}
?>