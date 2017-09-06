<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeProposeRequest;
use App\Http\Requests\PageRestaurantRequest;
use App\Http\Requests\ReProposeRequest;
use App\Model\Propose\Application\MakePropose;
use App\Model\Propose\Application\RePropose;
use App\Model\Propose\Repository\ProposeRepository;
use App\Model\Restaurant\Repository\RestaurantRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProposeController extends Controller
{
    const PAGE_SIZE  = 20;
    const PAGE_ORDER = 'asc';

    /** @var MakePropose */
    private $makePropose;

    /** @var RePropose */
    private $rePropose;

    /** @var RestaurantRepository */
    private $restaurantRepo;

    /** @var ProposeRepository */
    private $proposeRepo;

    public function __construct(
        MakePropose $makePropose,
        RePropose $rePropose,
        RestaurantRepository $restaurantRepo,
        ProposeRepository $proposeRepo
    ) {
        $this->makePropose = $makePropose;
        $this->rePropose = $rePropose;
        $this->restaurantRepo = $restaurantRepo;
        $this->proposeRepo = $proposeRepo;
    }

    /**
     * Show Restaurant page
     *
     * @return View
     */
    public function index(PageRestaurantRequest $request)
    {
        $name = $request->query->get('name');
        $page = $request->query->get('page');

        $userId = $request->user()->id;

        [$restaurants, $currentPropose, $currentRestaurant] = $this->queryProposePageData($userId, $name, $page);

        return view('propose', compact('restaurants', 'currentPropose', 'currentRestaurant'));
    }

    /**
     * POST to search restaurants
     *
     * @param PageRestaurantRequest $request
     *
     * @return RedirectResponse
     */
    public function postRestaurantSearch(PageRestaurantRequest $request)
    {
        $name = $request->request->get('name') ?? null;

        return redirect()->action('Web\ProposeController@index', ['name' => $name]);
    }

    /**
     * POST method to make propose
     *
     * @param MakeProposeRequest $makeProposeRequest
     *
     * @return RedirectResponse
     */
    public function postMakePropose(MakeProposeRequest $makeProposeRequest): RedirectResponse
    {
        $restaurantId = $makeProposeRequest->request->get('restaurant_id');

        $propose = $this->makePropose->makePropose($restaurantId);

        return back()->with('Proposed a restaurant success.');
    }

    /**
     * POST method to re-propose
     *
     * @param ReProposeRequest $reProposeRequest
     *
     * @return RedirectResponse
     */
    public function postRePropose(ReProposeRequest $reProposeRequest): RedirectResponse
    {
        $restaurantId = $reProposeRequest->request->get('restaurant_id');

        $propose = $this->rePropose->rePropose($restaurantId);

        return back()->with('Re-propose a restaurant success.');
    }

    /**
     * Query data to display Propose page
     *
     * @param int         $userId
     * @param string|null $restaurantName
     * @param int         $page
     * @param int         $size
     *
     * @return array
     */
    private function queryProposePageData(
        int $userId,
        string $restaurantName = null,
        int $page = null,
        int $size = self::PAGE_SIZE
    ) {
        $restaurants = $this->restaurantRepo->pageByRestaurantName($restaurantName, $page ?? 1, $size);

        $currentPropose = $this->proposeRepo->findLatestByUserIdForDate($userId);
        $currentRestaurant = ($currentPropose === null) ? null : $this->restaurantRepo->find($currentPropose->restaurant_id);

        return [$restaurants, $currentPropose, $currentRestaurant];
    }
}
