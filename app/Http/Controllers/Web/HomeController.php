<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\Cycle\Cycle;
use App\Model\Cycle\Repository\CycleRepository;
use App\Model\Propose\Propose;
use App\Model\Propose\Repository\ProposeRepository;
use App\Model\Restaurant\Repository\RestaurantRepository;
use App\Model\Restaurant\Restaurant;
use App\Model\User\Repository\UserRepository;
use App\Model\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /** @var CycleRepository $cycleRepo */
    private $cycleRepo;

    /** @var UserRepository $userRepo */
    private $userRepo;

    /** @var ProposeRepository $proposeRepo */
    private $proposeRepo;

    /** @var RestaurantRepository $restaurantRepo */
    private $restaurantRepo;

    public function __construct(
        CycleRepository $cycleRepo,
        UserRepository $userRepo,
        ProposeRepository $proposeRepo,
        RestaurantRepository $restaurantRepo
    ) {
        $this->cycleRepo = $cycleRepo;
        $this->userRepo = $userRepo;
        $this->proposeRepo = $proposeRepo;
        $this->restaurantRepo = $restaurantRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // initialize data
        // todo: move this to query function that returns view object
        $users = [];
        $proposes = [];
        /** @var Collection|Cycle[] $cycles */
        $cycles = $this->cycleRepo->findByMemberUserId($userId);
        $cycles = $cycles->sortBy('lunchtime');
        $proposesByCycle = [];

        // return everything empty if no Cycle
        if (count($cycles) === 0) {
            return view('home', compact('users', 'proposes', 'cycles', 'proposesByCycle'));
        }

        // find unique Users in all of my Cycles
        $userIds = [];
        foreach ($cycles as $cycle) {
            foreach ($cycle->members as $member) {
                $userIds[] = $member->user_id;
            }
        }
        $userIds = array_unique($userIds, SORT_NUMERIC);
        $users = $this->userRepo->findByIds($userIds);

        // find proposes of Users in my Cycles
        $today = Carbon::today();
        $proposes = $this->proposeRepo->findByUserIdsForDate($userIds, $today);

        // find unique proposed Restaurants
        $restaurantIds = [];
        foreach ($proposes as $propose) {
            $restaurantIds[] = $propose->restaurant_id;
        }
        $restaurantIds = array_unique($restaurantIds, SORT_NUMERIC);
        $restaurants = $this->restaurantRepo->findByIds($restaurantIds);

        // View Data: $userProposesByCycle
        foreach ($cycles as $cycle) {
            $proposesByCycle[] = [
                'cycle' => $cycle,
                'proposes' => $this->getCycleEffectiveProposes($cycle, $proposes, $users, $restaurants),
            ];
        }

        return view('home', compact('users', 'proposes', 'cycles', 'proposesByCycle'));
    }

    /**
     * @param Cycle                            $cycle
     * @param iterable|Collection|Propose[]    $proposes
     * @param iterable|Collection|User[]       $users
     * @param iterable|Collection|Restaurant[] $restaurants
     *
     * @return array
     */
    private function getCycleEffectiveProposes(
        Cycle $cycle,
        iterable $proposes,
        iterable $users,
        iterable $restaurants
    ): array {
        $members = $cycle->members;
        $memberUserIds = array_column($members->toArray(), 'user_id');

        $effectiveProposes = [];
        foreach ($proposes as $propose) {
            // skip proposes those are not belong to member users
            if (!in_array($propose->user_id, $memberUserIds, true)) {
                continue;
            }

            // skip late propose
            if ($cycle->propose_until < $propose->proposed_at->format('H:i:s')) {
                continue;
            }

            // if user never have a propose record then add it
            $proposeUserId = $propose->user_id;
            if (!isset($effectiveProposes[$proposeUserId])) {
                $propose->user = $users->where('id', '=', $propose->user_id)->first();
                $propose->restaurant = $restaurants->where('id', '=', $propose->restaurant_id)->first();
                $effectiveProposes[$proposeUserId] = $propose;
                continue;
            }

            // replace propose if later than existing record
            if ($effectiveProposes[$proposeUserId]->proposed_at < $propose->proposed_at) {
                $propose->user = $users->where('id', '=', $propose->user_id)->first();
                $propose->restaurant = $restaurants->where('id', '=', $propose->restaurant_id)->first();
                $effectiveProposes[$proposeUserId] = $propose;
            }
        }

        return $effectiveProposes;
    }
}
