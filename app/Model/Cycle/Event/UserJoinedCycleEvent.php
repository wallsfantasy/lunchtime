<?php

namespace App\Model\Cycle\Event;

use App\Events\Event;

class UserJoinedCycleEvent extends Event
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
        return 'cycle-user_joined';
    }
}
