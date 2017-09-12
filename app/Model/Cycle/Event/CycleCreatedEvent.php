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

class CycleCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var string */
    public $cycleId;

    /** @var string */
    public $memberId;

    /** @var string */
    public $cycleLunchtime;

    /** @var string */
    public $proposeUntil;

    public function __construct(string $cycleId, string $memberId, string $cycleLunchtime, string $proposeUntil)
    {
        $this->cycleId = $cycleId;
        $this->memberId = $memberId;
        $this->cycleLunchtime = $cycleLunchtime;
        $this->proposeUntil = $proposeUntil;
    }
}
