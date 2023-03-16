<?php

namespace App;
require __DIR__ . '/../config.php';
require __DIR__ . '/../routes.php';

class Init
{
    public function initialize()
    {
        //initialize database
        \App\Kernel\Db::initialize();

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
            \App\Kernel\Db::initializeTest();
        } else {
            \App\Kernel\Db::initialize();
        }

        \App\Kernel\Db::createTable('tcgame_users', [
            'id int(11) primary key AUTO_INCREMENT',
            'name varchar(255)',
            'status int(11)',
        ]);

        \App\Kernel\Db::createTable('tcgame_groups', [
            'id int(11) primary key AUTO_INCREMENT',
            'name varchar(255)',
        ]);

        \App\Kernel\Db::createTable('tcgame_user_groups', [
            'id int(11) primary key AUTO_INCREMENT',
            'user_id int(11)',
            'group_id int(11)',
            'sort int(11)',
            'date_added timestamp',
        ]);
        require __DIR__ . '/helpers.php';
        //avoiding conflicts while populating tables
        \App\Kernel\Db::query('delete from tcgame_users', []);
        \App\Kernel\Db::query('delete from tcgame_groups', []);
        \App\Kernel\Db::query('delete from tcgame_user_groups', []);

        $users = availableUserNames();
        foreach ($users as $user) {
            \App\Models\User::create([
                'name' => $user,
                'status' => '0',
            ]);
        }
    }
}
