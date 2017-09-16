<?php

namespace App\Model\Cycle\Application;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\CycleException;
use App\Model\Cycle\CycleRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Events\Dispatcher;

class LeaveCycle
{
    /** @var Dispatcher $dispatcher */
    private $dispatcher;

    /** @var AuthManager $authManager */
    private $authManager;

    /** @var CycleRepository $cycleRepo */
    private $cycleRepo;

    public function __construct(
        Dispatcher $dispatcher,
        AuthManager $authManager,
        CycleRepository $cycleRepo
    ) {
        $this->dispatcher = $dispatcher;
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
        foreach ($cycle->domainEvents as $event) {
            $event->addMeta();
            $this->dispatcher->dispatch($event);
        }

        return $cycle;
    }
}
