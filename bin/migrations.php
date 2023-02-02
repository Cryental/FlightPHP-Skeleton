#!/usr/bin/env php
<?php

require __DIR__.'/../bootstrap/app.php';

if (!empty($argv[1])) {
    if ($argv[1] == 'migrate') {
        $migrations = glob(__DIR__.'/../database/migrations/Table_*.php');

        foreach ($migrations as $migration) {
            $filename = pathinfo($migration)['filename'];
            $migrationClass = "\\App\Database\\Migrations\\".$filename;

            $migration = new $migrationClass();
            $migration->up();

            echo '[Done] '.$filename.PHP_EOL;
        }
    } elseif ($argv[1] == 'migrate:fresh') {
        $migrations = glob(__DIR__.'/../database/migrations/Table_*.php');

        foreach ($migrations as $migration) {
            $filename = pathinfo($migration)['filename'];
            $migrationClass = "\\App\Database\\Migrations\\".$filename;

            $migration = new $migrationClass();
            $migration->down();
            $migration->up();

            echo '[Done] '.$filename.PHP_EOL;
        }
    } else {
        exit('Please set up or down.');
    }
} else {
    exit('Please set up or down.');
}
