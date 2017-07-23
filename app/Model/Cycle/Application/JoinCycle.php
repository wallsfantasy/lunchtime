<?php

namespace App\Model\Cycle\Application;

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
     * @param int $cycleId
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
                JoinCycleException::CODES['ALREADY_JOINED'], $e, $cycleId
            );
        }

        $member = $cycle->members()->create(['user_id' => $userId]);

        return $member;
    }
}
