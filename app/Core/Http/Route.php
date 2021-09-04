<?php

namespace Core\Http;

class Route
{
    private $routes = [];

    private $cursor = '';

    private $namespace = 'Http\\Controller\\',
            $prefix = '',
            $params = [],
            $middleware = [];

    /**
     * @param string $name
     * @param array $arguments
     * @return Route
     */
    public function __call($name, array $arguments)
    {
        switch ($name = strtolower($name)) {
            case 'get':
            case 'post':
                $uri = sprintf('%s%s', $this->prefix, $arguments[0]);
                $callback = $arguments[1];
                $middleware = $arguments[2] ?? $this->middleware;
                return $this->addRoute($name, $uri, [
                    'uri' => $uri,
                    'path' => '',
                    'pattern' => $this->createSearchPattern($uri),
                    'namespace' => $this->namespace,
                    'callback' => $callback,
                    'params' => $this->params,
                    'middleware' => $middleware,
                ]);
            break;
        }
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $route
     * @return Route
     */
    private function addRoute($method, $uri, array $route)
    {
        if (! isset($this->routes[$method][$uri])) {
            if (isset($route['name'])) {
                unset($route['name']);
            }
            $this->routes[$method][$uri] = $route;
            $this->cursor = $method;
        }
        return $this;
    }

    /**
     * @param array $oldRoute
     * @param array $newRoute
     * @return Route
     */
    private function updateRoute(array $oldRoute, array $newRoute)
    {
        return $this->addRoute(
            $this->cursor, $newRoute['name'] ?? $oldRoute['uri'], array_merge($oldRoute, $newRoute)
        );
    }

    /**
     * @param string $uri
     * @param array $where [optional]
     * @return string
     */
    private function createSearchPattern($uri, array $where = [])
    {
        if ($where) {
            foreach ($where as $key => $value) {
                // Optional.
                if (strpos($uri, '?') !== false) {
                    $opt_key = sprintf("/\{(%s\?)\}/", $key);
                    $opt_regex = sprintf("?(\/%s|)", $value);
                    $uri = preg_replace($opt_key, $opt_regex, $uri);
                }
                // Required.
                $req_key = sprintf("/\{(%s)\}/", $key);
                $req_regex = sprintf("(?P<$1>%s)", $value);
                $uri = trim(preg_replace($req_key, $req_regex, $uri), '/');
            }
        }
        if (strpos($uri, '?') !== false) {
            $uri = preg_replace("/\{([a-zA-Z]+\?)\}/", "?(\/.+)", $uri);
        }
        $uri = trim(preg_replace("/\{([a-zA-Z]+)\}/", "(?P<$1>[a-zA-Z0-9\-]+)", $uri), '/');
        return sprintf('#^%s$#', $uri);
    }

    /**
     * @param string $namespace
     * @return Route
     */
    public function namespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @param string $prefix
     * @return Route
     */
    public function prefix($prefix)
    {
        $this->prefix = sprintf('/%s', ltrim($prefix, '/'));
        return $this;
    }

    /**
     * @param mixed $params
     * @return Route
     */
    public function params(...$params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param mixed $middleware
     * @return Route
     */
    public function middleware(...$middleware)
    {
        $this->middleware = $middleware;
        return $this;
    }

    /**
     * @param object $callback
     * @return void
     */
    public function group($callback)
    {
        call_user_func($callback, $this);
        $this->namespace = 'Http\\Controller\\';
        $this->prefix = '';
        $this->params = [];
        $this->middleware = [];
    }

    /**
     * @param string $name
     * @return Route
     */
    public function name($name)
    {
        return $this->updateRoute(
            $this->getLastRoute(), ['name' => $name]
        );
    }

    /**
     * @param array $where
     * @return Route
     */
    public function where(array $where)
    {
        $oldRoute = $this->getLastRoute();
        return $this->updateRoute($oldRoute, [
            'pattern' => $this->createSearchPattern($oldRoute['uri'], $where),
        ]);
    }

    /**
     * @return array
     */
    private function getLastRoute()
    {
        return array_pop($this->routes[$this->cursor]);
    }

    /**
     * @return array
     */
    public function getAllRoutes()
    {
        return $this->routes;
    }

    /**
     * @return void
     */
    public function loadAllRoutes()
    {
        return static function ($route) {
            require sprintf('%s/routes/web.php', ROOT);
            require sprintf('%s/routes/api.php', ROOT);
        };
    }
}
?>