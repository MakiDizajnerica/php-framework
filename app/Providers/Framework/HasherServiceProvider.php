<?php

namespace Providers\Framework;

use Core\Container;
use Support\Utility\Hasher;
use Support\Contracts\ServiceProviderContract;

class HasherServiceProvider implements ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService('hasher', new Hasher);
    }

    public function boot($service)
    {
        //
    }
}
?>