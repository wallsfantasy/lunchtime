<?php

namespace App\Model\Cycle;

use Illuminate\Database\Eloquent\Collection;

class CycleRepository
{
    /** @var Cycle */
    private $cycle;

    /** @var Member */
    private $member;

    public function __construct(Cycle $cycle, Member $member)
    {
        $this->cycle = $cycle;
        $this->member = $member;
    }

    /**
     * @param string $id
     *
     * @return Cycle
     */
    public function find(string $id): Cycle
    {
        $cycles = $this->cycle::with(['members'])
            ->where('id', '=', $id)
            ->first();

        return $cycles;
    }

    /**
     * @return iterable|Collection|Cycle[]
     */
    public function findAllByMemberUserId(int $memberUserId): iterable
    {
        $cycles = $this->cycle::with('members')
            ->whereHas('members', function ($query) use ($memberUserId) {
                $query->where('user_id', '=', $memberUserId);
            })
            ->get();

        return $cycles;
    }

    /**
     * @param string $id
     *
     * @return Cycle
     * @throws \Exception
     */
    public function get(string $id): Cycle
    {
        $cycles = $this->cycle::with(['members'])
            ->where('id', '=', $id)
            ->firstOrFail();

        return $cycles;
    }

    /**
     * @param string $id
     * @param int    $memberUserId
     *
     * @return Cycle
     * @throws \Exception
     */
    public function getByMemberUserId(string $id, int $memberUserId): Cycle
    {
        $cycle = $this->cycle::with(['members'])
            ->where('id', '=', $id)
            ->whereHas('members', function ($query) use ($memberUserId) {
                $query->where('user_id', '=', $memberUserId);
            })
            ->firstOrFail();

        return $cycle;
    }

    /**
     * @return Cycle
     */
    public function add(Cycle $cycle): Cycle
    {
        $cycle->push();

        return $cycle;
    }

    /**
     * @param Cycle $cycle
     *
     * @return Cycle
     */
    public function save(Cycle $cycle): Cycle
    {
        $cycle->push();

        return $cycle;
    }
}
