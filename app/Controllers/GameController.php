<?php

namespace App\Controllers;

use App\Kernel\ForceLogin;
use App\Models\Group;

class GameController
{
    use ForceLogin;

    public function index()
    {
        /*
        if (
            auth()
                ->user()
                ->group() !== null
        ) {
            redirect('/group');
        }
        */
        view('game');
    }

    public function homeStats()
    {
        json([
            'active_games' => count(Group::getActiveGames()),
            'active_players' => count(Group::getActivePlayers()),
            'waiting_players' => count(Group::getWaitingList()),
        ]);
    }

    public function join()
    {
        $availableGroups = Group::getAll();

        view('join', ['availableGroups' => $availableGroups]);
    }

    public function joinGroup($params = [])
    {
        if (count($params) == 1) {
            $id = $params[0];
        } else {
            redirect('/join');
        }

        $group = Group::find($id);
        if ($group === null) {
            redirect('/join');
        }

        $status = $group->join(auth()->user());
        if ($status === true) {
            redirect('/group');
        } else {
            if ($status == 'alreadyIn') {
                $message =
                    'You are already in a game. return to game <a href="/group">here</a>';
            } else {
                $message = $status;
            }
            view('/basicError', ['message' => $message, 'goBack' => true]);
        }
    }
    public function new_group()
    {
        $group = Group::create([
            'name' => 'Group Game (' . betterDate() . ')',
            'creator_id' => auth()->user()->id,
            'status' => 'waiting',
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

    public function groupWaitList()
    {
        $group = auth()
            ->user()
            ->group();
        $users = $group->users();
        $hash = md5(json_encode($users)); //for caching purposes
        if (isset($_POST['oldData']) && $_POST['oldData'] == $hash) {
            json(['users' => [], 'hash' => $hash, 'update' => false]);
        }
        json(['users' => $users, 'hash' => $hash, 'update' => true]);
    }

    public function startGame()
    {
        $group = auth()
            ->user()
            ->group();
        //group doesn't exist for some reason
        if ($group === null) {
            return json(['status' => 'error', 'message' => 'Group not found']);
        }
        $group->startGame();
        json(['status' => 'ok']);
    }

    public function setReady()
    {
        $group = auth()
            ->user()
            ->group();
        //group doesn't exist for some reason
        if ($group === null) {
            return json(['status' => 'error', 'message' => 'Group not found']);
        }
        $group->setUserStatus(auth()->user(), 'ready');
        json(['status' => 'ok']);
    }

    public function setPending()
    {
        $group = auth()
            ->user()
            ->group();
        //group doesn't exist for some reason
        if ($group === null) {
            return json(['status' => 'error', 'message' => 'Group not found']);
        }
        $group->setUserStatus(auth()->user(), 'pending');
        json(['status' => 'ok']);
    }
}
