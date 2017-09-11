<?php

namespace App\Model\Propose;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProposeRepository
{
    /** @var Propose */
    private $propose;

    public function __construct(Propose $propose)
    {
        $this->propose = $propose;
    }

    /**
     * @param int[]     $userIds
     * @param \DateTime $date
     *
     * @return iterable|Collection|Propose[]
     */
    public function findAllByUserIdsForDate(array $userIds, \DateTime $date): iterable
    {
        $proposes = $this->propose->whereIn('user_id', $userIds)
            ->where('for_date', '=', $date->format('Y-m-d'))
            ->get();

        return $proposes;
    }

    /**
     * @param Propose $propose
     *
     * @return Propose
     */
    public function add(Propose $propose): Propose
    {
        $propose->saveOrFail();

        return $propose;
    }
}
