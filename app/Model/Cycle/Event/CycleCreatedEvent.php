<?php

namespace App\Model\Cycle\Event;

use App\Events\Event;

class CycleCreatedEvent extends Event
{
    /** @var string */
    public $cycleId;

    /** @var int */
    public $cycleCreatorUserId;

    /** @var string */
    public $cycleName;

    /** @var string */
    public $cycleLunchtime;

    /** @var string */
    public $cycleProposeUntil;

    public function __construct(string $cycleId, int $cycleCreatorUserId, string $cycleName, string $cycleLunchtime, string $cycleProposeUntil)
    {
        $this->cycleId = $cycleId;
        $this->cycleCreatorUserId = $cycleCreatorUserId;
        $this->cycleName = $cycleName;
        $this->cycleLunchtime = $cycleLunchtime;
        $this->cycleProposeUntil = $cycleProposeUntil;
    }

    public function getEventName()
    {
        return 'lunchtime:cycle:cycle-created';
    }
}
