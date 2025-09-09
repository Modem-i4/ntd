<?php
require __DIR__ . '/../app/helpers.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';

$routes = [
  '/'      => ['title' => 'Home',  'view' => __DIR__ . '/../app/views/home.php',  'layout' => 'base'],
  '/about' => ['title' => 'About', 'view' => __DIR__ . '/../app/views/about.php', 'layout' => 'base'],
];

$route = $routes[$uri] ?? null;

if (!$route) {
  http_response_code(404);
  render(__DIR__ . '/../app/views/404.php', ['title' => '404'], 'base');
  exit;
}

render($route['view'], ['title' => $route['title'] ?? ''], $route['layout'] ?? 'base');
