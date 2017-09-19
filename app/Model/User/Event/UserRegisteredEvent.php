<?php

namespace App\Model\User\Event;

use App\Events\Event;

class UserRegisteredEvent extends Event
{
    /** @var string */
    public $userId;

    /** @var string */
    public $userName;

    /** @var string */
    public $userEmail;

    public function __construct($userId, $userName, $userEmail)
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
    }

    public function getEventName()
    {
        return 'lunchtime:user:user-registered';
    }
}
