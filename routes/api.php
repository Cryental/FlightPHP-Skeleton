<?php

Flight::route('GET /@name/@id', [\App\Http\Controllers\ExampleController::class, 'showCase']);
