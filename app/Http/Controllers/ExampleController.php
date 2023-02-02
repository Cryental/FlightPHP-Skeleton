<?php

namespace App\Http\Controllers;

use App\Models\User;
use Flight;
use Josantonius\Session\Facades\Session;

class ExampleController extends Controller
{
    public static function showCase($name, $id)
    {
//        $session = new Session();
//        $name = $session->get('hello');

//        User::query()->create([
//            'name'     => 'test',
//            'email'    => 'test',
//            'password' => 'test',
//        ]);

        Flight::render('index.twig', ['name' => "$name ($id)!"]);
    }
}
