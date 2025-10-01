<?php
// public/api.php — фронт-контролер

require __DIR__ . '/../app/api/db.php';
require __DIR__ . '/../app/api/utils.php';
require __DIR__ . '/../app/api/Controllers/SessionsController.php';
require __DIR__ . '/../app/api/Controllers/UserController.php';
require __DIR__ . '/../app/api/Controllers/StatsController.php';
require __DIR__ . '/../app/api/Controllers/TrainingController.php';
require __DIR__ . '/../app/api/Router.php';

$pdo           = db();
$sessionsCtrl  = new SessionsController($pdo);
$usersCtrl     = new UserController($pdo);
$statsCtrl     = new StatsController($pdo);
$trainingCtrl     = new TrainingController($pdo);

// Таблиця маршрутів: кожен хендлер ПОВЕРТАЄ масив або null (204)
$routes = [
  'POST' => [
    '/api/sessions/record'  => [$sessionsCtrl, 'record'],
    '/api/users/save'           => [$usersCtrl, 'save'],
    '/api/users/get-or-create'  => [$usersCtrl, 'getOrCreate'],
    '/api/users/attach-to-user' => [$usersCtrl, 'attachToUser'],
  ],
  'GET' => [
    '/api/stats'                => [$statsCtrl, 'index'],
    '/api/training'             => [$trainingCtrl, 'index'],
  ],
];

// Дозволені origin’и (додай свої)
$allowedOrigins = [
  'https://preview.construct.net',
  'https://ntd.test',
  'http://ntd',
];

$router = new Router($routes, $allowedOrigins, /* debug: */ true);
$router->dispatch($_SERVER);
