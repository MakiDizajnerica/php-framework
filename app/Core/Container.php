<?php

namespace Core;

class Container
{
    protected $providers = [];

    protected $services = [];

    protected $middlewares = [];

    /**
     * @param string $type
     * @param string $name
     * @param object $registrant
     * @param string $group [optional]
     * @return void
     */
    protected function register($type, $name, $registrant, $group = null)
    {
        if (! $group) {
            if (! isset($this->{$type}[$name])) {
                $this->{$type}[$name] = $registrant;
            }
        }
        else {
            if (! isset($this->{$type}[$group][$name])) {
                $this->{$type}[$group][$name] = $registrant;
            }
        }
    }

    /**
     * @param string $name
     * @param object $provider
     * @return void
     */
    public function registerProvider($name, $provider)
    {
        $this->register('providers', strtolower($name), $provider);
    }

    /**
     * @param string $name
     * @param object $service
     * @return void
     */
    public function registerService($name, $service)
    {
        $this->register('services', strtolower($name), $service);
    }

    /**
     * @param string $name
     * @param object $middleware
     * @param string $group [optional]
     * @return void
     */
    public function registerMiddleware($name, $middleware, $group = null)
    {
        $this->register('middlewares', strtolower($name), $middleware, $group);
    }

    /**
     * @param string $type
     * @return array
     */
    public function get($type)
    {
        return $this->{$type};
    }

    /**
     * @param string $name
     * @return object
     */
    public function getProvider($name)
    {
        return $this->get('providers')[strtolower($name)] ?? null;
    }

    /**
     * @param string $name
     * @return object
     */
    public function getService($name)
    {
        return $this->get('services')[strtolower($name)] ?? null;
    }

    /**
     * @param string $name
     * @return array|object
     */
    public function getMiddleware($name)
    {
        list($group, $name) = array_pad(
            explode('.', strtolower($name)), 2, null
        );
        if (isset($name)) {
            return $this->get('middlewares')[$group][$name] ?? null;
        }
        return $this->get('middlewares')[$group] ?? [];
    }

    /**
     * @param array $names
     * @return array
     */
    public function getWebMiddlewares(array $names)
    {
        $middlewares = [];
        foreach ($names as $name) {
            if (isset($this->get('middlewares')['web'][$name])) {
                $middlewares[] = $this->get('middlewares')['web'][$name];
            }
        }
        return $middlewares;
    }

    /**
     * @param string $type
     * @return object
     */
    public function getLast($type)
    {
        $values = array_values($this->{$type});
        return end($values);
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $group [optional]
     * @return void
     */
    protected function remove($type, $name, $group = null)
    {
        if (! $group) {
            unset($this->{$type}[$name]);
        }
        else {
            unset($this->{$type}[$group][$name]);
        }
    }

    /**
     * @param string $name
     * @return void
     */
    public function removeProvider($name)
    {
        $this->remove('providers', strtolower($name));
    }

    /**
     * @param string $name
     * @return void
     */
    public function removeService($name)
    {
        $this->remove('services', strtolower($name));
    }

    /**
     * @param string $name
     * @return void
     */
    public function removeMiddleware($name)
    {
        list($group, $name) = array_pad(
            explode('.', strtolower($name)), 2, null
        );
        $this->remove('middlewares', $name, $group ?? null);
    }

    /**
     * @param string $abstract
     * @return object
     */
    public function make($abstract)
    {
        return new $abstract;
    }
}
?>