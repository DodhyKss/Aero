<?php

namespace Core;

/**
 * Simple .env file parser for Aero Framework
 */
class Env
{
    /**
     * Load environment variables from a .env file
     */
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Remove comments
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            // Split name and value
            if (str_contains($line, '=')) {
                list($name, $value) = explode('=', $line, 2);
                
                $name = trim($name);
                $value = trim($value);

                // Remove quotes from value
                if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                    $value = substr($value, 1, -1);
                } elseif (str_starts_with($value, "'") && str_ends_with($value, "'")) {
                    $value = substr($value, 1, -1);
                }

                if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                    putenv(sprintf('%s=%s', $name, $value));
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }

    /**
     * Get an environment variable with a default fallback
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        
        if ($value === false) {
            return $default;
        }

        switch (strtolower((string)$value)) {
            case 'true': return true;
            case 'false': return false;
            case 'null': return null;
        }

        return $value;
    }
}
