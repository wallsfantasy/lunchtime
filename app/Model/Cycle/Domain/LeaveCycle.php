<?php

namespace App\Model\Cycle\Domain;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\Domain\Exception\LeaveCycleException;
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
     * @throws LeaveCycleException
     */
    public function leaveCycle(int $cycleId)
    {
        $userId = $this->authManager->guard()->id();

        try {
            /** @var Cycle $cycle */
            $cycle = Cycle::findOrFail($cycleId);
        } catch (\Exception $e) {
            throw new LeaveCycleException('Cycle not found',
                LeaveCycleException::CODES['LEAVE_NON_EXIST_CYCLE'], $e, $cycleId, $userId
            );
        }

        $result = (bool) Member::where(['cycle_id' => $cycleId, 'user_id' => $userId])->delete();
        if ($result === false) {
            throw new LeaveCycleException('User leave the Cycle which was never joined',
                LeaveCycleException::CODES['NOT_A_MEMBER'], null, $cycleId, $userId
            );
        }

        return $result;
    }
}
