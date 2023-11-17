<?php


use App\Http\Controllers\TestController;
use Core\Route\Route;


Route::get('/test/{id}', [TestController::class, 'show']);
Route::get('/test', [TestController::class, 'index']);

