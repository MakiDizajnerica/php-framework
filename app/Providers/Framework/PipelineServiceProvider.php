<?php

namespace Providers\Framework;

use Core\Container;
use Core\Pipeline\Pipeline;
use Support\Contracts\ServiceProviderContract;

class PipelineServiceProvider implements ServiceProviderContract
{
    public function register(Container $container)
    {
        $container->registerService('pipeline', new Pipeline);
    }

    public function boot($service)
    {
        //
    }
}
?>