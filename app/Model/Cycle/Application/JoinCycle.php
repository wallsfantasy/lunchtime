<?php

namespace App\Model\Cycle\Application;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\CycleException;
use App\Model\Cycle\CycleRepository;
use Illuminate\Auth\AuthManager;

class JoinCycle
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
     * Join Cycle
     *
     * @param string $cycleId
     *
     * @return Cycle
     * @throws CycleException
     */
    public function joinCycle(string $cycleId)
    {
        $userId = $this->authManager->guard()->id();

        $cycle = $this->cycleRepo->get($cycleId);

        $cycle = $cycle->joinCycle($userId);

        $this->cycleRepo->save($cycle);

        // dispatch event

        return $cycle;
    }
}
