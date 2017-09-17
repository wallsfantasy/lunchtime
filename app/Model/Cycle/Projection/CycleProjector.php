<?php

namespace App\Model\Cycle\Projection;

use App\Model\Cycle\Event\CycleClosedEvent;
use App\Model\Cycle\Event\CycleCreatedEvent;
use App\Model\Cycle\Event\MemberLeftCycleEvent;
use App\Model\Cycle\Event\UserJoinedCycleEvent;
use Illuminate\Redis\RedisManager;

class CycleProjector
{
    const KEY_IDS            = 'cycle:projection:ids';
    const KEY_PREFIX         = 'cycle:projection:';
    const KEY_PREFIX_MEMBERS = 'cycle:projection:members:';

    /** @var RedisManager */
    private $redis;

    public function __construct(RedisManager $redisManager)
    {
        $this->redis = $redisManager;
    }

    public function onCycleCreated(CycleCreatedEvent $event)
    {
        $this->redis->hmset(self::KEY_PREFIX . $event->cycleId,
            [
                'id' => $event->cycleId,
                'creator_user_id' => $event->cycleCreatorUserId,
                'name' => $event->cycleName,
                'lunchtime' => $event->cycleLunchtime,
                'propose_until' => $event->cycleProposeUntil,
            ]
        );
        $this->redis->sadd(self::KEY_PREFIX_MEMBERS . $event->cycleId, [$event->cycleCreatorUserId]);
        $this->redis->sadd(self::KEY_IDS, [$event->cycleId]);
    }

    public function onCycleClosed(CycleClosedEvent $event)
    {
        $this->redis->del(
            [
                self::KEY_PREFIX_MEMBERS . $event->cycleId,
                self::KEY_PREFIX . $event->cycleId,
            ]
        );
        $this->redis->srem(self::KEY_IDS, $event->cycleId);
    }

    public function onUserJoinedCycle(UserJoinedCycleEvent $event)
    {
        $this->redis->sadd(self::KEY_PREFIX_MEMBERS . $event->cycleId, [$event->userId]);
    }

    public function onMemberLeftCycle(MemberLeftCycleEvent $event)
    {
        $this->redis->srem(self::KEY_PREFIX_MEMBERS . $event->cycleId, [$event->memberUserId]);
    }

    public function subscribe($events)
    {
        $events->listen(CycleCreatedEvent::class, self::class . '@onCycleCreated');
        $events->listen(CycleClosedEvent::class, self::class . '@onCycleClosed');
        $events->listen(UserJoinedCycleEvent::class, self::class . '@onUserJoinedCycle');
        $events->listen(MemberLeftCycleEvent::class, self::class . '@onMemberLeftCycle');
    }
}
