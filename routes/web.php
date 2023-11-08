<?php


use App\Http\Controllers\MainController;
use Core\Route\Route;

Route::get('/home',[MainController::class, 'index']);
