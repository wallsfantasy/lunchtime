<?php

namespace App\Model\Cycle\Application;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\CycleException;
use App\Model\Cycle\CycleRepository;
use Illuminate\Auth\AuthManager;

class LeaveCycle
{
    /** @var AuthManager $authManager */
    private $authManager;

    /** @var CycleRepository $cycleRepo */
    private $cycleRepo;

    public function __construct(AuthManager $authManager, CycleRepository $cycleRepo)
    {
        $this->authManager = $authManager;
        $this->cycleRepo = $cycleRepo;
    }

    /**
     * @param string $cycleId
     *
     * @return Cycle
     * @throws CycleException
     */
    public function leaveCycle(string $cycleId): Cycle
    {
        $userId = $this->authManager->guard()->id();

        $cycle = $this->cycleRepo->getByMemberUserId($cycleId, $userId);

        $cycle->leaveCycle($userId);

        $this->cycleRepo->deleteMemberByUserId($cycle, $userId);

        // dispatch event

        return $cycle;
    }
}
