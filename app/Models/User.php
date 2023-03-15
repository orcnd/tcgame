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
        if ($withId) {
            return '#' . $this->id . ' ' . $this->name;
        }
        return $this->name;
    }

    public static function Create(array $data): User
    {
        $id = \App\Kernel\Db::insertQuery(
            'INSERT INTO users (name) VALUES (:name)',
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
