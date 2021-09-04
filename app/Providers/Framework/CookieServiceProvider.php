<?php

namespace Providers\Framework;

use Core\Container;
use Support\Utility\Cookie;
use Support\Contracts\ServiceProviderContract;

class CookieServiceProvider implements ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService('cookie', new Cookie);
    }

    public function boot($service)
    {
        //
    }
}
?>