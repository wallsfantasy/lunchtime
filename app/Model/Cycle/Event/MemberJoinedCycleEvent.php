<?php

namespace App\Model\Cycle\Event;

use App\Events\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MemberJoinedCycleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var string */
    public $cycleId;

    /** @var string */
    public $cycleName;

    /** @var string */
    public $memberId;

    public function __construct(string $cycleId, string $cycleName, string $memberId)
    {
        $this->cycleId = $cycleId;
        $this->cycleName = $cycleName;
        $this->memberId = $memberId;
    }
}
