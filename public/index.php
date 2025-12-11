<?php
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/Router.php';

require __DIR__ . '/../app/api/db.php';
require __DIR__ . '/../app/api/Controllers/StatsController.php';

$uri    = norm_path($_SERVER['REQUEST_URI'] ?? '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if (str_starts_with($uri, '/api')) {
    require __DIR__ . '/../public/api.php';
    exit;
}

$routes = [
  '/'              => ['title' => 'ГО «Нова Традиція»',         'view' => __DIR__ . '/../app/views/home.php', 
        'data' => fn () => [ 'stats' => (new StatsController(db()))->cached(), ],],
  '/about'         => ['title' => 'About',                       'view' => __DIR__ . '/../app/views/about.php'],
  '/trainingLith'      => ['title' => 'Тренінг для школи',           'view' => __DIR__ . '/../app/views/trainingLith.php'],
  '/trainingLith/{chapter}'      => ['title' => 'Тренінг для школи',           'view' => __DIR__ . '/../app/views/trainingLith.php'],
  '/training'      => ['title' => 'Тренінг для школи',           'view' => __DIR__ . '/../app/views/trainingPol.php'],
  '/training/{chapter}'      => ['title' => 'Тренінг для школи',           'view' => __DIR__ . '/../app/views/trainingPol.php'],
  '/play'      => ['title' => 'Створити гру',                'view' => __DIR__ . '/../app/views/play.php','header_fixed'  => false],
  '/play/{id}' => ['title' => 'Сесія',                       'view' => __DIR__ . '/../app/views/session.php'],
  '/guidelines'    => ['title' => 'Методичні рекомендації',      'view' => __DIR__ . '/../app/views/guidelines.php'],
  '/guidelines/{chapter}'    => ['title' => 'Методичні рекомендації',      'view' => __DIR__ . '/../app/views/guidelines.php'],
];

[$route, $params] = match_route($uri, $routes);

if (!$route) {
    http_response_code(404);
    render(__DIR__ . '/../app/views/404.php', ['title' => '404'], 'base');
    exit;
}
$extraData = [];
if (isset($route['data']) && is_callable($route['data'])) {
    $extraData = (array) $route['data']();
}

render(
    $route['view'],
    ['title' => $route['title'] ?? ''] + $params + $extraData,
    $route['layout'] ?? 'base'
);
