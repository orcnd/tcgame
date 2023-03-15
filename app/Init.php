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
        if (TEST_MODE) {
            \App\Kernel\Db::initializeTest();
        } else {
            \App\Kernel\Db::initialize();
        }

        \App\Kernel\Db::createTable('users', [
            'id int(11) primary key AUTO_INCREMENT',
            'name varchar(255)',
        ]);

        \App\Kernel\Db::createTable('groups', [
            'id int(11) primary key AUTO_INCREMENT',
            'name varchar(255)',
        ]);

        \App\Kernel\Db::createTable('user_groups', [
            'id int(11) primary key AUTO_INCREMENT',
            'user_id int(11)',
            'group_id int(11)',
            'sort int(11)',
            'date_added timestamp',
        ]);
    }
}
