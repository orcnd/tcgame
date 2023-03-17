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

    public function join()
    {
        $availableGroups = Group::findAvailableGroups(auth()->user());

        view('join', ['availableGroups' => $availableGroups]);
    }

    public function new_group()
    {
        $group = Group::create([
            'name' => 'Group Game (' . betterDate() . ')',
            'creator_id' => auth()->user()->id,
        ]);
        $group->join(auth()->user());
        redirect('/group');
    }

    public function group()
    {
        $group = auth()
            ->user()
            ->group();
        //group doesn't exist for some reason
        if ($group === null) {
            redirect('/game');
        }
        view('group', ['group' => $group]);
    }
}
