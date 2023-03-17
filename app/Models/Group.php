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
     * saves changes of group
     * 
     * @return void
     */
    public function save() {
        \App\Kernel\Db::insertQuery(
            'UPDATE tcgame_groups SET name = :name WHERE id = :id',
            [
                'name' => $this->name,
                'id' => $this->id,
            ]
        );
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
        $user->status = 1;
        $user->save();
    }

    /**
     * finds group by id
     *
     * @param int $id
     *
     * @return Group|bool
     */
    public static function find(int $id): Group|bool
    {
        $group = \App\Kernel\Db::query(
            'SELECT id,name FROM tcgame_groups WHERE id = :id',
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
     * finds group by user
     *
     * @param User $user
     * 
     * @return Group|bool
     */
    public static function findByUser(User $user): Group|bool
    {
        $userGroup = \App\Kernel\Db::query(
            'SELECT group_id FROM tcgame_user_groups WHERE user_id = :user_id',
            [
                'user_id' => $user->id,
            ]
        );

        if ($userGroup->rowCount() > 0) {
            $userGroup= \App\Kernel\Db::fetch($userGroup);
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

    /**
     * finds available group
     *
     * @param User $user
     *
     * @return array
     */
    public static function findAvailableGroups(User $user): array
    {
        $groups = \App\Kernel\Db::query(
            'SELECT id FROM tcgame_groups WHERE id NOT IN (SELECT group_id FROM tcgame_user_groups WHERE user_id = :user_id)',
            [
                'user_id' => $user->id,
            ]
        );

        if ($groups->rowCount() == 0) {
            return [];
        } else {
            $groups = \App\Kernel\Db::fetch($groups);
            $result = [];
            foreach ($groups as $group) {
                $group = self::find($group->id);
                if (count($group->users()) < 4) {
                    $result[] = $group;
                }
            }
            return $result;
        }
    }

    /**
     * returns all groups
     *
     * @return array
     */
    public static function getAll(): array
    {
        $query = 'SELECT id FROM tcgame_groups';

        $groups = \App\Kernel\Db::query($query, []);
        $groups = \App\Kernel\Db::fetch($groups);
        $result = [];
        if (is_array($groups)) {
            foreach ($groups as $group) {
                $result[] = self::find($group->id);
            }
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

    /**
     * returns all users in group
     *
     * @return array
     */
    public function users(): array
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

    /**
     * returns all active games
     *
     * @return array
     */
    public static function getActiveGames(): array
    {
        $groups = self::getAll();
        $result = [];
        foreach ($groups as $group) {
            if ($group->getUserCount() == 4) {
                $result[] = $group;
            }
        }
        return $result;
    }

    /**
     * returns all active players
     *
     * @return array
     */
    public static function getActivePlayers(): array
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

    /**
     * returns waiting list
     *
     * @return array
     */
    public static function getWaitingList(): array
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
