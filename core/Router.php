<?php

namespace Core;

/**
 * Router - SPA-aware URL Router
 * 
 * Handles route registration, URL matching with parameters,
 * route groups, middleware assignment, and named routes.
 */
class Router
{
    private array $routes = [];
    private array $namedRoutes = [];
    private array $groupStack = [];

    /**
     * Register a GET route
     */
    public function get(string $path, mixed $callback, ?string $name = null): Route
    {
        return $this->addRoute('GET', $path, $callback, $name);
    }

    /**
     * Register a POST route
     */
    public function post(string $path, mixed $callback, ?string $name = null): Route
    {
        return $this->addRoute('POST', $path, $callback, $name);
    }

    /**
     * Register a PUT route
     */
    public function put(string $path, mixed $callback, ?string $name = null): Route
    {
        return $this->addRoute('PUT', $path, $callback, $name);
    }

    /**
     * Register a DELETE route
     */
    public function delete(string $path, mixed $callback, ?string $name = null): Route
    {
        return $this->addRoute('DELETE', $path, $callback, $name);
    }

    /**
     * Register a PATCH route
     */
    public function patch(string $path, mixed $callback, ?string $name = null): Route
    {
        return $this->addRoute('PATCH', $path, $callback, $name);
    }

    /**
     * Create a route group with shared attributes
     */
    public function group(array $attributes, callable $callback): void
    {
        $this->groupStack[] = $attributes;
        $callback($this);
        array_pop($this->groupStack);
    }

    /**
     * Add a route to the collection
     */
    private function addRoute(string $method, string $path, mixed $callback, ?string $name = null): Route
    {
        // Apply group attributes
        $prefix = '';
        $middleware = [];
        $layout = null;

        foreach ($this->groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= '/' . trim($group['prefix'], '/');
            }
            if (isset($group['middleware'])) {
                $mw = is_array($group['middleware']) ? $group['middleware'] : [$group['middleware']];
                $middleware = array_merge($middleware, $mw);
            }
            if (isset($group['layout'])) {
                $layout = $group['layout'];
            }
        }

        $fullPath = $prefix . '/' . trim($path, '/');
        $fullPath = '/' . trim($fullPath, '/');
        if ($fullPath !== '/') {
            $fullPath = rtrim($fullPath, '/');
        }

        $route = new Route($method, $fullPath, $callback);
        $route->middleware($middleware);

        if ($layout) {
            $route->layout($layout);
        }

        if ($name) {
            $route->name($name);
            $this->namedRoutes[$name] = $route;
        }

        $this->routes[] = $route;
        return $route;
    }

    /**
     * Resolve a URI to a route
     */
    public function resolve(string $uri, string $method = 'GET'): ?array
    {
        $uri = '/' . trim($uri, '/');
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        foreach ($this->routes as $route) {
            if ($route->getMethod() !== $method) {
                continue;
            }

            $params = $this->matchRoute($route->getPath(), $uri);
            if ($params !== false) {
                return [
                    'callback' => $route->getCallback(),
                    'params' => $params,
                    'middleware' => $route->getMiddleware(),
                    'layout' => $route->getLayout(),
                    'name' => $route->getName(),
                ];
            }
        }

        return null;
    }

    /**
     * Match a route pattern against a URI
     */
    private function matchRoute(string $pattern, string $uri): array|false
    {
        // Exact match
        if ($pattern === $uri) {
            return [];
        }

        // Convert route parameters to regex
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $matches)) {
            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            return $params;
        }

        return false;
    }

    /**
     * Generate URL from a named route
     */
    public function url(string $name, array $params = []): ?string
    {
        if (!isset($this->namedRoutes[$name])) {
            return null;
        }

        $path = $this->namedRoutes[$name]->getPath();

        foreach ($params as $key => $value) {
            $path = str_replace("{{$key}}", $value, $path);
        }

        return $path;
    }

    /**
     * Get all registered routes (for debugging)
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
