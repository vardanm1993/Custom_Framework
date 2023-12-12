<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Exception\ContainerException;
use Core\Request;
use Core\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class TestController extends Controller
{
    public function __construct(protected Request $request)
    {

    }

    public function index()
    {
       return  "This is index page";
    }

    public function show($id)
    {
        echo $id;
    }

    /**
     * @return Response
     * @throws ContainerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function home(): Response
    {
        return $this->view('welcome');
    }
}