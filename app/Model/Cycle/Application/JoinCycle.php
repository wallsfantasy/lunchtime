<?php

namespace App\Model\Cycle\Application;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\Member;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

class JoinCycle
{
    /** @var AuthManager $authManager */
    private $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * Join Cycle
     *
     * @param int $cycleId
     *
     * @return Model|Member
     */
    public function joinCycle(int $cycleId)
    {
        /** @var Cycle $cycle */
        $cycle = Cycle::findOrFail($cycleId);

        $userId = $this->authManager->id();

        $member = $cycle->members()->create(['user_id' => $userId]);

        return $member;
    }
}
