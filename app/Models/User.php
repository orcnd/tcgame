<?php
namespace App\Models;

use App\Kernel\Db;

class User
{   
    /** user id */
    public $id;

    /** user name */
    public $name;

    /** User constructor */
    public function __construct($id = null, $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /** Get the display name of the user */
    public function getDisplayName(bool $withId): string
    {
        if ($withId === true) {
            return '#' . $this->id . ' ' . $this->name;
        }
        return $this->name;
    }

    /** creates user */
    public static function create(array $data): User
    {
        if (isset($data['status']) === false) {
            $data['status'] = 0;
        }
        $id = Db::insertQuery(
            'INSERT INTO tcgame_users (name,status) VALUES (:name, :status)',
            [
                'name' => $data['name'],
                'status' => $data['status'],
            ]
        );
        return self::find($id);
    }

    /** get users group */
    public function group()
    {
        return \App\Models\Group::bindToUser($this);
    }

    /** finds user by id */
    public static function find(int $id)
    {
        $user = Db::query('SELECT * FROM tcgame_users WHERE id=:id', [
            'id' => $id,
        ]);
        if ($user->rowCount() > 0) {
            $userData = Db::fetch($user);
            return self::fetchUser($userData);
        }
        return null;
    }

    /** saves changes of user */

    public function save()
    {
        Db::insertQuery(
            'UPDATE tcgame_users SET name=:name, status=:status WHERE id=:id',
            [
                'name' => $this->name,
                'status' => $this->status,
                'id' => $this->id,
            ]
        );
    }

    /** fetches user from database */
    private static function fetchUser($userData)
    {
        $user = new User();
        $user->id = $userData->id;
        $user->name = $userData->name;
        $user->status = $userData->status;
        return $user;
    }

    /** finds user by username */
    public static function findByUsername(string $username) : ?User
    {
        $user = Db::query('SELECT * FROM tcgame_users WHERE name=:name', [
            'name' => $username,
        ]);
        if ($user->rowCount() > 0) {
            $userData = Db::fetch($user);
            return self::fetchUser($userData);
        }
        return null;
    }
}