<?php

namespace App\Model\Cycle\EventListener;

use App\Model\Cycle\Application\CloseCycle;
use App\Model\Cycle\CycleRepository;
use App\Model\Cycle\Event\MemberLeftCycleEvent;

class CloseCycleNoMemberListener
{
    /** @var CycleRepository */
    private $cycleRepo;

    /** @var CloseCycle */
    private $closeCycle;

    public function __construct(CycleRepository $cycleRepo, CloseCycle $closeCycle)
    {
        $this->cycleRepo = $cycleRepo;
        $this->closeCycle = $closeCycle;
    }

    public function handle(MemberLeftCycleEvent $event)
    {
        $cycle = $this->cycleRepo->find($event->cycleId);

        if ($cycle !== null && count($cycle->members) === 0) {
            $this->closeCycle->closeCycle($cycle->id);
        }
    }
}
