<?php

namespace Ntric\Backend\Http\Controllers;

class ExampleController extends Controller {
    public static function showCase() {
        \Flight::render('index.twig', ['name' => 'Ntric']);
    }
}