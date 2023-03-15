<?php
namespace App\Models;

use App\Models\User;
class Group
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

    /**
     * creates user
     *
     * @param array $data
     *
     * @return Group
     */
    public static function create(array $data): Group
    {
        $id = \App\Kernel\Db::insertQuery(
            'INSERT INTO tcgame_groups (name) VALUES (:name)',
            [
                'name' => $data['name'],
            ]
        );
        $group = new Group();
        $group->name = $data['name'];
        $group->id = $id;
        return $group;
    }

    /**
     * assigns user to group
     *
     * @param User $user
     *
     * @return void
     */
    public function assignUser(User $user)
    {
        \App\Kernel\Db::insertQuery(
            'INSERT INTO tcgame_user_groups (user_id, group_id) VALUES (:user_id, :group_id)',
            [
                'user_id' => $user->id,
                'group_id' => $this->id,
            ]
        );
    }

    /**
     * finds group by id
     *
     * @param int $id
     *
     * @return Group|bool
     */
    public static function find(int $id)
    {
        $group = \App\Kernel\Db::query(
            'SELECT id,name FROM groups WHERE id = :id',
            [
                'id' => $id,
            ]
        );

        if ($group->rowCount() == 0) {
            return false;
        } else {
            $group = \App\Kernel\Db::fetch($group);
            $tempGroup = new Group();
            $tempGroup->id = $group->id;
            $tempGroup->name = $group->name;
            return $tempGroup;
        }
    }

    /**
     * finds group by name
     *
     * @param string $name
     *
     * @return void
     */
    public function removeUser(User $user)
    {
        \App\Kernel\Db::query(
            'DELETE FROM tcgame_user_groups WHERE user_id = :user_id AND group_id = :group_id',
            [
                'user_id' => $user->id,
                'group_id' => $this->id,
            ]
        );
        $groupUsersCount = \App\Kernel\Db::query(
            'SELECT COUNT(*) FROM tcgame_user_groups WHERE group_id = :group_id',
            [
                'group_id' => $this->id,
            ]
        );
        $groupUsersCount = $groupUsersCount->fetchColumn();
        //check for empty group
        if ($groupUsersCount == 0) {
            $this->destroy();
        }
    }

    /**
     * destroys group
     *
     * @return void
     */
    public function destroy()
    {
        \App\Kernel\Db::query(
            'DELETE FROM tcgame_user_groups WHERE group_id = :group_id',
            [
                'group_id' => $this->id,
            ]
        );
        \App\Kernel\Db::query(
            'DELETE FROM tcgame_groups WHERE id = :group_id',
            [
                'group_id' => $this->id,
            ]
        );
    }
}
