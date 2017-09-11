<?php

namespace App\Model\Propose;

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
     * @return Propose
     * @throws ProposeException
     */
    public function makePropose(int $userId, int $restaurantId, \DateTime $forDate)
    {
        // limit proposes made by user for a date
        $proposed = $this->proposeRepo->findAllByUserIdsForDate([$userId], $forDate);

        if (count($proposed) >= Propose::DAY_PROPOSES_LIMIT) {
            throw new ProposeException(
                "Your propose limit for a day reached",
                ProposeException::CODES_MAKE_PROPOSE['propose_limit_reach'],
                null,
                ['proposed' => $proposed->toArray()]
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
