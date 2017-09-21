<?php

namespace App\Model\Cycle\Event;

use App\Events\Event;
use App\Model\User\UserRepository;
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

    /** @var array */
    public $user = [];

    public function __construct(string $cycleId, string $cycleName, int $userId)
    {
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

    public function enrich(UserRepository $userRepo)
    {
        $user = $userRepo->findByIds([$this->userId]);

        $this->user = $user->toArray();
    }
}
