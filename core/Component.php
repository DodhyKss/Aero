<?php

namespace Core;

/**
 * Component - Base Component Class
 * 
 * Provides a base class for creating reusable PHP components
 * with props, state management, and rendering capabilities.
 */
abstract class Component
{
    protected array $props = [];
    protected array $state = [];
    protected ?string $id = null;

    public function __construct(array $props = [])
    {
        $this->props = array_merge($this->defaultProps(), $props);
        $this->id = $props['id'] ?? $this->generateId();
        $this->setup();
    }

    /**
     * Default props for the component
     */
    protected function defaultProps(): array
    {
        return [];
    }

    /**
     * Setup method called after construction
     */
    protected function setup(): void
    {
        // Override in child components
    }

    /**
     * Get a prop value
     */
    protected function prop(string $key, mixed $default = null): mixed
    {
        return $this->props[$key] ?? $default;
    }

    /**
     * Get the component's unique ID
     */
    protected function getId(): string
    {
        return $this->id;
    }

    /**
     * Generate a unique component ID
     */
    private function generateId(): string
    {
        $className = (new \ReflectionClass($this))->getShortName();
        return strtolower($className) . '-' . substr(uniqid(), -6);
    }

    /**
     * Render the component and return HTML string
     */
    abstract public function render(): string;

    /**
     * Convert to string (enables echo $component)
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Static factory method for quick rendering
     */
    public static function make(array $props = []): string
    {
        $instance = new static($props);
        return $instance->render();
    }

    /**
     * Render inline JavaScript for this component
     */
    protected function script(string $js): string
    {
        return "<script data-component=\"{$this->id}\">{$js}</script>";
    }

    /**
     * Render inline CSS for this component  
     */
    protected function style(string $css): string
    {
        return "<style data-component=\"{$this->id}\">{$css}</style>";
    }
}
