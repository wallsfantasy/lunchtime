<?php

namespace App\Model\Cycle\Domain;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\Member;
use App\Model\Propose\Exception\JoinCycleException;
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
     * @return Model|Member
     * @throws JoinCycleException
     */
    public function joinCycle(int $cycleId)
    {
        $userId = $this->authManager->guard()->id();

        try {
            /** @var Cycle $cycle */
            $cycle = Cycle::findOrFail($cycleId);
        } catch (\Exception $e) {
            throw new JoinCycleException('Cycle not found',
                JoinCycleException::CODES['JOIN_NON_EXIST_CYCLE'], $e, $cycleId, $userId
            );
        }

        try {
            /** @var Member $member */
            $member = $cycle->members()->create(['user_id' => $userId]);
        } catch (\Exception $e) {
            throw new JoinCycleException('User is already joined',
                JoinCycleException::CODES['ALREADY_JOINED'], $e, $cycleId, $userId
            );
        }

        return $member;
    }
}
