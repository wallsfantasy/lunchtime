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
     * @return Member|Model
     * @throws CycleException
     */
    public function joinCycle(int $cycleId)
    {
        $userId = $this->authManager->guard()->id();

        try {
            /** @var Cycle $cycle */
            $cycle = Cycle::findOrFail($cycleId);
        } catch (\Throwable $e) {
            throw new CycleException('Cycle not found',
                CycleException::CODES_JOIN_CYCLE['join_non_exist_cycle'],
                $e,
                ['user_id' => $userId, 'cycle_id' => $cycleId]
            );
        }

        try {
            /** @var Member $member */
            $member = $cycle->members()->create(['user_id' => $userId]);
        } catch (\Throwable $e) {
            throw new CycleException('User is already joined',
                CycleException::CODES_JOIN_CYCLE['join_already_joined'],
                $e,
                ['user_id' => $userId, 'cycle_id' => $cycleId]
            );
        }

        return $member;
    }
}
