<?php

return [
    // Main services
    \Providers\Framework\PipelineServiceProvider::class,
    \Providers\Framework\RequestServiceProvider::class,
    \Providers\Framework\ResponseServiceProvider::class,
    \Providers\Framework\RouteServiceProvider::class,
    \Providers\Framework\RouterServiceProvider::class,
    \Providers\Framework\SessionServiceProvider::class,
    \Providers\Framework\CookieServiceProvider::class,
    \Providers\Framework\HasherServiceProvider::class,
    \Providers\Framework\AuthServiceProvider::class,

    // Deferred services
    \Providers\Framework\ClientServiceProvider::class,
    \Providers\Framework\LocationServiceProvider::class,
    \Providers\Framework\ViewServiceProvider::class,
];

?>