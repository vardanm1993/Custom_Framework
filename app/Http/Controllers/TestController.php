<?php

namespace App\Http\Controllers;

use Core\Request;

class TestController
{
    public function __construct(protected Request $request)
    {

    }

    public function index()
    {
        echo "This is index page";
    }

    public function show($id)
    {
        echo $id;
    }
    public function home()
    {
        dd($_SESSION['resolved']);
    }
}