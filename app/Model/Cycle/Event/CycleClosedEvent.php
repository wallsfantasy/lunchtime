<?php

namespace App\Model\Cycle\Event;

use App\Events\Event;

class CycleClosedEvent extends Event
{
    /** @var string */
    public $cycleId;

    /** @var string */
    public $cycleName;

    public function __construct(string $cycleId, string $cycleName)
    {
        parent::__construct();
        $this->cycleId = $cycleId;
        $this->cycleName = $cycleName;
    }

    public function getEventName()
    {
        return 'cycle-closed';
    }
}
