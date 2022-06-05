<?php
namespace Framework\Routing;

class Route
{
    protected string $method;
    protected string $path;
    protected  $handler;
    protected array $parameters = [];

    public function __construct(string $method, string $path, callable|string $handler)
    {
        $this->method = $method;
        $this->path= $path;
        $this->handler = $handler;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function method(): string
    {
        return $this->method;
    }
    public function path(): string
    {
        return $this->path;
    }

    public function matches(string $method, string $path): bool
    {
        if ($this->method === $method && $this->path === $path)
            return true;
        $parametersNames = [];

        $normalizedPath = $this->normalizePath($this->path);
        /**
         * Get all parameters names and replace them with regular expression syntax to match
         * optional and required parameters
         * '/home/' remains '/home/'
         * '/product/{id}/' becomes '/product/([^/]+)/'
         * '/blog/{slug?}/' becomes '/blog/([^/]*)(?:/?)'
         */

        $pattern = preg_replace_callback(
            '#{([^}]+)}/#', function (array $found) use (&$parametersNames) {
                $parametersNames[] = rtrim($found[1], '?');

            // if it's an optional parameter we make the following slash optional as well
                if (str_ends_with($found[1], '?'))
                    return '([^/]*)(?:/?)';
                return '([^/]+)/';
            }, $normalizedPath,
        );
        // if there are no route parameters, and it was not a literal match, then this route will never
        // match the requested path

        if (!str_contains($pattern, '+') && !str_contains($pattern, '*'))
            return false;
        preg_match("#{$pattern}#", $this->normalizePath($path), $matches);
        $parametersValues = [];
        array_shift($matches);

        if (count($matches) === count($parametersNames)) {
            // if the route matches the request path then we need to assemble the parameters
            // before we can return true for the match

            foreach ($matches as $value) {
                if ($value) {
                    array_push($parametersValues, $value);
                    continue;
                }
                array_push($parametersValues, null);
            }
            $emptyValues = array_fill(0, count($parametersNames), false);

            $parametersValues += $emptyValues;

            $this->parameters = array_combine(
                $parametersNames,
                $parametersValues,
            );
            return true;
        }
        return false;
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path, '/');
        $path = "/{$path}/";
        // replace the / sign if found between 2 and unlimited times with only one /
        $path = preg_replace('/[\/]{2,}/', '/', $path);
        return $path;
    }

    public function dispatch()
    {
        return call_user_func($this->handler);
    }
}