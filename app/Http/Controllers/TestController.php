<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Exception\ContainerException;
use Core\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class TestController extends Controller
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

    /**
     * @return mixed
     * @throws ContainerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function home()
    {
        return $this->view('welcome');
    }
}