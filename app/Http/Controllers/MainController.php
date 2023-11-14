<?php

namespace App\Http\Controllers;

use Core\Request;

class MainController
{
    public function __construct(protected Request $request)
    {

    }
    public function index()
    {
        dd($_SESSION['resolved']) ;
    }
}