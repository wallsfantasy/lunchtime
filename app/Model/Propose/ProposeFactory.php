<?php

namespace App\Model\Propose;

use Carbon\Carbon;

class ProposeFactory
{
    /** @var ProposeRepository $proposeRepo */
    private $proposeRepo;

    public function __construct(ProposeRepository $proposeRepo)
    {
        $this->proposeRepo = $proposeRepo;
    }

    /**
     * @param int       $userId
     * @param int       $restaurantId
     * @param \DateTime $forDate
     *
     * @throws ProposeException
     */
    public function makePropose(int $userId, int $restaurantId, \DateTime $forDate)
    {
        // proposes made by userId for forDate within 24 hours
        $yesterdayNow = Carbon::now()->subDay();
        $proposed = $this->proposeRepo->findAllByUserIdForDateAfter($userId, $forDate, $yesterdayNow);

        if (count($proposed) > Propose::DAY_PROPOSES_LIMIT) {
            $laggingPropose = $proposed->last();
            throw new ProposeException(
                "Your propose limit within 24 hours reached",
                ProposeException::CODES_MAKE_PROPOSE['propose_limit_reach'],
                null,
                ['lagging_propose_time' => $laggingPropose->toArray()]
            );
        }

        $latestProposed = $proposed->first();
        if ($latestProposed !== null && $latestProposed->restaurant_id === $restaurantId) {
            throw new ProposeException(
                "You're proposing same restaurant with the latest one you proposed for {$forDate->format('Y-m-d')}",
                ProposeException::CODES_MAKE_PROPOSE['duplicate_last_propose'],
                null,
                ['for_date' => $forDate, 'restaurant_id' => $restaurantId]
            );
        }

        $propose = new Propose([
            'user_id' => $userId,
            'restaurant_id' => $restaurantId,
            'for_date' => $forDate,
        ]);

        return $propose;
    }
}
