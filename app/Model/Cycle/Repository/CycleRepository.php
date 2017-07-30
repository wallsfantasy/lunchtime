<?php

namespace App\Model\Cycle\Repository;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\Member;
use Illuminate\Support\Facades\DB;

class CycleRepository
{
    private const TABLES = [
        'cycle' => 'cycles',
        'member' => 'cycle_members',
    ];

    /** @var Cycle */
    private $cycle;

    public function __construct(Cycle $cycle)
    {
        $this->cycle = $cycle;
    }

    /**
     * @param int $id
     *
     * @return Cycle
     */
    public function find(int $id): Cycle
    {
        $cycles = $this->cycle::with(['members'])
            ->where('id', '=', $id)
            ->first();

        return $cycles;
    }

    /**
     * @param int $id
     *
     * @return Cycle
     * @throws \Exception
     */
    public function get(int $id): Cycle
    {
        $cycles = $this->cycle::with(['members'])
            ->where('id', '=', $id)
            ->firstOrFail();

        return $cycles;
    }

    /**
     * @param Cycle             $cycle
     * @param iterable|Member[] $members
     *
     * @return int
     * @throws \Throwable
     */
    public function add(Cycle $cycle, iterable $members = []): int
    {
        $cycleId = null;
        DB::transaction(function () use ($cycle, $members, &$cycleId) {
            $cycleId = DB::table(self::TABLES['cycle'])->insertGetId($cycle->toArray());

            if (count($members) > 0) {
                $memberData = [];
                foreach ($members as $member) {
                    $member = $member->toArray();
                    $member['cycle_id'] = $cycleId;
                    $memberData[] = $member;
                }
                DB::table(self::TABLES['member'])->insert($memberData);
            }
        });

        return $cycleId;
    }
}
