<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeProposeRequest;
use App\Http\Requests\PageRestaurantRequest;
use App\Model\Propose\Domain\MakePropose;
use App\Model\Propose\Repository\ProposeRepository;
use App\Model\Restaurant\Repository\RestaurantRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProposeController extends Controller
{
    const PAGE_SIZE = 20;
    const PAGE_ORDER = 'asc';

    /** @var MakePropose */
    private $makePropose;

    /** @var RestaurantRepository */
    private $restaurantRepo;

    /** @var ProposeRepository */
    private $proposeRepo;

    public function __construct(
        MakePropose $makePropose,
        RestaurantRepository $restaurantRepo,
        ProposeRepository $proposeRepo
    ) {
        $this->makePropose = $makePropose;
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

        $restaurants = $this->restaurantRepo->pageByRestaurantName(null, 1, self::PAGE_SIZE);

        $todayProposal = $this->proposeRepo->findByUserIdForDate($userId);

        $todayRestaurant = ($todayProposal === null) ? null : $this->restaurantRepo->find($todayProposal->restaurant_id);

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

        $name = $request->request->get('name') ?? null;
        $page = $request->request->get('page') ?? 1;
        $size = $request->request->get('size') ?? self::PAGE_SIZE;

        // todo: sanitize page, size
        $restaurants = $this->restaurantRepo->pageByRestaurantName($name, $page, $size);

        $todayProposal = $this->proposeRepo->findByUserIdsForDate([$userId]);

        return view('propose', compact('restaurants', 'todayProposal'));
    }

    /**
     * POST method to make propose
     *
     * @param MakeProposeRequest $makeProposeRequest
     *
     * @return RedirectResponse
     */
    public function postMakePropose(MakeProposeRequest $makeProposeRequest)
    {
        $restaurantId = $makeProposeRequest->request->get('restaurant_id');

        $propose = $this->makePropose->makePropose($restaurantId);

        return back()->with('Proposed a restaurant success.');
    }
}
