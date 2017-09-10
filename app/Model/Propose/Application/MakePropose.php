<?php

namespace App\Model\Propose\Application;

use App\Model\Propose\Propose;
use App\Model\Propose\ProposeFactory;
use App\Model\Propose\ProposeRepository;
use Carbon\Carbon;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

class MakePropose
{
    /** @var AuthManager */
    private $authManager;

    /** @var ProposeRepository */
    private $proposeRepo;

    /** @var ProposeFactory */
    private $proposeFactory;

    public function __construct(
        AuthManager $authManager,
        ProposeFactory $proposeFactory,
        ProposeRepository $proposeRepository
    ) {
        $this->authManager = $authManager;
        $this->proposeFactory = $proposeFactory;
        $this->proposeRepo = $proposeRepository;
    }

    /**
     * Make propose for a restaurant
     *
     * @param int            $restaurantId
     * @param \DateTime|null $forDate
     *
     * @return Model|Propose
     */
    public function makePropose(int $restaurantId, ?\DateTime $forDate)
    {
        $userId = $this->authManager->guard()->id();

        $forDate = $forDate ?? Carbon::today();

        $propose = $this->proposeFactory->makePropose($userId, $restaurantId, $forDate);

        $this->proposeRepo->add($propose);

        return $propose;
    }
}
