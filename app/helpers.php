<?php
function render(string $view, array $data = [], ?string $layout = 'base'): void {
    extract($data, EXTR_SKIP);
    ob_start();
    require $view;
    $content = ob_get_clean();
    if ($layout) {
        $title = $data['title'] ?? 'Document';
        require __DIR__ . "/layouts/{$layout}.php";
    } else {
        echo $content;
    }
}

function norm_path(string $p): string {
    $p = parse_url($p, PHP_URL_PATH) ?? '/';
    $p = rtrim($p, '/');
    return $p === '' ? '/' : $p;
}

/**
 * Підтримує:
 *  - {param}               -> (?P<param>[^/]+)
 *  - {param:\d{6}}         -> (?P<param>\d{6})
 *  - {param:[A-Za-z]{2,5}} -> ок
 *  - допускає фігурні дужки усередині патерна (квантифікатори)
 */
function route_regex(string $pattern): string {
    $rx = preg_replace_callback(
        '~\{([a-zA-Z_][a-zA-Z0-9_]*)(?::((?>[^{}]+|{[^}]*})+))?\}~',
        function ($m) {
            $name = $m[1];
            $pat  = isset($m[2]) && $m[2] !== '' ? $m[2] : '[^/]+';
            return '(?P<' . $name . '>' . $pat . ')';
        },
        $pattern
    );
    return '~^' . rtrim($rx, '/') . '$~';
}


function match_route(string $uri, array $routes): array {
    if (isset($routes[$uri])) return [$routes[$uri], []];
    foreach ($routes as $pattern => $cfg) {
        $rx = route_regex($pattern);
        if (preg_match($rx, rtrim($uri, '/'), $m)) {
            $params = [];
            foreach ($m as $k => $v) if (!is_int($k)) $params[$k] = $v;
            return [$cfg, $params];
        }
    }
    return [null, []];
}
