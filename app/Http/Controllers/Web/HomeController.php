<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\Cycle\Repository\CycleRepository;
use App\Model\Propose\Repository\ProposeRepository;
use App\Model\Restaurant\Repository\RestaurantRepository;
use App\Model\User\Repository\UserRepository;
use Carbon\Carbon;
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
        $users = [];
        $proposes = [];
        $cycles = $this->cycleRepo->findByMemberUserId($userId);
        $userProposesByCycle = [];

        // return everything empty if no Cycle
        if (count($cycles) === 0) {
            return view('home', compact('users', 'proposes', 'cycles', 'userProposesByCycle'));
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

        // view data for $userProposesByCycle
        foreach ($cycles as $cycle) {
            foreach ($cycle->members as $member) {
                $user = $users->where('id', $member->user_id)->first();
                $propose = $proposes->where('user_id', $member->user_id)->first();
                $restaurant = ($propose === null) ? null : $restaurants->where('id', $propose->restaurant_id)->first();
                $userProposesByCycle[$cycle->name][$user->name] = $restaurant->name ?? null;
            }
        }

        return view('home', compact('users', 'proposes', 'cycles', 'userProposesByCycle'));
    }
}
