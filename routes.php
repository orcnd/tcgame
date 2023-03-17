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

Route::set('/new_group', 'GET', [GameController::class, 'new_group']);

Route::set('/group', 'GET', [GameController::class, 'group']);

Route::set('/group_wait_list', 'POST', [
    GameController::class,
    'groupWaitList',
]);

Route::set('/join_group/:id', 'GET', [GameController::class, 'joinGroup']);
