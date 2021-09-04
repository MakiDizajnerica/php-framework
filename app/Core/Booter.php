<?php

namespace Core;

use Core\Container;
use Support\Utility\Config;
use Providers\DeferredServiceProvider;

class Booter
{
    protected $container;

    /**
     * @param Container $container
     * @return Booter
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return void
     */
    public function handleApplicationBoot()
    {
        $this->registerProviders(Config::get('services'));
        $this->registerMiddlewares(Config::get('middlewares'));
        $this->registerServices();
    }

    /**
     * @return void
     */
    public function handleApplicationDeferredBoot()
    {
        $this->registerDeferredServices();
    }

    /**
     * @param array $providers [otpional]
     * @return void
     */
    protected function registerProviders(array $providers = [])
    {
        foreach ($providers as $provider) {
            $this->container->registerProvider($provider, new $provider);
        }
    }

    /**
     * @return void
     */
    protected function registerServices()
    {
        foreach ($this->container->get('providers') as $name => $provider) {
            if (! $provider instanceof DeferredServiceProvider) {
                $provider->register($this->container);
                $provider->boot(
                    $this->container->getLast('services')
                );
                $this->container->removeProvider($name);
            }
        }
    }

    /**
     * @return void
     */
    protected function registerDeferredServices()
    {
        foreach ($this->container->get('providers') as $name => $provider) {
            $provider->register($this->container);
            $provider->boot(
                $this->container->getLast('services')
            );
            $this->container->removeProvider($name);
        }
    }

    /**
     * @param array $middlewares [otpional]
     * @return void
     */
    protected function registerMiddlewares(array $middlewares = [])
    {
        foreach ($middlewares as $groups => $group) {
            foreach ($group as $name => $middleware) {
                if (is_numeric($name)) {
                    $name = $middleware;
                }
                $this->container->registerMiddleware($name, new $middleware, $groups);
            }
        }
    }





    public function handleRequest()
    {
        $this->container->getService('pipeline')->send(
            $this->container->getService('request')
        )->through(
            $this->container->getMiddleware('framework')
        )->thenReturn();

        $middlewares = $this->container->getService('router')->dispatch();

        $this->container->getService('pipeline')->send(
            $this->container->getService('request')
        )->through(
            $this->container->getWebMiddlewares($middlewares)
        )->thenReturn();

        return $this->container->getService('router')->finalize();
    }

    public function handleResponse($response, $code = 200)
    {
        $this->container->getService('response')->render($response, $code);
    }
}
?>