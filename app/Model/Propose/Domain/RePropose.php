<?php

namespace App\Model\Propose\Domain;

use App\Model\Propose\Propose;
use App\Model\Propose\Repository\ProposeRepository;
use App\Model\Restaurant\Repository\RestaurantRepository;
use Carbon\Carbon;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

class RePropose
{
    /** @var AuthManager */
    private $authManager;

    /** @var ProposeRepository */
    private $proposeRepo;

    /** @var RestaurantRepository */
    private $restaurantRepo;

    public function __construct(
        AuthManager $authManager,
        ProposeRepository $proposeRepo,
        RestaurantRepository $restaurantRepo
    ) {
        $this->authManager = $authManager;
        $this->proposeRepo = $proposeRepo;
        $this->restaurantRepo = $restaurantRepo;
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

        $restaurant = $this->restaurantRepo->get($restaurantId);

        // throw if haven't proposed for given date
        $latestProposed = $this->proposeRepo->findLatestByUserIdForDate($userId, $forDate);
        if ($latestProposed === null) {
            throw new ProposeException("Never proposed for date {$forDate->format('Y-m-d')}",
                ProposeException::CODES_RE_PROPOSE['have_not_proposed'],
                null,
                ['for_date' => $forDate, 'restaurant' => $restaurant->toArray()]
            );
        }

        // throw if re-proposed the same as latest proposed
        if ($latestProposed !== null
            && $latestProposed->restaurant_id === $restaurantId
            && $latestProposed->for_date->toDateString() === $forDate->toDateString()
        ) {
            throw new ProposeException("Repropose the same restaurant and date as latest proposed",
                ProposeException::CODES_RE_PROPOSE['repropose_latest_proposed'],
                null,
                ['for_date' => $forDate, 'restaurant' => $restaurant->toArray()]
            );
        }

        $propose = new Propose([
            'user_id' => $userId,
            'restaurant_id' => $restaurantId,
            'for_date' => $forDate,
        ]);

        $propose = $this->proposeRepo->add($propose);

        return $propose;
    }
}
