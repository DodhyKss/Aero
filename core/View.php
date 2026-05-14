<?php

namespace Core;

/**
 * View - Template Rendering Engine
 * 
 * Renders PHP view files with variable injection, layout support,
 * and component inclusion. Supports sections and slots for layouts.
 */
class View
{
    private static ?string $viewsPath = null;
    private static array $shared = [];
    private static array $sections = [];
    private static array $sectionStack = [];

    /**
     * Set the views directory path
     */
    public static function setViewsPath(string $path): void
    {
        self::$viewsPath = rtrim($path, '/\\');
    }

    /**
     * Get the views path
     */
    public static function getViewsPath(): string
    {
        if (self::$viewsPath === null) {
            self::$viewsPath = defined('VIEWS_PATH') ? \VIEWS_PATH : dirname(__DIR__) . '/app/Pages';
        }
        return self::$viewsPath;
    }

    /**
     * Share data across all views
     */
    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    /**
     * Render a view file and return its HTML content
     */
    public static function render(string $view, array $data = []): string
    {
        $viewPath = self::resolveViewPath($view);

        if (!file_exists($viewPath)) {
            return "<!-- View not found: {$view} -->";
        }

        // Merge shared data with view-specific data
        $data = array_merge(self::$shared, $data);

        // Extract variables for the view
        extract($data);

        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    /**
     * Render a component
     */
    public static function component(string $component, array $props = []): string
    {
        $componentPath = dirname(self::getViewsPath()) . '/Components/' . str_replace('.', '/', $component) . '.php';

        if (!file_exists($componentPath)) {
            return "<!-- Component not found: {$component} -->";
        }

        extract($props);

        ob_start();
        include $componentPath;
        return ob_get_clean();
    }

    /**
     * Start a section
     */
    public static function startSection(string $name): void
    {
        self::$sectionStack[] = $name;
        ob_start();
    }

    /**
     * End the current section
     */
    public static function endSection(): void
    {
        if (empty(self::$sectionStack)) {
            return;
        }

        $name = array_pop(self::$sectionStack);
        self::$sections[$name] = ob_get_clean();
    }

    /**
     * Get a section's content
     */
    public static function getSection(string $name, string $default = ''): string
    {
        return self::$sections[$name] ?? $default;
    }

    /**
     * Check if a section exists
     */
    public static function hasSection(string $name): bool
    {
        return isset(self::$sections[$name]);
    }

    /**
     * Resolve a view name to a file path
     */
    private static function resolveViewPath(string $view): string
    {
        // Replace dots with directory separators
        $view = str_replace('.', DIRECTORY_SEPARATOR, $view);
        return self::getViewsPath() . DIRECTORY_SEPARATOR . $view . '.php';
    }

    /**
     * Escape HTML for safe output
     */
    public static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
