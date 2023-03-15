<?php
namespace App\Models;

class User
{
    public $id;
    public $name;

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
        $id = \App\Kernel\Db::insertQuery(
            'INSERT INTO tcgame_users (name) VALUES (:name)',
            [
                'name' => $data['name'],
            ]
        );
        $user = new User();
        $user->name = $data['name'];
        $user->id = $id;
        return $user;
    }
}
