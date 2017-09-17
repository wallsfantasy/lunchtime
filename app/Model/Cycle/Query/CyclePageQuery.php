<?php

namespace App\Model\Cycle\Query;

use App\Model\Cycle\CycleRepository;
use App\Model\Cycle\Projection\CycleProjector;
use App\Model\Cycle\Projection\UserProjector;
use Illuminate\Redis\RedisManager;

class CyclePageQuery
{
    const PAGE_SIZE = 20;

    /** @var RedisManager */
    private $redis;

    /** @var CycleRepository */
    private $cycleRepo;

    public function __construct(RedisManager $redisManager, CycleRepository $cycleRepo)
    {
        $this->redis = $redisManager;
        $this->cycleRepo = $cycleRepo;
    }

    public function queryPage(int $myUserId, ?int $page, ?string $searchName)
    {
        $page = $page ?? 1;
        $start = ($page - 1) * self::PAGE_SIZE;

        // find cycle ids
        if ($searchName !== null) {
            $cycles = $this->cycleRepo->pageByCycleName($searchName, $page, 'asc', self::PAGE_SIZE);

            $cycles = $cycles->items();

            $cycleIds = array_column($cycles, 'id');
        } else {
            $cycleIds = $this->redis->sort(
                CycleProjector::KEY_IDS,
                [
                    'LIMIT' => [$start, self::PAGE_SIZE],
                    'BY' => CycleProjector::KEY_PREFIX . '*',
                ]
            );
        }

        // create cycles data
        $cycles = [];
        foreach ($cycleIds as $cycleId) {
            $cycle = $this->redis->hgetall(CycleProjector::KEY_PREFIX . $cycleId);

            // get member user ids
            $memberUserIds = $this->redis->smembers(CycleProjector::KEY_PREFIX_MEMBERS . $cycleId);

            // mark which cycle are mine
            $cycle['is_my_cycle'] = in_array($myUserId, $memberUserIds, false);

            // add cycle member data
            $cycle['members'] = [];
            foreach ($memberUserIds as $userId) {
                $cycle['members'][] = $this->redis->hgetall(UserProjector::KEY_PREFIX . $userId);
            }

            $cycles[] = $cycle;
        }


        return $cycles;
    }
}
