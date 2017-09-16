<?php

namespace App\Model\Cycle\Application;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\CycleException;
use App\Model\Cycle\CycleRepository;
use Illuminate\Contracts\Events\Dispatcher;

class CloseCycle
{
    /** @var Dispatcher */
    private $dispatcher;

    /** @var CycleRepository $cycleRepo */
    private $cycleRepo;

    public function __construct(Dispatcher $dispatcher, CycleRepository $cycleRepo)
    {
        $this->dispatcher = $dispatcher;
        $this->cycleRepo = $cycleRepo;
    }

    /**
     * @param string $cycleId
     *
     * @return Cycle
     * @throws CycleException
     */
    public function closeCycle(string $cycleId): Cycle
    {
        $cycle = $this->cycleRepo->get($cycleId);

        $cycle->closeCycle();

        $this->cycleRepo->delete($cycle);

        foreach ($cycle->domainEvents as $event) {
            $event->addMeta();
            $this->dispatcher->dispatch($event);
        }

        return $cycle;
    }
}
