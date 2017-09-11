<?php

namespace App\Model\Cycle;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CycleRepository
{
    const DEFAULT_PAGE_SIZE = 20;

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
     * @param string|null $name
     * @param int|null    $page
     * @param string      $order
     * @param int         $size
     *
     * @return iterable|LengthAwarePaginator
     * @throws \InvalidArgumentException
     */
    public function pageByCycleName(
        ?string $name,
        int $page = 1,
        string $order = 'asc',
        int $size = self::DEFAULT_PAGE_SIZE
    ): iterable {
        // todo: create pagination result VO in common
        if ($name === null) {
            $paginated = $this->cycle::with('members')
                ->orderBy('name', $order)
                ->paginate($size, $columns = ['*'], $pageName = 'page', $page);
            return $paginated;
        }

        $paginated = $this->cycle::with('members')
            ->where('name', 'ilike', "%{$name}%")
            ->orderBy('name', $order)
            ->paginate($size, $columns = ['*'], $pageName = 'page', $page);

        return $paginated;
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
     * @param Cycle $cycle
     */
    public function add(Cycle $cycle)
    {
        $cycle->push();
    }

    /**
     * @param Cycle $cycle
     */
    public function save(Cycle $cycle)
    {
        $cycle->push();
    }

    /**
     * @param Cycle $cycle
     * @param int   $userId
     */
    public function deleteMemberByUserId(Cycle $cycle, int $userId)
    {
        $member = $this->member->where('cycle_id', '=', $cycle->id)
            ->where('user_id', '=', $userId)
            ->first();

        $member->delete();
    }
}
