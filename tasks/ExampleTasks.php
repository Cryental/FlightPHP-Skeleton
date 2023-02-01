<?php

require __DIR__.'/../bootstrap/app.php';

use Crunz\Schedule;

$schedule = new Schedule();

$task = $schedule->run('cmd');
$task->daily();

return $schedule;
