<?php

namespace App\Model\Cycle\Projection;

use App\Model\User\Event\UserRegisteredEvent;
use Illuminate\Redis\RedisManager;

class UserProjector
{
    const KEY_PREFIX = 'user:projection:';

    /** @var RedisManager */
    private $redis;

    public function __construct(RedisManager $redis)
    {
        $this->redis = $redis;
    }

    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $this->redis->hmset(self::KEY_PREFIX . $event->userId,
            [
                'id' => $event->userId,
                'name' => $event->userName,
                'email' => $event->userEmail,
            ]
        );
    }

    // public function onUserChangedName(UserChangedNameEvent $event) {...}

    // public function onUserChangedEmail(UserChangeEmailEvent $event) {...}

    // public function onUserUnregistered(UserUnregisteredEvent $event) {...}
}
