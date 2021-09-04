<?php

namespace Support\Contracts;

use Core\Container;

interface ServiceProviderContract
{
    public function register(Container $container);

    public function boot($service);
}
?>