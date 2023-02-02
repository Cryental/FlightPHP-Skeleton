<?php

namespace App\Http\Controllers;

use Flight;
use Josantonius\Session\Facades\Session;

class ExampleController extends Controller
{
    public static function showCase()
    {
        $session = new Session();

        $name = $session->get('hello');

        Flight::render('error.twig', ['name' => $name]);
    }
}
