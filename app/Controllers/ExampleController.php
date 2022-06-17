<?php

namespace Ntric\Backend\Controllers;

use Ntric\Backend\Databases\UserDatabase;

class ExampleController extends Controller {
    public static function showCase() {
        $userRepository = new UserDatabase();

        $done = $userRepository->UpdateById(1, [
            "name" => "John Doe12",
            "email" => "test" . rand(0, 100) . "@test.com"
        ]);

        \Flight::json($done);
    }
}