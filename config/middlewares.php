<?php

return [
    'framework' => [
        Http\Middlewares\Framework\CheckMaintenanceMiddleware::class,
        Http\Middlewares\Framework\TrimInputsMiddleware::class,
    ],
    'web' => [
        'auth' => Http\Middlewares\AuthMiddleware::class,
        'guest' => Http\Middlewares\GuestMiddleware::class,
        'throttle' => Http\Middlewares\ThrottleMiddleware::class,
    ],
];

?>