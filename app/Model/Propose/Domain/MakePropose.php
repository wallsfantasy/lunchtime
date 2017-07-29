<?php

namespace App\Model\Propose\Domain;

use App\Model\Propose\Domain\Exception\MakeProposeException;
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
     * @throws MakeProposeException
     */
    public function makePropose(int $restaurantId, \DateTime $date = null)
    {
        $userId = $this->authManager->guard()->id();
        $date = $date ?? new \DateTime('today');
        $date->setTime(0, 0);

        // throw if already proposed
        $proposed = Propose::where(['user_id' => $userId, 'for_date' => $date->format('Y-m-d')])->first();
        if ($proposed !== null) {
            throw new MakeProposeException("Already proposed for date {$date->format('Y-m-d')}",
                MakeProposeException::CODES['ALREADY_PROPOSED'], null, $date, $restaurantId
            );
        }

        // throw if restaurant not exists
        try {
            Restaurant::where(['id' => $restaurantId])->firstOrFail();
        } catch (\Exception $e) {
            throw new MakeProposeException("Restaurant not found",
                MakeProposeException::CODES['RESTAURANT_NOT_FOUND'], $e, $date, $restaurantId
            );
        }

        $propose = Propose::create(['user_id' => $userId, 'restaurant_id' => $restaurantId, 'for_date' => $date]);

        return $propose;
    }
}
