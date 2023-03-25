<?php
namespace App\Kernel;

use App\Models\User;

class Auth
{
    public static $user = null;

    /**
     * initializes session
     */
    public static function initialize() : void
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (self::check()) {
            self::$user = User::find($_SESSION['user_id']);
        }
    }

    /**
     * checks if user is logged in
     *
     * @return bool
     */
    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * returns user
     *
     * @return User
     */
    public static function user()
    {
        if (self::check()) {
            self::$user = User::find($_SESSION['user_id']);
        } else {
            self::$user = null;
        }
        return self::$user;
    }

    /**
     * logs in user
     *
     * @param string $user
     */
    public static function login(string $username)
    {
        $user = User::findByUsername($username);
        if ($user === null) {
            return false;
        }
        $_SESSION['user_id'] = $user->id;
        return true;
    }

    /**
     * logs out user
     */
    public static function logout()
    {
        unset($_SESSION['user_id']);
    }
}
