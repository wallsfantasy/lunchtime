<?php

namespace App\Model\Cycle\Application;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\CycleException;
use App\Model\Cycle\CycleRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Bus\DispatchesJobs;

class JoinCycle
{
    use DispatchesJobs;

    /** @var Dispatcher */
    private $dispatcher;

    /** @var AuthManager */
    private $authManager;

    /** @var CycleRepository */
    private $cycleRepo;

    public function __construct(Dispatcher $dispatcher, AuthManager $authManager, CycleRepository $cycleRepo)
    {
        $this->dispatcher = $dispatcher;
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

        $cycle->joinCycle($userId);

        $this->cycleRepo->save($cycle);

        // dispatch event
        foreach ($cycle->domainEvents as $event) {
            $event->addMeta();
            $this->dispatcher->dispatch($event);
        }

        return $cycle;
    }
}
