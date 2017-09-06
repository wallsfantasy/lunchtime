<?php

namespace App\Model\Cycle\Application;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\Member;
use Illuminate\Auth\AuthManager;

class LeaveCycle
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
     * @return bool
     * @throws CycleException
     */
    public function leaveCycle(int $cycleId)
    {
        $userId = $this->authManager->guard()->id();

        try {
            /** @var Cycle $cycle */
            $cycle = Cycle::findOrFail($cycleId);
        } catch (\Throwable $e) {
            throw new CycleException('Cycle not found',
                CycleException::CODES_LEAVE_CYCLE['leave_non_exist_cycle'],
                $e,
                ['user_id' => $userId, 'cycle_id' => $cycleId]
            );
        }

        $result = (bool) Member::where(['cycle_id' => $cycleId, 'user_id' => $userId])->delete();
        if ($result === false) {
            throw new CycleException('User leave the Cycle which was not the member',
                CycleException::CODES_LEAVE_CYCLE['not_a_member'],
                null,
                ['user_id' => $userId, 'cycle_id' => $cycleId]
            );
        }

        return $result;
    }
}
