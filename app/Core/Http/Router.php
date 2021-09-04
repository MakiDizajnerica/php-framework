<?php

namespace Core\Http;

use ReflectionClass;
use ReflectionMethod;
use Core\Http\Request;
use Core\Http\Response;
use Exceptions\RoutingException;

class Router
{
    private $request,
            $response;

    private $routes = [],
            $callback = [],
            $params = [];

    /**
     * @param Request $request
     * @param Response $response
     * @param array $routes
     * @return Router
     */
    public function __construct(Request $request, Response $response, $routes) {
        $this->request = $request;
        $this->response = $response;
        $this->routes = $routes; 
    }

    /**
     * @return array
     */
    public function dispatch()
    {
        $method = $this->request->getMethod();
        $uri = $this->request->parseURI();

        if (($route = $this->matchRoute($method, $uri)) === false) {
            throw new RoutingException(404, sprintf("Route not defined '%s:%s'", strtoupper($method), $uri));
        }

        switch (gettype($route['callback'])) {
            case 'string':
                $callback = explode('@', $route['callback']);
            case 'array':
                $controller = sprintf('%s%s', $route['namespace'], $callback[0]);
                $action = $callback[1] ?? '__invoke';

                if (! class_exists($controller)) {
                    throw new RoutingException(404, sprintf("Controller '%s' does not exist.", $controller));
                }
                if (! (new ReflectionClass($controller = new $controller))->hasMethod($action)) {
                    throw new LogicException(404, sprintf("Action '%s' does not exist.", $action));
                }
                if (! (new ReflectionMethod($controller, $action))->isPublic()) {
                    throw new LogicException(404, sprintf("Action '%s' is not accessible.", $action));
                }

                $this->callback = [$controller, $action];
            break;
            case 'object':
                $this->callback = $route['callback'];
            break;
            default:
                throw new LogicException(500, "Wrong callback type, 'String', 'Array' or 'Closure Object' expected.");
            break;
        }

        $this->params = array_merge($this->params, $route['params']);
        array_unshift($this->params, $this->request);

        return $route['middleware'];
    }

    /**
     * @return string
     */
    public function finalize()
    {
        if (($response = call_user_func_array($this->callback, $this->params)) === false) {
            throw new LogicException(500, "Error calling 'call_user_func_array()'.");
        }
        return $response;
    }

    /**
     * @param string $method
     * @param string $uri
     * @return array|boolean
     */
    private function matchRoute($method, $uri) {
        foreach ($this->routes[$method] as $route) {
            if ((boolean) preg_match_all($route['pattern'], trim($uri, '/'), $matches)) {
                foreach (array_slice($matches, 1) as $key => $value) {
                    if (is_numeric($key)) {
                        array_push($this->params, trim($value[0], '/'));
                    }
                }      
                return([
                    'namespace' => $route['namespace'],
                    'callback' => $route['callback'],
                    'params' => $route['params'],
                    'middleware' => $route['middleware'],
                ]);
            }
        }
        return false;
    }
}
?>