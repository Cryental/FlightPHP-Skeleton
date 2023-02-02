<?php

require __DIR__.'/../vendor/autoload.php';

use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Pagination\Paginator;
use Josantonius\Session\Facades\Session;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

$configs = glob(__DIR__.'/../config/*.php');

foreach ($configs as $config) {
    require_once $config;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->safeLoad();

$log = new Logger($_ENV['APP_NAME']);
$log->pushHandler(new StreamHandler(__DIR__.'/../storage/logs/'.$_ENV['APP_NAME'].'-'.Carbon::now()->toDateString().'.log', Logger::DEBUG));

date_default_timezone_set($_ENV['APP_TIMEZONE']);

$capsule = new Capsule();

$capsule->addConnection([
    'driver'   => $_ENV['DB_CONNECTION'],
    'host'     => $_ENV['DB_HOST'],
    'port'     => $_ENV['DB_PORT'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
], $_ENV['DB_CONNECTION']);

Paginator::currentPageResolver(function ($pageName = 'page') {
    return (int) (Flight::request()->query[$pageName] ?? 1);
});

$capsule->setAsGlobal();
$capsule->bootEloquent();

Flight::set('flight.views.path', __DIR__.'/../resources/views');
Flight::set('flight.compiled.views.path', __DIR__.'/../storage/framework/views');
Flight::set('flight.log_errors', $_ENV['APP_DEBUG'] === 'true');

// Set the view renderer use twig. Before deploying to prod. activate the cache and
// set the web user allowed to read/write from/to the folder.
$loader = new Twig\Loader\FilesystemLoader(Flight::get('flight.views.path'));
$twigConfig = [
    'cache'	=> Flight::get('flight.compiled.views.path'),
    'debug'	=> $_ENV['APP_DEBUG'] === 'true',
];

// Sets twig as the view handler for Flight.
Flight::register('view', 'Twig\Environment', [$loader, $twigConfig], function ($twig) {
    $twig->addExtension(new Twig\Extension\DebugExtension());
});

// Map the call for ease of use.
Flight::map('render', function ($template, $data = []) {
    return Flight::view()->display($template, $data);
});

$whoops = new Run();
$whoops->pushHandler(new PrettyPageHandler());

Flight::map('error', function (Error|Exception $ex) use ($log, $whoops) {
    if ($_ENV['APP_DEBUG'] === 'true') {
        $whoops->handleException($ex);
    } else {
        $log->error($ex->getTraceAsString());
        Flight::render('error.twig');
    }
});

$routes = glob(__DIR__.'/../routes/*.php');

foreach ($routes as $route) {
    require_once $route;
}

// https://github.com/josantonius/php-session
$session = new Session();

$session->start([
    'name'                   => $_ENV['SESSION_NAME'],
    'sid_bits_per_character' => 4,
    'sid_length'             => 64,
    'cookie_samesite'        => 'Strict',
    'cookie_secure'          => true,
]);
