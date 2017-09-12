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

class CycleClosedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var string */
    public $cycleId;

    /** @var string */
    public $cycleName;

    public function __construct(string $cycleId, string $cycleName)
    {
        $this->cycleId = $cycleId;
        $this->cycleName = $cycleName;
    }
}
