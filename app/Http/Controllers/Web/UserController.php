<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageUserRequest;
use App\Model\Propose\Repository\ProposeRepository;
use App\Model\Restaurant\Repository\RestaurantRepository;
use App\Model\User\Repository\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    const PAGE_SIZE  = 20;
    const PAGE_ORDER = 'asc';

    /** @var UserRepository $userRepo */
    private $userRepo;

    /** @var ProposeRepository $proposeRepo */
    private $proposeRepo;

    /** @var RestaurantRepository $restaurantRepo */
    private $restaurantRepo;

    public function __construct(
        UserRepository $userRepo,
        ProposeRepository $proposeRepo,
        RestaurantRepository $restaurantRepo
    ) {
        $this->userRepo = $userRepo;
        $this->proposeRepo = $proposeRepo;
        $this->restaurantRepo = $restaurantRepo;
    }

    /**
     * Show Restaurant page
     *
     * @return View
     */
    public function index(PageUserRequest $request)
    {
        $page = $request->query->get('page');
        $name = $request->query->get('name');

        if ($name === null && $page === null) {
            $users = $this->queryForUserPage();
        } else {
            $users = $this->queryForUserPage($name, $page);
        }

        return view('user', compact('users'));
    }

    /**
     * Post method to make search
     *
     * @param PageUserRequest $request
     *
     * @return RedirectResponse
     */
    public function postSearch(PageUserRequest $request): RedirectResponse
    {
        $name = $request->request->get('name');

        if ($name === null) {
            return redirect()->action('Web\UserController@index');
        }

        return redirect()->action('Web\UserController@index', ['name' => $name]);
    }

    /**
     * Prepare data to display restaurant view
     *
     * @param string|null $name
     * @param int         $page
     *
     * @return array
     */
    private function queryForUserPage(string $name = null, int $page = null)
    {
        $users = $this->userRepo->pageByUserName($name, $page ?? 1, self::PAGE_ORDER, self::PAGE_SIZE);

        // find today Proposes of the users
        $today = Carbon::today();
        $userIds = array_column((array) $users->items(), 'id');
        $proposes = $this->proposeRepo->findByUserIdsForDate($userIds, $today);

        // find proposed restaurants
        $proposedRestaurantIds = array_unique(array_column((array) $proposes->all(), 'restaurant_id'));
        $restaurants = $this->restaurantRepo->findByIds($proposedRestaurantIds);

        // function to join user's proposes/restaurants data
        $joinProposesRestaurant = function (iterable $userProposes) use ($restaurants): iterable {
            foreach ($userProposes as $userPropose) {
                $userProposeRestaurant = null;
                if ($userPropose !== null) {
                    $userProposeRestaurant = $restaurants->where('id', '=', $userPropose->restaurant_id)->first();
                }
                $userPropose['restaurant'] = $userProposeRestaurant;
            }

            return $userProposes;
        };

        // factory view data
        $userPageData = [];
        foreach ($users as $user) {
            $item = [];
            $item['user'] = $user;

            $userProposes = $proposes->where('user_id', '=', $user->id)->sortBy('proposed_at');
            $item['proposes'] = $joinProposesRestaurant($userProposes);

            $userPageData[] = $item;
        }

        return $userPageData;
    }
}
