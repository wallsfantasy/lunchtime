<?php

namespace App\Model\Cycle\Event;

use App\Events\Event;
use Illuminate\Broadcasting\Channel;

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
        $this->cycleId = $cycleId;
        $this->cycleName = $cycleName;
        $this->memberId = $memberId;
        $this->memberUserId = $memberUserId;
    }

    public function getEventName()
    {
        return 'lunchtime:cycle:member-left';
    }

    public function broadcastOn()
    {
        return new Channel('cycle');
    }

    public function broadcastAs()
    {
        return $this->getEventName();
    }
}
