<?php

namespace App\Model\Propose\Domain;

use App\Model\Propose\Propose;
use App\Model\Propose\Repository\ProposeRepository;
use App\Model\Restaurant\Repository\RestaurantRepository;
use Carbon\Carbon;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

class MakePropose
{
    /** @var AuthManager */
    private $authManager;

    /** @var ProposeRepository */
    private $proposeRepo;

    /** @var RestaurantRepository */
    private $restaurantRepo;

    public function __construct(
        AuthManager $authManager,
        ProposeRepository $proposeRepository,
        RestaurantRepository $restaurantRepo
    ) {
        $this->authManager = $authManager;
        $this->proposeRepo = $proposeRepository;
        $this->restaurantRepo = $restaurantRepo;
    }

    /**
     * Make propose for a restaurant
     *
     * @param int $restaurantId
     * @param \DateTime|null $forDate
     *
     * @return Model|Propose
     * @throws ProposeException
     */
    public function makePropose(int $restaurantId, \DateTime $forDate = null)
    {
        $userId = $this->authManager->guard()->id();
        $forDate = $forDate ?? Carbon::today();

        $restaurant = $this->restaurantRepo->get($restaurantId);

        // throw if currently proposed the same restaurant
        $proposed = $this->proposeRepo->findLatestByUserIdForDate($userId, $forDate);
        if ($proposed !== null && $proposed->restaurant_id === $restaurantId) {
            throw new ProposeException(
                "You're currently proposing {$restaurant->name} on {$forDate->format('Y-m-d')}",
                ProposeException::CODES_MAKE_PROPOSE['currently_proposed'],
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
