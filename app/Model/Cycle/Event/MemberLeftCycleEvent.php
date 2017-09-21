<?php

namespace App\Model\Cycle\Event;

use App\Events\Event;
use App\Model\User\UserRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MemberLeftCycleEvent extends Event implements ShouldBroadcast
{
    /** @var string */
    public $cycleId;

    /** @var string */
    public $cycleName;

    /** @var int */
    public $memberId;

    /** @var int */
    public $memberUserId;

    /** @var array */
    public $memberUser = [];

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

    public function enrich(UserRepository $userRepo)
    {
        $memberUser = $userRepo->findByIds([$this->memberUserId]);

        $this->memberUser = $memberUser->toArray();
    }
}
