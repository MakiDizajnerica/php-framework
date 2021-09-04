<?php

namespace Providers\Framework;

use Core\Container;
use Core\Http\Response;
use Support\Contracts\ServiceProviderContract;

class ResponseServiceProvider implements ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService('response', new Response);
    }

    public function boot($service)
    {
        //
    }
}
?>