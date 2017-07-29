<?php

namespace App\Model\Cycle\Repository;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\Member;

class CycleRepository
{
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
     * @return Cycle
     * @throws \Exception
     */
    public function add(Cycle $cycle, iterable $members = []): Cycle
    {
        if ($cycle->save() === false) {
            throw new \Exception('Fail to add Cycle to repository');
        }

        if ($members !== []) {
            $cycle->members()->saveMany($members);
        }

        $result = $this->find($cycle->id);

        return $result;
    }
}
