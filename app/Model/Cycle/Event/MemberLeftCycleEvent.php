<?php

namespace App\Model\Cycle\Event;

use App\Events\Event;

class MemberLeftCycleEvent extends Event
{
    /** @var string */
    public $cycleId;

    /** @var string */
    public $cycleName;

    /** @var int */
    public $memberId;

    public function __construct(string $cycleId, string $cycleName, int $memberId)
    {
        parent::__construct();
        $this->cycleId = $cycleId;
        $this->cycleName = $cycleName;
        $this->memberId = $memberId;
    }

    public function getEventName()
    {
        return 'cycle-member_left';
    }
}
