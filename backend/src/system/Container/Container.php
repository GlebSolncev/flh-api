<?php

namespace System\Container;
;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class Container
{
    /**
     * @param string $classname
     * @return mixed
     * @throws ReflectionException
     */
    public function resolveClass(string $classname): mixed
    {
        if(!class_exists($classname)) return null;

        return $this->createClass($classname);
    }

    /**
     * @param mixed $controller
     * @param string $methodName
     * @param array $defaultParams
     * @return mixed
     * @throws ReflectionException
     */
    public function callMethod(mixed $controller, string $methodName, array $defaultParams): mixed
    {
        return $this->resolveMethod($controller, $methodName, $defaultParams);
    }

    /**
     * @param string $classname
     * @return mixed
     * @throws ReflectionException
     */
    protected function createClass(string $classname): mixed
    {
        $reflection = new ReflectionClass($classname);
        $constructor = $reflection->getConstructor();

        $deps = [];
        if ($constructor !== null) {
            $parameters = $constructor->getParameters();
            foreach ($parameters as $parameter) {
                if(!$parameter->getType() or $parameter->isDefaultValueAvailable()) continue;
                $paramName = $parameter->getType()->getName();

                if(class_exists($paramName)) {
                    $deps[] = $this->resolveClass($paramName);
                    continue;
                }

                $res = '';
                settype($res, $paramName);
                $deps[] = $res;
            }
        }

        return new $classname(...$deps);
    }

    /**
     * @param mixed $controller
     * @param string $methodName
     * @param array $defaultParams
     * @return mixed
     * @throws ReflectionException
     */
    protected function resolveMethod(mixed $controller,string $methodName,array $defaultParams): mixed
    {
        $ref = new ReflectionMethod(get_class($controller), $methodName);
        $params = [];

        if ($ref->getParameters() !== null) {
            foreach ($ref->getParameters() as $parameter) {
                if(in_array($parameter->getName(), array_keys($defaultParams))) continue;
                $class = new ($parameter->getType()->getName());
                if($class instanceof Request)
                    $class = $class::createFromGlobals();

                $params[] = $class;
            }
        }

        return $controller->{$methodName}(...array_merge([$defaultParams], $params));
    }
}