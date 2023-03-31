<?php

declare(strict_types=1);

namespace System\Router;

/**
 * Class Route
 * @package DevCoder
 */
final class Route
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array<string>
     */
    private $parameters = [];

    /**
     * @var array<string>
     */
    private $methods = [];

    /**
     * @var array<string>
     */
    private $vars = [];

    /**
     * Route constructor.
     * @param string $name
     * @param string $path
     * @param array $parameters
     *    $parameters = [
     *      0 => (string) Controller name : HomeController::class.
     *      1 => (string|null) Method name or null if invoke method
     *    ]
     * @param array $methods
     */
    public function __construct(string $name, string $path, array $parameters, array $methods = ['GET'])
    {
        if ($methods === []) {
            throw new \InvalidArgumentException('HTTP methods argument was empty; must contain at least one method');
        }
        $this->name = $name;
        $this->path = $path;
        $this->parameters = $parameters;
        $this->methods = $methods;
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public function match(string $path, string $method): bool
    {
        $regex = $this->getPath();
        foreach ($this->getVarsNames() as $variable) {
            $varName = trim($variable, '{\}');
            $regex = str_replace($variable, '(?P<' . $varName . '>[^/]++)', $regex);
        }

        if (in_array($method, $this->getMethods()) && preg_match('#^' . $regex . '$#sD', self::trimPath($path), $matches)) {
            $values = array_filter($matches, static function ($key) {
                return is_string($key);
            }, ARRAY_FILTER_USE_KEY);
            foreach ($values as $key => $value) {
                $this->vars[$key] = $value;
            }
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array|string[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return array|string[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return array
     */
    public function getVarsNames(): array
    {
        preg_match_all('/{[^}]*}/', $this->path, $matches);
        return reset($matches) ?? [];
    }

    /**
     * @return bool
     */
    public function hasVars(): bool
    {
        return $this->getVarsNames() !== [];
    }

    /**
     * @return string[]
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @static method: trimPath
     * @param string $path
     * @return string
     */
    public static function trimPath(string $path): string
    {
        return '/' . rtrim(ltrim(trim($path), '/'), '/');
    }
}