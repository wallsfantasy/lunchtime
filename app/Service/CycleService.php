<?php

namespace App\Service;

use App\User;

class CycleService
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
