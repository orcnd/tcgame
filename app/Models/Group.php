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
     * join user to group
     *
     * @param User $user
     *
     * @return void
     */
    public function join(User $user)
    {
        self::removeUserFromAllGroups($user);
        \App\Kernel\Db::insertQuery(
            'INSERT INTO tcgame_user_groups (user_id, group_id,date_added) VALUES (:user_id, :group_id, NOW())',
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

    public static function findByUser(User $user)
    {
        $userGroup = \App\Kernel\Db::query(
            'SELECT group_id FROM tcgame_user_groups WHERE user_id = :user_id',
            [
                'user_id' => $user->id,
            ]
        );

        if ($userGroup !== false) {
            return self::find($userGroup->group_id);
        }
        return false;
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
     * removes user from all groups
     *
     * @param User $user
     *
     * @return void
     */
    public static function removeUserFromAllGroups(User $user)
    {
        \App\Kernel\Db::query(
            'DELETE FROM tcgame_user_groups WHERE user_id = :user_id',
            [
                'user_id' => $user->id,
            ]
        );
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

    public function findAvailableGroup()
    {
        $group = \App\Kernel\Db::query(
            'SELECT id,name FROM tcgame_groups WHERE id NOT IN (SELECT group_id FROM tcgame_user_groups WHERE user_id = :user_id)',
            [
                'user_id' => $this->id,
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

    public function getAll($filter = [])
    {
        $query = 'SELECT id FROM tcgame_groups';
        $params = [];
        if (isset($filter['id'])) {
            $query .= ' WHERE id = :id';
            $params['id'] = $filter['id'];
        }
        $groups = \App\Kernel\Db::query($query, $params);
        $groups = \App\Kernel\Db::fetch($groups);
        $result = [];
        foreach ($groups as $group) {
            $result[] = self::find($group->id);
        }
        return $result;
    }

    public function getUserCount()
    {
        $count = \App\Kernel\Db::query(
            'SELECT COUNT(*) FROM tcgame_user_groups WHERE group_id = :group_id',
            [
                'group_id' => $this->id,
            ]
        );
        $count = $count->fetchColumn();
        return $count;
    }

    public function users()
    {
        $waiters = \App\Kernel\Db::query(
            'SELECT user_id FROM tcgame_user_groups WHERE group_id = :group_id',
            [
                'group_id' => $this->id,
            ]
        );
        $waiters = \App\Kernel\Db::fetch($waiters);
        $result = [];
        foreach ($waiters as $waiter) {
            $result[] = User::find($waiter->user_id);
        }
        return $result;
    }

    public static function getWaiterCount()
    {
        $groups = self::getAll();
        $result = [];
        foreach ($groups as $group) {
            if ($group->getUserCount() < 4) {
                $result[] = $group;
            }
        }
        return $result;
    }

    public static function getWaitingList()
    {
        $groups = self::getAll();
        $result = [];
        foreach ($groups as $group) {
            if ($group->getUserCount() == 4) {
                array_push($result, $group->users());
            }
        }
        return $result;
    }
}
