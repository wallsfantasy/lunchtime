<?php

namespace App\Model\Cycle\Application;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\CycleRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Events\Dispatcher;

class CreateCycle
{
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
     * @param string             $name
     * @param \DateInterval      $lunchtime
     * @param \DateInterval|null $proposeUntil
     *
     * @return Cycle
     */
    public function createCycle(string $name, \DateInterval $lunchtime, ?\DateInterval $proposeUntil): Cycle
    {
        $userId = $this->authManager->guard()->id();

        $cycle = Cycle::createCycle($userId, ucfirst($name), $lunchtime, $proposeUntil);

        $this->cycleRepo->add($cycle);

        // dispatch event
        foreach ($cycle->domainEvents as $event) {
            $event->addMeta();
            $this->dispatcher->dispatch($event);
        }

        return $cycle;
    }
}
