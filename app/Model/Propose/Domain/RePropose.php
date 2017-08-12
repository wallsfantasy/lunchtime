<?php

namespace App\Model\Propose\Domain;

use App\Model\Propose\Propose;
use App\Model\Restaurant\Restaurant;
use Carbon\Carbon;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

class RePropose
{
    /** @var AuthManager */
    private $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * (Re)Make propose again for a restaurant
     *
     * @param int            $restaurantId
     * @param \DateTime|null $forDate
     *
     * @return Model|Propose
     * @throws ProposeException
     */
    public function rePropose(int $restaurantId, \DateTime $forDate = null)
    {
        $userId = $this->authManager->guard()->id();
        $forDate = $forDate ?? Carbon::today();

        // throw if haven't proposed
        $latestProposed = Propose::where(['user_id' => $userId, 'for_date' => $forDate->format('Y-m-d')])->first();
        if ($latestProposed === null) {
            throw new ProposeException("Never proposed for date {$forDate->format('Y-m-d')}",
                ProposeException::CODES_RE_PROPOSE['have_not_proposed'],
                null,
                ['for_date' => $forDate, 'restaurant_id' => $restaurantId]
            );
        }

        // throw if re-proposed the same as latest proposed
        $latestProposedCompare = [$latestProposed->restaurant_id, $latestProposed->for_date->toDateString()];
        if ($latestProposedCompare === [$restaurantId, $forDate->toDateString()]) {
            throw new ProposeException("Repropose the same restaurant and date as latest proposed",
                ProposeException::CODES_RE_PROPOSE['repropose_latest_proposed'],
                null,
                ['for_date' => $forDate, 'restaurant_id' => $restaurantId]
            );
        }

        // throw if restaurant not exists
        try {
            Restaurant::where(['id' => $restaurantId])->firstOrFail();
        } catch (\Throwable $e) {
            throw new ProposeException("Restaurant not found",
                ProposeException::CODES_MAKE_PROPOSE['restaurant_not_found'],
                $e,
                ['for_date' => $forDate, 'restaurant_id' => $restaurantId]
            );
        }

        $propose = Propose::create(['user_id' => $userId, 'restaurant_id' => $restaurantId, 'for_date' => $forDate]);

        return $propose;
    }
}
