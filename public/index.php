<?php
require __DIR__ . '/../app/helpers.php'; // your existing helpers with render()

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = rtrim($uri, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

// Delegate all /api traffic to a dedicated router (and exit).
if (str_starts_with($uri, '/api')) {
    require __DIR__ . '/../app/api_router.php';
    exit;
}

// ===== site pages (your existing site routes)
$routes = [
  '/'      => ['title' => 'ГО «Нова Традиція»', 'view' => __DIR__ . '/../app/views/home.php',  'layout' => 'base'],
  '/about' => ['title' => 'About',              'view' => __DIR__ . '/../app/views/about.php', 'layout' => 'base'],
  '/training' => ['title' => 'Тренінг для школи', 'view' => __DIR__ . '/../app/views/training.php', 'layout' => 'base'],
  '/guidelines' => ['title' => 'Методичні рекомендації', 'view' => __DIR__ . '/../app/views/guidelines.php', 'layout' => 'base'],
];

$route = $routes[$uri] ?? null;

if (!$route) {
  http_response_code(404);
  render(__DIR__ . '/../app/views/404.php', ['title' => '404'], 'base');
  exit;
}

render($route['view'], ['title' => $route['title'] ?? ''], $route['layout'] ?? 'base');
