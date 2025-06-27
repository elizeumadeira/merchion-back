<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\isLogged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'user'], function () {
    Route::resource('/', UserController::class)->only(['store']);

    // criei o método login na classe UserController mas normalmente eu adicionaria em uma LoginController
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/me', [UserController::class, 'me'])
        ->middleware(IsLogged::class)
        ->name('user.me');
});
