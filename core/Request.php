<?php

namespace Core;

/**
 * Request - HTTP Request Abstraction
 * 
 * Parses and provides access to the current HTTP request data
 * including URI, method, headers, query params, body, and AJAX detection.
 */
class Request
{
    private string $uri;
    private string $method;
    private array $query;
    private array $body;
    private array $headers;
    private array $files;
    private array $server;

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->uri = $this->parseUri();
        $this->method = $this->parseMethod();
        $this->query = $_GET;
        $this->body = $this->parseBody();
        $this->headers = $this->parseHeaders();
        $this->files = $_FILES;
    }

    /**
     * Parse the request URI, removing query string and base path
     */
    private function parseUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Remove base path if configured
        $basePath = defined('BASE_PATH') ? \BASE_PATH : '';
        if ($basePath && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = '/' . trim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }

    /**
     * Parse the HTTP method (supports method override via _method field)
     */
    private function parseMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // Support method override for forms
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        return $method;
    }

    /**
     * Parse the request body
     */
    private function parseBody(): array
    {
        if ($this->method === 'GET') {
            return [];
        }

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $rawBody = file_get_contents('php://input');
            return json_decode($rawBody, true) ?? [];
        }

        return $_POST;
    }

    /**
     * Parse HTTP headers
     */
    private function parseHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = str_replace('_', '-', substr($key, 5));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }

    /**
     * Check if the request is an AJAX/SPA request
     */
    public function isAjax(): bool
    {
        return ($this->getHeader('X-REQUESTED-WITH') === 'XMLHttpRequest') ||
               ($this->getHeader('X-SPA-REQUEST') === 'true');
    }

    /**
     * Get the request URI
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get the HTTP method
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get a query parameter
     */
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * Get all query parameters
     */
    public function allQuery(): array
    {
        return $this->query;
    }

    /**
     * Get a body/post parameter
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    /**
     * Get all body parameters
     */
    public function allInput(): array
    {
        return $this->body;
    }

    /**
     * Get a specific header
     */
    public function getHeader(string $name): ?string
    {
        $name = strtoupper(str_replace('-', '-', $name));
        return $this->headers[$name] ?? null;
    }

    /**
     * Get uploaded file info
     */
    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Get a server variable
     */
    public function server(string $key, mixed $default = null): mixed
    {
        return $this->server[$key] ?? $default;
    }

    /**
     * Check if a specific input exists
     */
    public function has(string $key): bool
    {
        return isset($this->body[$key]) || isset($this->query[$key]);
    }

    /**
     * Get input from both query and body
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }
}
