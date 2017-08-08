<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRestaurantRequest;
use App\Http\Requests\RegisterRestaurantRequest;
use App\Model\Restaurant\Domain\RegisterRestaurant;
use App\Model\Restaurant\Repository\RestaurantRepository;
use App\Model\User\Repository\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    const PAGE_SIZE  = 20;
    const PAGE_ORDER = 'asc';

    /** @var RestaurantRepository $restaurantRepo */
    private $restaurantRepo;

    /** @var UserRepository $userRepo */
    private $userRepo;

    /** @var RegisterRestaurant $registerRestaurant */
    private $registerRestaurant;

    public function __construct(
        RestaurantRepository $restaurantRepo,
        UserRepository $userRepo,
        RegisterRestaurant $registerRestaurant
    ) {
        $this->restaurantRepo = $restaurantRepo;
        $this->userRepo = $userRepo;
        $this->registerRestaurant = $registerRestaurant;
    }

    /**
     * Show Restaurant page
     *
     * @return View
     */
    public function index()
    {
        [$restaurants, $registrants] = $this->queryRestaurantView(null, 1, self::PAGE_SIZE);

        return view('restaurant', compact('restaurants', 'registrants'));
    }

    /**
     * Show search result of the restaurants
     *
     * @param PageRestaurantRequest $request
     *
     * @return View
     */
    public function search(PageRestaurantRequest $request): View
    {
        $name = $request->request->get('name') ?? null;
        $page = $request->request->get('page') ?? 1;
        $size = $request->request->get('size') ?? self::PAGE_SIZE;

        // todo: sanitize page, size
        [$restaurants, $registrants] = $this->queryRestaurantView($name, $page, $size);

        return view('restaurant', compact('restaurants', 'registrants'));
    }

    /**
     * Prepare data to display restaurant view
     *
     * @param string|null $name
     * @param int         $page
     * @param int         $size
     *
     * @return array
     */
    private function queryRestaurantView(string $name = null, int $page, int $size): array
    {
        $restaurants = $this->restaurantRepo->pageByRestaurantName($name, $page, self::PAGE_ORDER, $size);

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

        return [$restaurants, $registrants];
    }

    /**
     * POST to register restaurant
     *
     * @param RegisterRestaurantRequest $request
     *
     * @return RedirectResponse
     */
    public function postRegisterRestaurant(RegisterRestaurantRequest $request): RedirectResponse
    {
        $name = $request->request->get('name');
        $description = $request->request->get('description');

        $registered = $this->registerRestaurant->registerRestaurant($name, $description);

        return back()->with('notification', 'Register restaurant success');
    }
}
