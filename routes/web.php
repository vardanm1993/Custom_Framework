<?php

use App\Http\Controllers\TestController;
use Core\Route\Route;


Route::get('/test/{id}', [TestController::class, 'show']);
Route::get('/', [TestController::class, 'home']);
Route::get('/test', [TestController::class, 'index']);

Route::post('/test', [TestController::class, 'index']);


