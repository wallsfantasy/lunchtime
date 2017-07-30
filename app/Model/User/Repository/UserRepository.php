<?php

namespace App\Model\User\Repository;

use App\Model\User\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /** @var User */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param int[] $ids
     *
     * @return iterable|Collection|User[]
     */
    public function findByIds(array $ids): iterable
    {
        $users = $this->user::whereIn('id', $ids)
            ->get();

        return $users;
    }
}
