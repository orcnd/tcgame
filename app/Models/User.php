<?php
namespace App\Models;

use App\Kernel\Db;

class User
{
    public $id;
    public $name;

    public function __construct($id = null, $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Get the display name of the user
     *
     * @param bool $withId if true, the id will be added to the name
     *
     * @return string
     */
    public function getDisplayName(bool $withId): string
    {
        if ($withId === true) {
            return '#' . $this->id . ' ' . $this->name;
        }
        return $this->name;
    }

    /**
     * creates user
     *
     * @param array $data
     *
     * @return User
     */
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

    /**
     * get users group
     *
     * @return Group
     */
    public function group()
    {
        return \App\Models\Group::bindToUser($this);
    }

    /**
     * finds user by id
     *
     * @param int $id
     *
     * @return User|null
     */
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

    /**
     * saves changes of user
     *
     * @return void
     */

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

    private static function fetchUser($userData)
    {
        $user = new User();
        $user->id = $userData->id;
        $user->name = $userData->name;
        $user->status = $userData->status;
        return $user;
    }

    /**
     * finds user by username
     *
     * @param string $username
     *
     * @return User|null
     */
    public static function findByUsername(string $username)
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
