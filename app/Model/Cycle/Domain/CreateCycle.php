<?php

namespace App\Model\Cycle\Domain;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\Member;
use App\Model\Cycle\Repository\CycleRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

class CreateCycle
{
    /** @var AuthManager $authManager */
    private $authManager;

    /** @var CycleRepository $cycleRepo */
    private $cycleRepo;

    public function __construct(AuthManager $authManager, CycleRepository $cycleRepo)
    {
        $this->authManager = $authManager;
        $this->cycleRepo = $cycleRepo;
    }

    /**
     * @param string             $name
     * @param \DateInterval      $lunchtime
     * @param \DateInterval|null $proposeUntil
     *
     * @return Model|Cycle
     * @throws CycleException
     */
    public function createCycle(string $name, \DateInterval $lunchtime, \DateInterval $proposeUntil = null): Cycle
    {
        $userId = $this->authManager->guard()->id();

        // initialize lunchtime and propose_until
        $today = new \DateTimeImmutable('today');
        $todayLunchtime = $today->add($lunchtime);
        if ($proposeUntil === null) {
            $todayProposeUntil = $todayLunchtime->sub(new \DateInterval(Cycle::DEFAULT_PROPOSE_BEFORE_LUNCHTIME));
            $proposeUntil = $today->diff($todayProposeUntil);
        } else {
            $todayProposeUntil = $today->add($proposeUntil);
        }

        // check times make sense
        $lunchtimeFormatted = $lunchtime->format('%H:%I:%S');
        $proposeUntilFormatted = $proposeUntil->format('%H:%I:%S');
        if ($todayLunchtime < $todayProposeUntil) {
            $this->throwLunchtimeBeforeProposeTime($lunchtimeFormatted, $proposeUntilFormatted);
        }

        // factory and save cycle
        /** @var Cycle $cycle */
        $cycle = new Cycle(
            [
                'name' => $name,
                'lunchtime' => $lunchtimeFormatted,
                'propose_until' => $proposeUntilFormatted,
                'creator_user_id' => $userId,
            ]
        );

        $members = [new Member(['user_id' => $userId])];

        try {
            $id = $this->cycleRepo->add($cycle, $members);
        } catch (\Throwable $e) {
            throw new CycleException('Fail to add Cycle to repository', 0, $e, [$cycle, $members]);
        }

        $cycle = $this->cycleRepo->find($id);

        return $cycle;
    }

    /**
     * @param string $lunchtimeFormatted
     * @param string $proposeUntilFormatted
     *
     * @throws CycleException
     */
    private function throwLunchtimeBeforeProposeTime(string $lunchtimeFormatted, string $proposeUntilFormatted): void
    {
        throw new CycleException(
            "Lunchtime ({$lunchtimeFormatted}) arrived before last propose time ({$proposeUntilFormatted})",
            CycleException::CODES_CREATE_CYCLE['lunchtime_before_propose'],
            null,
            [
                'propose_until' => $proposeUntilFormatted,
                'lunchtime' => $lunchtimeFormatted,
            ]
        );
    }
}
