<?php

namespace Core;

/**
 * HttpClient - Server-side API Client
 * 
 * Digunakan untuk fetch data dari API eksternal di sisi PHP (server).
 * Contoh: $data = HttpClient::get('https://api.example.com/users');
 */
class HttpClient
{
    private static array $defaultHeaders = [
        'Content-Type: application/json',
        'Accept: application/json',
    ];

    private static ?string $baseUrl = null;
    private static ?string $token = null;

    /**
     * Set base URL untuk semua request
     * Contoh: HttpClient::setBaseUrl('https://api.example.com')
     */
    public static function setBaseUrl(string $url): void
    {
        self::$baseUrl = rtrim($url, '/');
    }

    /**
     * Set Bearer token untuk autentikasi
     */
    public static function setToken(string $token): void
    {
        self::$token = $token;
    }

    /**
     * GET request
     */
    public static function get(string $url, array $params = [], array $headers = []): array
    {
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return self::request('GET', $url, null, $headers);
    }

    /**
     * POST request
     */
    public static function post(string $url, array $data = [], array $headers = []): array
    {
        return self::request('POST', $url, $data, $headers);
    }

    /**
     * PUT request
     */
    public static function put(string $url, array $data = [], array $headers = []): array
    {
        return self::request('PUT', $url, $data, $headers);
    }

    /**
     * DELETE request
     */
    public static function delete(string $url, array $headers = []): array
    {
        return self::request('DELETE', $url, null, $headers);
    }

    /**
     * Core request method menggunakan cURL
     */
    private static function request(string $method, string $url, ?array $data = null, array $extraHeaders = []): array
    {
        // Prepend base URL jika URL tidak dimulai dengan http
        if (self::$baseUrl && !str_starts_with($url, 'http')) {
            $url = self::$baseUrl . '/' . ltrim($url, '/');
        }

        $ch = curl_init();

        // Merge headers
        $headers = array_merge(self::$defaultHeaders, $extraHeaders);

        // Tambah Authorization header jika token ada
        if (self::$token) {
            $headers[] = 'Authorization: Bearer ' . self::$token;
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        if ($data !== null && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'status' => 0,
                'error' => $error,
                'data' => null,
            ];
        }

        $decoded = json_decode($response, true);

        return [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'status' => $httpCode,
            'data' => $decoded ?? $response,
        ];
    }
}
