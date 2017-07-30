<?php

namespace App\Model\Propose\Domain;

use App\Model\Propose\Propose;
use App\Model\Restaurant\Restaurant;
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
     * @param \DateTime|null $date
     *
     * @return Model|Propose
     * @throws ProposeException
     */
    public function makePropose(int $restaurantId, \DateTime $date = null)
    {
        json_encode(new \DateTime());
        $userId = $this->authManager->guard()->id();
        $date = $date ?? new \DateTime('today');
        $date->setTime(0, 0);

        // throw if already proposed
        $proposed = Propose::where(['user_id' => $userId, 'for_date' => $date->format('Y-m-d')])->first();
        if ($proposed !== null) {
            throw new ProposeException("Already proposed for date {$date->format('Y-m-d')}",
                ProposeException::CODES_MAKE_PROPOSE['already_proposed'],
                null,
                [ 'date' => $date, 'restaurant_id' => $restaurantId]
            );
        }

        // throw if restaurant not exists
        try {
            Restaurant::where(['id' => $restaurantId])->firstOrFail();
        } catch (\Throwable $e) {
            throw new ProposeException("Restaurant not found",
                ProposeException::CODES_MAKE_PROPOSE['restaurant_not_found'],
                $e,
                [ 'date' => $date, 'restaurant_id' => $restaurantId ]
            );
        }

        $propose = Propose::create(['user_id' => $userId, 'restaurant_id' => $restaurantId, 'for_date' => $date]);

        return $propose;
    }
}
