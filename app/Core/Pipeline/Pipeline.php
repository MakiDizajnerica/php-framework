<?php

namespace Core\Pipeline;

use Closure;
use Exception;

class Pipeline
{
    protected $passable,
              $pipes = [],
              $method = 'handle';

    /**
     * @param mixed $passable
     * @return Pipeline
     */
    public function send($passable)
    {
        $this->passable = $passable;
        return $this;
    }

    /**
     * @param array|mixed $pipes
     * @return Pipeline
     */
    public function through($pipes)
    {
        $this->pipes = is_array($pipes) ? $pipes : func_get_args();
        return $this;
    }

    /**
     * @return array
     */
    private function pipes()
    {
        return $this->pipes;
    }

    /**
     * @param string $method
     * @return Pipeline
     */
    public function via($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param Closure $destination
     * @return mixed
     */
    public function then(Closure $destination)
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes()), $this->carry(), $this->prepareDestination($destination)
        );
        return $pipeline($this->passable);
    }

    /**
     * @return mixed
     */
    public function thenReturn()
    {
        return $this->then(function ($passable) {
            return $passable;
        });
    }

    /**
     * @param Closure $destination
     * @return Closure
     */
    protected function prepareDestination(Closure $destination)
    {
        return function ($passable) use ($destination) {
            try {
                return $destination($passable);
            } 
            catch (Exception $e) {
                return $this->handleException($passable, $e);
            }
        };
    }

    /**
     * @return Closure
     */
    protected function carry()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                try {
                    if (is_callable($pipe)) {
                        return $pipe($passable, $stack);
                    } 
                    else if (! is_object($pipe)) {
                        [$name, $parameters] = $this->parsePipeString($pipe);
                        $pipe = new $pipe;
                        $parameters = array_merge([$passable, $stack], $parameters);
                    } 
                    else {
                        $parameters = [$passable, $stack];
                    }

                    $carry = method_exists($pipe, $this->method) ? $pipe->{$this->method}(...$parameters) : $pipe(...$parameters);
                    return $this->handleCarry($carry);
                } 
                catch (Exception $e) {
                    return $this->handleException($passable, $e);
                }
            };
        };
    }

    /**
     * @param string $pipe
     * @return array
     */
    protected function parsePipeString($pipe)
    {
        [$name, $parameters] = array_pad(explode(':', $pipe, 2), 2, []);
        if (is_string($parameters)) {
            $parameters = explode(',', $parameters);
        }
        return [$name, $parameters];
    }

    /**
     * @param mixed $carry
     * @return mixed
     */
    protected function handleCarry($carry)
    {
        return $carry;
    }

    /**
     * @param mixed $passable
     * @param Exception $e
     * @return mixed
     * @throws Exception
     */
    protected function handleException($passable, Exception $e)
    {
        throw $e;
    }
}
?>