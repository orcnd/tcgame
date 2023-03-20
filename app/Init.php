<?php

namespace App;
require __DIR__ . '/../config.php';
require __DIR__ . '/../routes.php';

use App\Kernel\Db;

class Init
{
    public function initialize()
    {
        //initialize database
        Db::initialize();

        \App\Kernel\Auth::initialize();

        //initialize MVC
        $kernel = new \App\Kernel\Mvc();
    }

    /**
     * Installs script
     *
     * @param bool $isTest if true, the database will be installed in memory
     *
     * @return void
     */
    public static function install($isTest = false)
    {
        if (defined('TEST_MODE') && TEST_MODE) {
            Db::initializeTest();
        } else {
            Db::initialize();
        }

        DB::dropTable(['tcgame_users', 'tcgame_groups', 'tcgame_user_groups']);

        Db::createTable('tcgame_users', [
            'id int(11) primary key AUTO_INCREMENT',
            'name varchar(255)',
            'status int(11)',
        ]);

        Db::createTable('tcgame_groups', [
            'id int(11) primary key AUTO_INCREMENT',
            'name varchar(255)',
            'creator_id int(11)',
            'status varchar(255)',
        ]);

        Db::createTable('tcgame_user_groups', [
            'id int(11) primary key AUTO_INCREMENT',
            'user_id int(11)',
            'group_id int(11)',
            'status varchar(255)',
            'date_added timestamp',
            'date_removed timestamp',
        ]);

        require __DIR__ . '/helpers.php';

        $users = availableUserNames();
        foreach ($users as $user) {
            \App\Models\User::create([
                'name' => $user,
                'status' => '0',
            ]);
        }
    }
}
