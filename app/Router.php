<?php

class Router
{
    public function __construct(
        private array $routes,
        private array $allowedOrigins = ['*'],
        private bool  $debug = false
    ) {}

    public function dispatch(array $server): void
    {
        if (!$this->isApiRoutes($this->routes)) return;

        $origin = $this->allowOrigin($server);
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Content-Type, X-API-Key, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Vary: Origin');

        $method = $server['REQUEST_METHOD'] ?? 'GET';
        $uri    = norm_path($server['REQUEST_URI'] ?? '/');

        if ($method === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        $this->mergeJsonIntoPost($server);

        try {
            [$handler, $params] = $this->resolve($method, $uri);

            // Параметри маршруту → у супер-глобали (для контролерів, що читають $_GET)
            if ($params) {
                $_GET     = $params + $_GET;
                $_REQUEST = $params + $_REQUEST;
            }

            if (!$handler) {
                $allowed = [];
                foreach ($this->routes as $m => $map) {
                    if (($this->findDynamic($uri, $map)[0] ?? null) || isset($map[$uri])) {
                        $allowed[] = $m;
                    }
                }
                if ($allowed) {
                    header('Allow: ' . implode(', ', array_values(array_unique($allowed))));
                    $this->sendError('Method Not Allowed', 405);
                }
                $this->sendError('Not Found', 404);
            }

            if (is_callable($handler)) {
                $result = $params ? call_user_func_array($handler, array_values($params))
                                  : call_user_func($handler);
            } elseif (is_array($handler) && isset($handler['call']) && is_callable($handler['call'])) {
                $result = $params ? call_user_func_array($handler['call'], array_values($params))
                                  : call_user_func($handler['call']);
            } else {
                $result = $handler;
            }

            if ($result === null) {
                http_response_code(204);
                exit;
            }
            $this->sendJson($result);

        } catch (\Throwable $e) {
            $this->sendError('Server error', 500, $e);
        }
    }

    private function isApiRoutes(array $routes): bool
    {
        foreach (['GET','POST','PUT','PATCH','DELETE','OPTIONS'] as $m) {
            if (array_key_exists($m, $routes)) return true;
        }
        return false;
    }

    private function resolve(string $method, string $uri): array {
        $map = $this->routes[$method] ?? [];
        if (isset($map[$uri])) return [$map[$uri], []];
        return $this->findDynamic($uri, $map);
    }

    private function findDynamic(string $uri, array $map): array {
        foreach ($map as $pattern => $handler) {
            $rx = route_regex($pattern);
            if (preg_match($rx, rtrim($uri, '/'), $m)) {
                $params = [];
                foreach ($m as $k => $v) if (!is_int($k)) $params[$k] = $v;
                return [$handler, $params];
            }
        }
        return [null, []];
    }

    private function allowOrigin(array $server): string
    {
        $origin = $server['HTTP_ORIGIN'] ?? '';
        if ($origin && (in_array('*', $this->allowedOrigins, true) || in_array($origin, $this->allowedOrigins, true))) {
            return $origin;
        }
        $scheme = (!empty($server['HTTPS']) && $server['HTTPS'] !== 'off') ? 'https' : 'http';
        return $scheme . '://' . ($server['HTTP_HOST'] ?? 'localhost');
    }

    private function mergeJsonIntoPost(array $server): void
    {
        $ct = $server['CONTENT_TYPE'] ?? $server['HTTP_CONTENT_TYPE'] ?? '';
        if (stripos($ct, 'application/json') === false) return;

        $raw = file_get_contents('php://input');
        if (!$raw) return;

        $json = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
            $_POST = $json + $_POST;
        }
    }

    private function sendJson($data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) { @ob_end_clean(); }
        echo is_string($data)
            ? $data
            : json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
