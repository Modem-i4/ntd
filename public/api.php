<?php
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/api/db.php';
require __DIR__ . '/../app/api/utils.php';
require __DIR__ . '/../app/api/Controllers/SessionsController.php';
require __DIR__ . '/../app/api/Controllers/UserController.php';
require __DIR__ . '/../app/api/Controllers/StatsController.php';
require __DIR__ . '/../app/api/Controllers/GameController.php';
require __DIR__ . '/../app/api/Controllers/TrainingController.php';
require __DIR__ . '/../app/api/Controllers/CertificatesController.php';
require __DIR__ . '/../app/api/Controllers/LinksController.php';
require __DIR__ . '/../app/Router.php';

require __DIR__ . '/../app/api/Controllers/ScenarioStatsController.php';

$pdo           = db();
$sessionsCtrl  = new SessionsController($pdo);
$usersCtrl     = new UserController($pdo);
$statsCtrl     = new StatsController($pdo);
$LithStatsCtrl     = new ScenarioStatsController($pdo,['vitovt','orsha','khotyn','danylo','kyiv']);
$DanStatsCtrl     = new ScenarioStatsController($pdo,['orlyk','unr','lesya']);
$trainingCtrl  = new TrainingController($pdo);
$gameCtrl      = new GameController($pdo);
$certCtrl      = new CertificatesController($pdo);
$teacherCtrl    = new TeacherController($pdo);
$linksCtrl    = new LinksController($pdo);

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
    '/api/stats/lith'              => [$LithStatsCtrl, 'index'],
    '/api/stats/dan'              => [$DanStatsCtrl, 'index'],
    '/api/stats'                  => [$statsCtrl, 'index'],
    '/api/stats/detailed'           => [$statsCtrl, 'detailed'],
    '/api/stats/cached'           => [$statsCtrl, 'cached'],
    '/api/stats/makeCache'        => [$statsCtrl, 'makeCache'],
    '/api/training'               => [$trainingCtrl, 'index'],
    '/api/guidelines'             => [$trainingCtrl, 'index'],
    '/api/games/stats'            => [$gameCtrl, 'stats'],
    '/api/games/{game_code:\d{6}}'=> [$gameCtrl, 'scenario'],
    '/api/certs'          =>            [$certCtrl, 'index'],
    '/certs/{uid:[A-Za-z0-9]{5}}' => [$certCtrl, 'check'],
    '/certs/pdf/{uid:[A-Za-z0-9]{5}}' => [$certCtrl, 'getPdf'],
    '/certs/{file:[^/]+\.pdf}'   => [$certCtrl, 'pdf'],
    '/api/certs/{uid:\[A-Za-z0-9]{5}}'=> [$certCtrl, 'single'],
    '/api/teacher/sessions' =>       [$teacherCtrl, 'sessions'],
    '/api/sessions/check' =>         [$sessionsCtrl, 'checkExist'],
    '/l/{goto}'              =>         [$linksCtrl, 'redirect'],
  ],
];

$allowedOrigins = [
  'https://preview.construct.net',
  'https://ntd.test',
  'http://ntd',
];

$router = new Router($routes, $allowedOrigins, true);
$router->dispatch($_SERVER);
