<?php

// Autoload files using the Composer autoloader.
require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Group;

const TEST_MODE = true;
final class TestsGeneral extends TestCase
{
    /** @test */
    public function testUserModelDisplayName(): void
    {
        \App\Init::install();

        $user = User::Create(['name' => 'test']);
        $this->assertEquals($user->getDisplayName(true), '#1 test');
        print PHP_EOL . 'Display name show test passed';
    }

    /** @test */
    public function testUserModelCreate()
    {
        \App\Init::install();
        $user = User::create(['name' => 'test']);
        $this->assertEquals($user->name, 'test');
        print PHP_EOL . 'User Create test passed';
    }

    public function testGroupModelCreate()
    {
        \App\Init::install();
        $group = Group::create(['name' => 'test Group']);
        $this->assertEquals($group->name, 'test Group');
        print PHP_EOL . 'Group create test passed';
    }
}
