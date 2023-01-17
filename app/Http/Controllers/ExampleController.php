<?php

namespace App\Http\Controllers;

use Flight;

class ExampleController extends Controller
{
    public static function showCase()
    {
        Flight::render('index.twig', ['name' => 'Ntric']);
    }
}
