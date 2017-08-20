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
    public function index(PageRestaurantRequest $request): View
    {
        $page = $request->request->get('page');
        $name = $request->request->get('name');

        [$restaurants, $registrants] = $this->queryRestaurantView($name, $page);

        return view('restaurant', compact('restaurants', 'registrants'));
    }

    /**
     * POST method to make search
     *
     * @param PageRestaurantRequest $request
     *
     * @return RedirectResponse
     */
    public function postSearch(PageRestaurantRequest $request): RedirectResponse
    {
        $name = $request->request->get('name');

        if ($name === null) {
            return redirect()->action('Web\RestaurantController@index');
        }

        return redirect()->action('Web\RestaurantController@index', ['name' => $name]);
    }

    /**
     * POST method to register restaurant
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

        return back()->with('notification', "Restaurant {$registered->name} registered.");
    }

    /**
     * Prepare data to display restaurant view
     *
     * @param string|null $name
     * @param int         $page
     *
     * @return array
     */
    private function queryRestaurantView(string $name = null, int $page = null): array
    {
        $restaurants = $this->restaurantRepo->pageByRestaurantName($name, $page ?? 1, self::PAGE_ORDER, self::PAGE_SIZE);

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
}
