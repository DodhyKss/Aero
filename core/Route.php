<?php

namespace Core;

/**
 * Route - Individual Route Definition
 * 
 * Represents a single route with its method, path, callback,
 * middleware, layout, and name.
 */
class Route
{
    private string $method;
    private string $path;
    private mixed $callback;
    private array $middleware = [];
    private ?string $layout = null;
    private ?string $name = null;

    public function __construct(string $method, string $path, mixed $callback)
    {
        $this->method = $method;
        $this->path = $path;
        $this->callback = $callback;
    }

    /**
     * Set middleware for this route
     */
    public function middleware(array|string $middleware): self
    {
        if (is_string($middleware)) {
            $this->middleware[] = $middleware;
        } else {
            $this->middleware = array_merge($this->middleware, $middleware);
        }
        return $this;
    }

    /**
     * Set the layout for this route
     */
    public function layout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Set the name for this route
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    // Getters
    public function getMethod(): string { return $this->method; }
    public function getPath(): string { return $this->path; }
    public function getCallback(): mixed { return $this->callback; }
    public function getMiddleware(): array { return $this->middleware; }
    public function getLayout(): ?string { return $this->layout; }
    public function getName(): ?string { return $this->name; }
}
