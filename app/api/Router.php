<?php
// app/api/Router.php

class Router
{
    public function __construct(
        private array $routes,
        private array $allowedOrigins = ['*'],
        private bool  $debug = false
    ) {}

    public function dispatch(array $server): void
    {
        // CORS
        $origin = $this->allowOrigin($server);
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Content-Type, X-API-Key');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Vary: Origin');

        $method = $server['REQUEST_METHOD'] ?? 'GET';
        $uri    = $this->normPath($server['REQUEST_URI'] ?? '/');

        // Preflight
        if ($method === 'OPTIONS') {
            http_response_code(204);
            return;
        }

        try {
            $handler = $this->routes[$method][$uri] ?? null;
            if (!$handler) {
                // Якщо шлях існує для іншого методу → 405
                $allowed = [];
                foreach ($this->routes as $m => $map) {
                    if (isset($map[$uri])) $allowed[] = $m;
                }
                if ($allowed) {
                    header('Allow: ' . implode(', ', $allowed));
                    $this->sendError('Method Not Allowed', 405);
                }
                $this->sendError('Not Found', 404);
            }

            $result = call_user_func($handler);

            if ($result === null) {
                http_response_code(204);
                return;
            }
            $this->sendJson($result);

        } catch (\Throwable $e) {
            $this->sendError('Server error', 500, $e);
        }
    }

    /* ------------ helpers ------------ */

    private function normPath(string $p): string
    {
        $p = parse_url($p, PHP_URL_PATH) ?? '/';
        $p = rtrim($p, '/');
        return $p === '' ? '/' : $p;
    }

    private function allowOrigin(array $server): string
    {
        $origin = $server['HTTP_ORIGIN'] ?? '';
        if ($origin && (in_array('*', $this->allowedOrigins, true) || in_array($origin, $this->allowedOrigins, true))) {
            return $origin;
        }
        // локальні запити без Origin — дозволяємо той самий хост
        $scheme = (!empty($server['HTTPS']) && $server['HTTPS'] !== 'off') ? 'https' : 'http';
        return $scheme . '://' . ($server['HTTP_HOST'] ?? 'localhost');
    }

    private function sendJson($data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) { @ob_end_clean(); }
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    private function sendError(string $msg, int $code, ?\Throwable $e = null): void
    {
        $payload = ['error' => $msg];
        if ($this->debug && $e) {
            $payload['detail'] = $e->getMessage();
            $payload['trace']  = $e->getTraceAsString();
        }
        $this->sendJson($payload, $code);
    }
}
