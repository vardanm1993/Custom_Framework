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
        echo "This is home page";
    }
    public function show($id)
    {
        echo $id;
    }
}