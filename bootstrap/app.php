<?php

require 'vendor/autoload.php';

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

$configs = glob(__DIR__ . '/../config/*.php');

foreach ($configs as $config) {
    require($config);
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler());

$log = new Logger($_ENV['APP_NAME']);
$log->pushHandler(new StreamHandler(__DIR__ . '/../storage/logs/error.log', Level::Error));

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

$routes = glob(__DIR__ . '/../routes/*.php');

foreach ($routes as $route) {
    require($route);
}