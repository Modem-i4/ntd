<?php
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/api/db.php';
require __DIR__ . '/../app/api/utils.php';
require __DIR__ . '/../app/api/Controllers/SessionsController.php';
require __DIR__ . '/../app/api/Controllers/UserController.php';
require __DIR__ . '/../app/api/Controllers/StatsController.php';
require __DIR__ . '/../app/api/Controllers/GameController.php';
require __DIR__ . '/../app/api/Controllers/TrainingController.php';
require __DIR__ . '/../app/Router.php';

$pdo           = db();
$sessionsCtrl  = new SessionsController($pdo);
$usersCtrl     = new UserController($pdo);
$statsCtrl     = new StatsController($pdo);
$trainingCtrl  = new TrainingController($pdo);
$gameCtrl      = new GameController($pdo);

$routes = [
  'POST' => [
    '/api/sessions/record'        => [$sessionsCtrl, 'record'],
    '/api/users/save'             => [$usersCtrl, 'save'],
    '/api/users/get-or-create'    => [$usersCtrl, 'getOrCreate'],
    '/api/users/attach-to-user'   => [$usersCtrl, 'attachToUser'],
    '/api/games/create'           => [$gameCtrl, 'create'],
    '/api/games/update'        => [$gameCtrl, 'gamesUpdate'],
  ],
  'GET' => [
    '/api/stats'                  => [$statsCtrl, 'index'],
    '/api/training'               => [$trainingCtrl, 'index'],
    '/api/games/stats'            => [$gameCtrl, 'stats'],
    '/api/games/{game_code:\d{6}}'=> [$gameCtrl, 'scenario'],
  ],
];

$allowedOrigins = [
  'https://preview.construct.net',
  'https://ntd.test',
  'http://ntd',
];

$router = new Router($routes, $allowedOrigins, true);
$router->dispatch($_SERVER);
