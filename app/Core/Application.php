<?php

namespace Core;

use Exception;
use Core\Booter;
use Core\Container;

class Application
{
    private static $container;

    private $booter;

    /**
     * @return Application
     */
    public function __construct()
    {
        self::$container = new Container;
        $this->booter = new Booter(self::$container);
    }

    /**
     * @return Container
     */
    public static function container()
    {
        return self::$container;
    }

    /**
     * @return void
     */
    public function run()
    {
        try {
            $this->booter->handleApplicationBoot();
            $response = $this->booter->handleRequest();
            $this->booter->handleApplicationDeferredBoot();
            $this->booter->handleResponse($response, 200);
        }
        catch (Exception $e) {
            $this->booter->handleResponse($e->getMessage(), $e->getCode());
        }

        //dd(self::$container, false);
        //dd(self::$container->getService('session')->all(), false);
        memoryUsage();
    }
}
?>