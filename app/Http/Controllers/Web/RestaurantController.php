<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRestaurantRequest;
use App\Model\Restaurant\Repository\RestaurantRepository;
use App\Model\User\Repository\UserRepository;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    /** @var RestaurantRepository $restaurantRepo */
    private $restaurantRepo;

    /** @var UserRepository $userRepo */
    private $userRepo;

    public function __construct(RestaurantRepository $restaurantRepo, UserRepository $userRepo)
    {
        $this->restaurantRepo = $restaurantRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Show Restaurant page
     *
     * @return View
     */
    public function index(PageRestaurantRequest $request)
    {
        $name = $request->query->get('name') ?? null;
        $page = $request->query->get('page') ?? 1;
        $size = $request->query->get('size') ?? RestaurantRepository::DEFAULT_PAGE_SIZE;

        // initialize data
        // todo: sanitize page, size
        $restaurants = $this->restaurantRepo->pageByRestaurantName($name, $page, 'asc', $size);

        // find Users who made registration
        $userIds = [];
        foreach ($restaurants->items() as $restaurant) {
            $userIds[$restaurant->id] = $restaurant->id;
        }
        $registrants = $this->userRepo->findByIds($userIds);

        // view data for $userProposesByCycle
        foreach ($restaurants->items() as &$restaurant) {
            $user = $registrants->where('id', $restaurant->registrator_user_id)->first();
            $restaurant->registrator_user = $user;
        }

        return view('restaurant', compact('restaurants', 'registrants'));
    }
}
