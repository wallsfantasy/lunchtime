<?php

namespace App\Model\Cycle\Domain;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\Domain\Exception\CreateCycleException;
use App\Model\Cycle\Member;
use App\Model\Cycle\Repository\CycleRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

class CreateCycle
{
    /** @var AuthManager $authManager */
    private $authManager;

    /** @var CycleRepository */
    private $cycleRepo;

    public function __construct(AuthManager $authManager, CycleRepository $cycleRepository)
    {
        $this->authManager = $authManager;
        $this->cycleRepo = $cycleRepository;
    }

    /**
     * @param string             $name
     * @param \DateInterval      $lunchtime
     * @param \DateInterval|null $proposeUntil
     *
     * @return Model|Cycle
     * @throws CreateCycleException
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

        // factory
        $cycle = new Cycle(
            [
                'name' => $name,
                'lunchtime' => $lunchtimeFormatted,
                'propose_until' => $proposeUntilFormatted,
                'creator_user_id' => $userId,
            ]
        );
        $members = [
            new Member(['user_id' => $userId]),
        ];

        // save
        try {
            $cycle = $this->cycleRepo->add($cycle, $members);
        } catch (\Throwable $e) {
            $this->throwRepositoryException($e, $cycle);
        }

        return $cycle;
    }

    /**
     * @param string $lunchtimeFormatted
     * @param string $proposeUntilFormatted
     *
     * @throws CreateCycleException
     */
    private function throwLunchtimeBeforeProposeTime(string $lunchtimeFormatted, string $proposeUntilFormatted): void
    {
        throw new CreateCycleException(
            "Lunchtime ({$lunchtimeFormatted}) arrived before last propose time ({$proposeUntilFormatted})",
            CreateCycleException::CODES['LUNCHTIME_BEFORE_PROPOSE_TIME'],
            null,
            [
                'propose_until' => $proposeUntilFormatted,
                'lunchtime' => $lunchtimeFormatted,
            ]
        );
    }

    /**
     * @param \Throwable $previous
     * @param Cycle      $cycle
     *
     * @throws CreateCycleException
     */
    private function throwRepositoryException(\Throwable $previous, Cycle $cycle): void
    {
        throw new CreateCycleException(
            'Fail to save Cycle',
            CreateCycleException::CODES['REPOSITORY_FAILURE'],
            $previous,
            $cycle->toArray()
        );
    }
}
