<?php

namespace Providers\Framework;

use Core\Container;
use Support\Contracts\ServiceProviderContract;

class AuthServiceProvider implements ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService('auth', 'auth');
    }

    public function boot($service)
    {
        //
    }
}
?>