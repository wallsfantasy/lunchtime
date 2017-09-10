<?php

namespace App\Model\Cycle\Application;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\CycleRepository;
use Illuminate\Auth\AuthManager;

class CreateCycle
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
     * @param string             $name
     * @param \DateInterval      $lunchtime
     * @param \DateInterval|null $proposeUntil
     *
     * @return Cycle
     */
    public function createCycle(string $name, \DateInterval $lunchtime, ?\DateInterval $proposeUntil): Cycle
    {
        $userId = $this->authManager->guard()->id();

        $cycle = Cycle::createCycle($userId, $name, $lunchtime, $proposeUntil);

        $cycle = $this->cycleRepo->add($cycle);

        return $cycle;
    }
}
