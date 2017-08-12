<?php

namespace App\Model\Propose\Domain;

use App\Model\Propose\Propose;
use App\Model\Restaurant\Restaurant;
use Carbon\Carbon;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

class MakePropose
{
    /** @var AuthManager */
    private $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * Make propose for a restaurant
     *
     * @param int            $restaurantId
     * @param \DateTime|null $forDate
     *
     * @return Model|Propose
     * @throws ProposeException
     */
    public function makePropose(int $restaurantId, \DateTime $forDate = null)
    {
        $userId = $this->authManager->guard()->id();
        $forDate = $forDate ?? Carbon::today();

        // throw if already proposed
        $proposed = Propose::where(['user_id' => $userId, 'for_date' => $forDate->format('Y-m-d')])->first();
        if ($proposed !== null) {
            throw new ProposeException("Already proposed for forDate {$forDate->format('Y-m-d')}",
                ProposeException::CODES_MAKE_PROPOSE['already_proposed'],
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
