<?php

namespace Ntric\Backend\Controllers;

use Ntric\Backend\Databases\UserDatabase;

class ExampleController extends Controller {
    public static function showCase() {
        $userRepository = new UserDatabase();

        $done = $userRepository->DeleteById(1);

        \Flight::json($done);
    }
}