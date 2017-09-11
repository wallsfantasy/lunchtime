<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeProposeRequest;
use App\Http\Requests\PageRestaurantRequest;
use App\Model\Propose\Application\MakePropose;
use App\Model\Propose\Propose;
use App\Model\Propose\ProposeRepository;
use App\Model\Restaurant\Repository\RestaurantRepository;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ProposeController extends Controller
{
    const PAGE_SIZE  = 20;
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
    public function index(PageRestaurantRequest $request)
    {
        $name = $request->query->get('name');
        $page = $request->query->get('page');

        $userId = $request->user()->id;

        [$todayProposes, $currentPropose, $restaurants] = $this->queryProposePageData($userId, $name, $page);

        return view('propose', compact('todayProposes', 'currentPropose', 'restaurants'));
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

        $this->makePropose->makePropose($restaurantId, Carbon::today());

        return back()->with('Proposed a restaurant success.');
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
        // restaurants to show in propose restaurant list
        $restaurants = $this->restaurantRepo->pageByRestaurantName($restaurantName, $page ?? 1, 'asc', $size);

        /** @var Collection|Propose[] $todayProposes */
        $todayProposes = $this->proposeRepo
            ->findAllByUserIdsForDate([$userId], Carbon::today())
            ->sortByDesc('proposed_at');

        $proposedRestaurantIds = array_column($todayProposes->toArray(), 'restaurant_id');
        $proposedRestaurants = (count($proposedRestaurantIds) > 0)
            ? $this->restaurantRepo->findAllByIds($proposedRestaurantIds)->keyBy('id')
            : Collection::make();

        foreach ($todayProposes as &$propose) {
            $propose->restaurant = $proposedRestaurants[$propose->restaurant_id] ?? null;
        }

        $currentPropose = $todayProposes->first();

        return [$todayProposes, $currentPropose, $restaurants];
    }
}
