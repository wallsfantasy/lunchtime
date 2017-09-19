<?php

namespace App\Model\Cycle\Event;

use App\Events\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserJoinedCycleEvent extends Event implements ShouldBroadcast
{
    /** @var string */
    public $cycleId;

    /** @var string */
    public $cycleName;

    /** @var int */
    public $userId;

    public function __construct(string $cycleId, string $cycleName, int $userId)
    {
        parent::__construct();
        $this->cycleId = $cycleId;
        $this->cycleName = $cycleName;
        $this->userId = $userId;
    }

    public function getEventName()
    {
        return 'lunchtime:cycle:user-joined';
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
