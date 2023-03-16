<?php

namespace App\Controllers;

use App\Kernel\ForceLogin;
class GameController
{
    use ForceLogin;

    public function index()
    {
        view('game');
    }
}
