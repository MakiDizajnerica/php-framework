<?php

namespace Providers\Framework;

use Core\View;
use Core\Container;
use Providers\DeferredServiceProvider;
use Support\Contracts\ServiceProviderContract;

class ViewServiceProvider implements DeferredServiceProvider, ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService('view', new View);
    }

    public function boot($service)
    {
        //
    }
}
?>