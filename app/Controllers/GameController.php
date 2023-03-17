<?php

namespace App\Controllers;

use App\Kernel\ForceLogin;
use App\Models\Group;

class GameController
{
    use ForceLogin;

    public function index()
    {
        view('game');
    }

    public function homeStats()
    {
        return json([
            'active_games' => count(Group::getActiveGames()),
            'active_players' => count(Group::getActivePlayers()),
            'waiting_players' => count(Group::getWaitingList()),
        ]);
    }
}
