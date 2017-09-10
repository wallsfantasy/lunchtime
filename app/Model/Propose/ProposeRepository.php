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
     * @param int       $userId
     * @param \DateTime $date
     *
     * @return Propose|null
     */
    public function findLatestByUserIdForDate(int $userId, \DateTime $date): ?Propose
    {
        $propose = $this->propose->where('user_id', '=', $userId)
            ->where('for_date', '=', $date->format('Y-m-d'))
            ->orderBy('proposed_at', 'desc')
            ->first();

        return $propose;
    }

    /**
     * @param int       $userId
     * @param \DateTime $forDate
     * @param \DateTime $after
     *
     * @return iterable|Collection|Propose[]
     */
    public function findAllByUserIdForDateAfter(int $userId, \DateTime $forDate, \DateTime $after): iterable
    {
        $proposes = $this->propose->where('user_id', '=', $userId)
            ->where('for_date', '=', $forDate->format('Y-m-d'))
            ->where('proposed_at', '>=', $after->format('Y-m-d H:i:s'))
            ->orderBy('proposed_at', 'desc')
            ->get();

        return $proposes;
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
     * This function is kept for reference only
     *
     * @param array          $userIds
     * @param \DateTime|null $date
     *
     * @return iterable
     */
    public function findAllLatestByUserIdsForDate(array $userIds, \DateTime $date = null): iterable
    {
        $date = $date ?? Carbon::today();

        $proposes = Propose::from(DB::raw('proposes AS p'))
            ->join(DB::raw('(SELECT user_id, MAX(proposed_at) AS max_proposed_at FROM proposes GROUP BY user_id) AS m'),
                function ($query) {
                    $query->on('p.user_id', '=', 'm.user_id')
                        ->on('p.proposed_at', '=', 'm.max_proposed_at');
                })
            ->whereIn('p.user_id', $userIds)
            ->where('p.for_date', '=', $date)
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
