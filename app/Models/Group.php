<?php
namespace App\Models;

use App\Models\User;
use App\Kernel\Db;

class Group
{
    public $id,$name,$creator_id,$status;

    protected static $fillable=['name','creator_id','status'];

    private $statuses=[
        'waiting'=>'Waiting',
        'playing'=>'Playing',
        'finished'=>'Finished',
    ];

    /** starts the game */
    public function startGame() {
        $this->status='playing';
        $this->save();
    }

    /** Get the display name of the user */
    public function getDisplayName(bool $withId): string
    {
        if ($withId) {
            return '#' . $this->id . ' ' . $this->name;
        }
        return $this->name;
    }

    /**  creates group */
    public static function create(array $data): Group
    {
        $id = Db::insertQuery(
            'INSERT INTO tcgame_groups (name,creator_id,status) VALUES (:name,:creator_id,:status)',
            [
                'name' => $data['name'],
                'creator_id' => $data['creator_id'],
                'status' => $data['status'],
            ]
        );
        $group = new Group();
        foreach (self::$fillable as $key) {
            $group->$key = $data[$key];
        }
        $group->id = $id;

        return $group;
    }

    /** saves changes of group */
    public function save() {

        $sql='UPDATE tcgame_groups SET ';
        $params=[];
        foreach (self::$fillable as $key) {
            $sql.=$key.'=:'.$key.',';
            $params[$key]=$this->$key;
        }
        $sql=substr($sql,0,-1);
        $sql.=' WHERE id=:id';
        $params['id']=$this->id;
        Db::insertQuery($sql,$params);
    }

    /** join user to group */
    public function join(User $user) : string|bool
    {
        //self::removeUserFromAllGroups($user);
        if ($user->group()->id == $this->id) {
            return "alreadyIn";
        }
        Db::insertQuery(
            'INSERT INTO tcgame_user_groups (user_id, group_id,status,date_added) VALUES (:user_id, :group_id, :status,NOW())',
            [
                'user_id' => $user->id,
                'group_id' => $this->id,
                'status' => 'pending',
            ]
        );
        $user->status = 1;
        $user->save();
        return true;
    }


    /** sets user game status */
    public function setUserStatus(User $user, string $status):void {
       Db::query(
            'UPDATE tcgame_user_groups SET status=:status WHERE user_id=:user_id AND group_id=:group_id',
            [
                'user_id' => $user->id,
                'group_id' => $this->id,
                'status' => $status,
            ]
        );
    }

    /**  finds group by id */
    public static function find(int $id): Group|bool
    {
        $group = Db::query(
            'SELECT * FROM tcgame_groups WHERE id = :id',
            [
                'id' => $id,
            ]
        );

        if ($group->rowCount() == 0) {
            return false;
        } else {
            $group = Db::fetch($group);
            $tempGroup = new Group();
            $tempGroup->id = $group->id;
            foreach (self::$fillable as $key) {
                $tempGroup->$key = $group->$key;
            }
            return $tempGroup;
        }
    }

    /**  finds group by user */
    public static function findByUser(User $user): Group|bool
    {
        $userGroup = Db::query(
            'SELECT group_id FROM tcgame_user_groups WHERE user_id = :user_id',
            [
                'user_id' => $user->id,
            ]
        );

        if ($userGroup->rowCount() > 0) {
            $userGroup= Db::fetch($userGroup);
            return self::find($userGroup->group_id);
        }
        return false;
    }


    /** bind group model by user  */
    public static function bindToUser(User $user): Group|bool {
        $group=self::findByUser($user);
        if ($group) {
            $group->user=$user;
            $user->group=$group;
            return $group;
        }
        return false;
    }


    /** user's add time to this group */
    public function added_at(): bool|string {
        if(!$this->user) return false;
        $added_at=Db::query(
            'SELECT date_added FROM tcgame_user_groups WHERE user_id = :user_id AND group_id = :group_id',
            [
                'user_id' => $this->user->id,
                'group_id' => $this->id,
            ]
        );
        if ($added_at->rowCount() > 0) {
            $added_at= Db::fetch($added_at);
            return $added_at->date_added;
        }
        return false;
    }

    /** removing user from group */
    public function removeUser(User $user) :void { 
        Db::query(
            'DELETE FROM tcgame_user_groups WHERE user_id = :user_id AND group_id = :group_id',
            [
                'user_id' => $user->id,
                'group_id' => $this->id,
            ]
        );
        $groupUsersCount = Db::query(
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

    /** removes user from all groups */
    public static function removeUserFromAllGroups(User $user) :void
    {
        Db::query(
            'DELETE FROM tcgame_user_groups WHERE user_id = :user_id',
            [
                'user_id' => $user->id,
            ]
        );
    }

    /** destroys group */
    public function destroy() :void
    {
        Db::query(
            'DELETE FROM tcgame_user_groups WHERE group_id = :group_id',
            [
                'group_id' => $this->id,
            ]
        );
        Db::query(
            'DELETE FROM tcgame_groups WHERE id = :group_id',
            [
                'group_id' => $this->id,
            ]
        );
    }

    /** finds available group */
    public static function findAvailableGroups(User $user): array
    {
        $groups = Db::query(
            'SELECT id FROM tcgame_groups WHERE id NOT IN (SELECT group_id FROM tcgame_user_groups WHERE user_id = :user_id)',
            [
                'user_id' => $user->id,
            ]
        );

        if ($groups->rowCount() == 0) {
            return [];
        } else {
            $groups = Db::fetchALL($groups);
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

    /** returns all groups */
    public static function getAll(): array
    {
        $query = 'SELECT id FROM tcgame_groups';

        $groups = Db::query($query, []);
        $groups = Db::fetchAll($groups);
        $result = [];
        if (is_array($groups)) {
            foreach ($groups as $group) {
                $result[] = self::find($group->id);
            }
        }
        return $result;
    }

    /** returns total user count in groups */
    public function getUserCount()
    {
        $count = Db::query(
            'SELECT COUNT(*) FROM tcgame_user_groups WHERE group_id = :group_id',
            [
                'group_id' => $this->id,
            ]
        );
        $count = $count->fetchColumn();
        return $count;
    }

    /** returns all users in group */
    public function users(): array
    {
        $waiters = Db::query(
            'SELECT tcgame_user_groups.user_id,tcgame_user_groups.date_added,tcgame_user_groups.status,
            tcgame_users.name as user_name  FROM tcgame_user_groups
            inner join tcgame_users on tcgame_users.id=tcgame_user_groups.user_id 
              WHERE tcgame_user_groups.group_id = :group_id ',
            [
                'group_id' => $this->id,
            ]
        );
        if ($waiters->rowCount() == 0) {
            return [];
        }
        $waiters = Db::fetchAll($waiters);
        $result = [];
        foreach ($waiters as $waiter) {
            $user=new User($waiter->user_id,$waiter->user_name);
            $user->date_added=$waiter->date_added;
            if ($user->id==$this->creator_id) {
                $user->is_creator=true;
            }
            $user->game_status=$waiter->status;
            $result[] = $user;
        }
        return $result;
    }

    /** returns all active games */
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

    /** returns all active players */
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

    /** returns waiting list */
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