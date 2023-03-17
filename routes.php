<?php
namespace App;
use App\Kernel\Route;

use App\Controllers\MainController;
use App\Controllers\UserController;
use App\Controllers\GameController;

Route::set('/', 'GET', [MainController::class, 'index']);

Route::set('/login', 'GET', [UserController::class, 'login']);
Route::set('/login', 'POST', [UserController::class, 'login']);

Route::set('/logout', 'GET', [UserController::class, 'logout']);

Route::set('/game', 'GET', [GameController::class, 'index']);
Route::set('/join', 'GET', [GameController::class, 'join']);

Route::set('/game_home_stats', 'POST', [GameController::class, 'homeStats']);
