<?php

namespace App\Model\Cycle\Application;

use App\User;

class JoinCycle
{
    /**
     * @param User $user
     * @param int  $cycleId
     *
     * @return User
     */
    public function joinCycle(User $user, int $cycleId)
    {
        $user->cycles()->attach($cycleId);

        return $user;
    }
}
