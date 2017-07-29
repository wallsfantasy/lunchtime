<?php

namespace App\Model\Cycle\Domain;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\Domain\Exception\CreateCycleException;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

class CreateCycle
{
    /** @var AuthManager $authManager */
    private $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * @param string             $name
     * @param \DateInterval      $lunchtime
     * @param \DateInterval|null $proposeUntil
     *
     * @return Model|Cycle
     * @throws CreateCycleException
     */
    public function createCycle(string $name, \DateInterval $lunchtime, \DateInterval $proposeUntil = null)
    {
        $userId = $this->authManager->guard()->id();

        // initialize `propose until` and check validity
        $today = new \DateTimeImmutable('today');
        $todayLunchtime = $today->add($lunchtime);
        if ($proposeUntil === null) {
            $todayProposeUntil = $todayLunchtime->sub(new \DateInterval(Cycle::DEFAULT_PROPOSE_BEFORE_LUNCHTIME));
            $proposeUntil = $today->diff($todayProposeUntil);
        } else {
            $todayProposeUntil = $today->add($proposeUntil);
        }

        $lunchtimeFormatted = $lunchtime->format('%H:%I:%S');
        $proposeUntilFormatted = $proposeUntil->format('%H:%I:%S');
        if ($todayLunchtime < $todayProposeUntil) {
            throw new CreateCycleException(
                "Lunchtime ({$lunchtimeFormatted}) arrived before last propose time ({$proposeUntilFormatted})",
                CreateCycleException::CODES['LUNCHTIME_AFTER_PROPOSE_UNTIL'], null, $proposeUntil, $lunchtime
            );
        }

        $cycle = Cycle::create(
            [
                'name' => $name,
                'propose_until' => $proposeUntilFormatted,
                'lunchtime' => $lunchtimeFormatted,
                'user_id' => $userId,
            ]
        );

        return $cycle;
    }
}
