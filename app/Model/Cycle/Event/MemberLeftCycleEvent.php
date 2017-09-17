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

    /** @var int */
    public $memberUserId;

    public function __construct(string $cycleId, string $cycleName, int $memberId, int $memberUserId)
    {
        parent::__construct();
        $this->cycleId = $cycleId;
        $this->cycleName = $cycleName;
        $this->memberId = $memberId;
        $this->memberUserId = $memberUserId;
    }

    public function getEventName()
    {
        return 'cycle-member_left';
    }
}
