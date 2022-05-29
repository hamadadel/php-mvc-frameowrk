<?php
namespace Framework\Routing;

class Router
{
    /**
     * The internal $routes array stores all the routes we define, using the add()
    method
     * @var array
     */
    protected array $routes = [];
    protected array $errorHandlers = [];

    public function add(string $method, string $path, callable|string $handler): Route
    {
        return $this->routes[] = new Route($method, $path, $handler);
    }

    public function dispatch()
    {
        $paths = $this->paths();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        $matching = $this->match($method, $uri);
        if ($matching) {
            try {
                return $matching->dispatch();
            }
            catch (\Throwable $e) {
                return $this->dispatchError();
            }
        }

        if (in_array($uri, $paths))
            return $this->dispatchNotAllowed();

        return $this->dispatchNotFound();
    }

    private function paths(): array
    {
        $paths = [];
        foreach ($this->routes as $route) {
            $paths[] = $route->path();
        }
        return $paths;
    }

    private function match(string $method, string $uri): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $uri)) {
                return $route;
            }
        }
        return null;
    }
    /**
     * Errors handling specific methods
     */

    public function errorHandler(int $code, callable $handler): void
    {
        $this->errorHandlers[$code] = $handler;
    }

    public function dispatchNotAllowed()
    {
        // ??=  says that the left-hand side should be set equal to the right-hand side if the left-hand side is null or undefined.
        $this->errorHandlers[400] ??= fn() => 'not allowed';
        return $this->errorHandlers[400]();
    }
    public function dispatchNotFound()
    {
        $this->errorHandlers[404] ??=  fn() => 'not found';
        return $this->errorHandlers[404]();
    }

    public function dispatchError()
    {
        $this->errorHandlers[500] ??=  fn() => 'server error';
        return $this->errorHandlers[500]();
    }

    public function redirect($to): void
    {
        header(
            "Location: {$to}", $replace = true, $code =301
        );
        exit();
    }
}