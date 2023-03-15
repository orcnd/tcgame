<?php

// Autoload files using the Composer autoloader.
require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use App\Models\User;

const TEST_MODE = true;
final class TestUserModel extends TestCase
{
    /** @test */
    public function TestUserModelDisplayName()
    {
        \App\Init::install();

        $user = User::Create(['name' => 'test']);
        $this->assertEquals($user->getDisplayName(true), '#1 test');
    }

    /** @test */
    public function TestUserModelCreate()
    {
        \App\Init::install();

        $user = User::Create(['name' => 'test']);
        $this->assertEquals($user->name, 'test');
    }
}
