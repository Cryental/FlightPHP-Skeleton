<?php

require __DIR__ . '/../vendor/autoload.php';

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Pagination\Paginator;

$configs = glob(__DIR__ . '/../config/*.php');

foreach ($configs as $config) {
    require($config);
}

Flight::map('error', function(Exception $ex){
    // Handle error
    echo $ex->getTraceAsString();
});

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler());

$log = new Logger($_ENV['APP_NAME']);
$log->pushHandler(new StreamHandler(__DIR__ . "/../storage/logs/ntric-" . \Carbon\Carbon::now()->toDateString() . '.log', Level::Debug));

Flight::map('error', function(Error|Exception $ex) use ($log, $whoops) {
    if ($_ENV['APP_DEBUG'] === 'true') {
        $whoops->handleException($ex);
    } else {
        $log->error($ex->getTraceAsString());
        Flight::json(['error' => true, 'info' => [
            'code' => 'INTERNAL_SERVER_ERROR',
            'message' => 'Fatal error occurred. This has been logged for further investigation.'
        ]], 500);
    }
});

date_default_timezone_set($_ENV['APP_TIMEZONE']);

$capsule = new Capsule();

$capsule->addConnection([
    'driver'   => $_ENV['DB_CONNECTION'],
    'host'     => $_ENV['DB_HOST'],
    'port'     => $_ENV['DB_PORT'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
]);

Paginator::currentPageResolver(function ($pageName = 'page') {
    return (int) ($_GET[$pageName] ?? 1);
});

$capsule->setAsGlobal();
$capsule->bootEloquent();

Flight::set('flight.views.path',  __DIR__ . '/../resources/views');
Flight::set('flight.compiled.views.path', __DIR__ . '/../storage/framework/views');

// Set the view renderer use twig. Before deploying to prod. activate the cache and
// set the web user allowed to read/write from/to the folder.
$loader = new Twig\Loader\FilesystemLoader(Flight::get('flight.views.path'));
$twigConfig = array(
    'cache'	=>	Flight::get('flight.compiled.views.path'),
    'debug'	=> $_ENV['APP_DEBUG'] === 'true'
);

// Sets twig as the view handler for Flight.
Flight::register('view', 'Twig\Environment', array($loader, $twigConfig), function($twig) {
    $twig->addExtension(new Twig\Extension\DebugExtension());
});

Flight::set('flight.log_errors', true);

// Map the call for ease of use.
Flight::map('render', function($template, $data = []){
    return Flight::view()->display($template, $data);
});

$routes = glob(__DIR__ . '/../routes/*.php');

foreach ($routes as $route) {
    require_once($route);
}