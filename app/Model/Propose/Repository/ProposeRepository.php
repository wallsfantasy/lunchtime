<?php

namespace App\Model\Propose\Repository;

use App\Model\Propose\Propose;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ProposeRepository
{
    /** @var Propose */
    private $propose;

    public function __construct(Propose $propose)
    {
        $this->propose = $propose;
    }

    /**
     * @param int[]          $userIds
     * @param \DateTime|null $date
     *
     * @return iterable|Collection|Propose[]
     */
    public function findByUserIdsForDate(array $userIds, \DateTime $date = null): iterable
    {
        $date = $date ?? Carbon::today();
        $proposes = $this->propose->whereIn('user_id', $userIds)
            ->where('for_date', '=', $date->format('Y-m-d'))
            ->get();

        return $proposes;
    }

    /**
     * @param Propose $propose
     *
     * @return int
     */
    public function add(Propose $propose)
    {
        $propose->save();

        return $propose->id;
    }

}
