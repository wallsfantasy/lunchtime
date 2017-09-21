<?php

namespace App\Model\Cycle;

use App\Model\Cycle\Event\CycleCreatedEvent;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class CycleFactory
{
    /**
     * @param int                $userId
     * @param string             $name
     * @param \DateInterval      $lunchtime
     * @param \DateInterval|null $proposeUntil
     *
     * @return Cycle
     */
    public function createCycle(
        int $userId,
        string $name,
        \DateInterval $lunchtime,
        ?\DateInterval $proposeUntil
    ): Cycle {
        // make default propose until if need
        if ($proposeUntil === null) {
            $todayProposeUntil = Carbon::today()->add($lunchtime)->sub(new \DateInterval(Cycle::DEFAULT_PROPOSE_BEFORE_LUNCHTIME));
            $proposeUntil = Carbon::today()->diff($todayProposeUntil);
        }

        $this->guardProposeUntilAndLunchtime($proposeUntil, $lunchtime);

        $cycleId = Uuid::uuid4()->toString();
        $cycle = new Cycle([
            'id' => $cycleId,
            'name' => $name,
            'lunchtime' => $lunchtime->format('%H:%I:%S'),
            'propose_until' => $proposeUntil->format('%H:%I:%S'),
            'creator_user_id' => $userId,
        ]);

        $member = new Member([
            'cycle_id' => $cycleId,
            'user_id' => $userId,
        ]);
        $cycle->members->add($member);

        // generate cycleCreatedEvent
        $cycle->recordEvent(
            new CycleCreatedEvent($cycleId, $userId, $name, $lunchtime->format('%H:%I:%S'),
                $proposeUntil->format('%H:%I:%S'))
        );

        return $cycle;
    }

    /**
     * @param \DateInterval $proposeUntil
     * @param \DateInterval $lunchtime
     *
     * @throws CycleException
     */
    private function guardProposeUntilAndLunchtime(\DateInterval $proposeUntil, \DateInterval $lunchtime)
    {
        // check times make sense
        $todayLunchtime = Carbon::today()->add($lunchtime);
        $todayProposeUntil = Carbon::today()->add($proposeUntil);
        if ($todayLunchtime < $todayProposeUntil) {
            $lunchtimeFormatted = $lunchtime->format('%H:%I:%S');
            $proposeUntilFormatted = $proposeUntil->format('%H:%I:%S');
            throw CycleException::createLunchtimeBeforePropose(null, [
                'propose_until' => $proposeUntilFormatted,
                'lunchtime' => $lunchtimeFormatted,
            ]);
        }
    }
}
