<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeProposeRequest;
use App\Http\Requests\PageRestaurantRequest;
use App\Http\Requests\ReProposeRequest;
use App\Model\Propose\Domain\MakePropose;
use App\Model\Propose\Domain\RePropose;
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
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        [$restaurants, $todayProposal, $todayRestaurant] = $this->queryProposePageData($userId);

        return view('propose', compact('restaurants', 'todayProposal', 'todayRestaurant'));
    }

    /**
     * Show search result of the restaurants
     *
     * @param PageRestaurantRequest $request
     *
     * @return View
     */
    public function restaurantSearch(PageRestaurantRequest $request)
    {
        $userId = $request->user()->id;

        // todo: sanitize page, size
        // todo: same restaurant search path with index?
        $name = $request->request->get('name') ?? null;
        $page = $request->request->get('page') ?? 1;
        $size = $request->request->get('size') ?? self::PAGE_SIZE;

        [$restaurants, $todayProposal, $todayRestaurant] = $this->queryProposePageData($userId, $name, $page, $size);

        return view('propose', compact('restaurants', 'todayProposal', 'todayRestaurant'));
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
        int $page = 1,
        int $size = self::PAGE_SIZE
    ) {
        $restaurants = $this->restaurantRepo->pageByRestaurantName($restaurantName, $page, $size);

        $todayProposal = $this->proposeRepo->findByUserIdForDate($userId);
        $todayRestaurant = ($todayProposal === null) ? null : $this->restaurantRepo->find($todayProposal->restaurant_id);

        return [$restaurants, $todayProposal, $todayRestaurant];
    }
}
