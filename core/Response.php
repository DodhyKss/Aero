<?php

namespace Core;

/**
 * Response - HTTP Response Helper
 * 
 * Provides static methods for sending various types of HTTP responses:
 * HTML fragments, JSON, redirects, etc.
 */
class Response
{
    /**
     * Send an HTML response
     */
    public static function html(string $content, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: text/html; charset=UTF-8');
        echo $content;
        exit;
    }

    /**
     * Send a JSON response
     */
    public static function json(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Send a redirect response (SPA-aware)
     */
    public static function redirect(string $url, int $statusCode = 302): void
    {
        // Check if this is an AJAX/SPA request
        $isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') ||
                  (isset($_SERVER['HTTP_X_SPA_REQUEST']) && $_SERVER['HTTP_X_SPA_REQUEST'] === 'true');

        if ($isAjax) {
            // For SPA requests, send redirect info as JSON
            self::json([
                '_spa_redirect' => true,
                'url' => $url
            ]);
        } else {
            http_response_code($statusCode);
            header("Location: {$url}");
            exit;
        }
    }

    /**
     * Send a "no content" response
     */
    public static function noContent(): void
    {
        http_response_code(204);
        exit;
    }

    /**
     * Send a file download response
     */
    public static function download(string $filePath, ?string $filename = null): void
    {
        if (!file_exists($filePath)) {
            self::html('File not found', 404);
            return;
        }

        $filename = $filename ?? basename($filePath);
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

        header("Content-Type: {$mimeType}");
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
        exit;
    }

    /**
     * Set a custom response header
     */
    public static function header(string $name, string $value): void
    {
        header("{$name}: {$value}");
    }
}
