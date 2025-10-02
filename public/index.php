<?php
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/Router.php';

$uri    = norm_path($_SERVER['REQUEST_URI'] ?? '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if (str_starts_with($uri, '/api')) {
    require __DIR__ . '/../public/api.php';
    exit;
}

$routes = [
  '/'              => ['title' => 'ГО «Нова Традиція»',         'view' => __DIR__ . '/../app/views/home.php',       'layout' => 'base'],
  '/about'         => ['title' => 'About',                       'view' => __DIR__ . '/../app/views/about.php',      'layout' => 'base'],
  '/training'      => ['title' => 'Тренінг для школи',           'view' => __DIR__ . '/../app/views/training.php',   'layout' => 'base'],
  '/play'      => ['title' => 'Створити гру',                'view' => __DIR__ . '/../app/views/play.php',   'layout' => 'base', 'header_fixed'  => false],
  '/play/{id}' => ['title' => 'Сесія',                       'view' => __DIR__ . '/../app/views/session.php',   'layout' => 'base'],
  '/guidelines'    => ['title' => 'Методичні рекомендації',      'view' => __DIR__ . '/../app/views/guidelines.php', 'layout' => 'base'],
];

[$route, $params] = match_route($uri, $routes);

if (!$route) {
    http_response_code(404);
    render(__DIR__ . '/../app/views/404.php', ['title' => '404'], 'base');
    exit;
}

render(
    $route['view'],
    ['title' => $route['title'] ?? ''] + $params,
    $route['layout'] ?? 'base'
);
