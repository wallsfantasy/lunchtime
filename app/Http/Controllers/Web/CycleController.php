<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCycleRequest;
use App\Http\Requests\JoinCycleRequest;
use App\Http\Requests\LeaveCycleRequest;
use App\Http\Requests\PageCycleRequest;
use App\Model\Cycle\Application\CreateCycle;
use App\Model\Cycle\Application\JoinCycle;
use App\Model\Cycle\Application\LeaveCycle;
use App\Model\Cycle\CycleRepository;
use App\Model\Cycle\Projection\CyclePageQuery;
use App\Model\User\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CycleController extends Controller
{
    const PAGE_SIZE  = 20;
    const PAGE_ORDER = 'asc';

    /** @var CycleRepository */
    private $cycleRepo;

    /** @var UserRepository */
    private $userRepo;

    /** @var CreateCycle */
    private $createCycle;

    /** @var JoinCycle */
    private $joinCycle;

    /** @var LeaveCycle */
    private $leaveCycle;

    /** @var CyclePageQuery */
    private $cyclePageQuery;

    public function __construct(
        CycleRepository $cycleRepo,
        UserRepository $userRepo,
        CreateCycle $createCycle,
        JoinCycle $joinCycle,
        LeaveCycle $leaveCycle,
        CyclePageQuery $cyclePageQuery
    ) {
        $this->cycleRepo = $cycleRepo;
        $this->userRepo = $userRepo;
        $this->createCycle = $createCycle;
        $this->joinCycle = $joinCycle;
        $this->leaveCycle = $leaveCycle;
        $this->cyclePageQuery = $cyclePageQuery;
    }

    /**
     * Show Cycle page
     *
     * @return View
     */
    public function index(PageCycleRequest $request): View
    {
        $pageNum = $request->request->get('page');
        $searchName = $request->request->get('name');

        $myUserId = $request->user()->id;

        $cyclePage = $this->cyclePageQuery->queryPage($myUserId, $pageNum, $searchName);

        return view('cycle', compact('cyclePage', ['myUserId' => $myUserId]));
    }

    /**
     * POST method to make search
     *
     * @param PageCycleRequest $request
     *
     * @return RedirectResponse
     */
    public function postSearch(PageCycleRequest $request): RedirectResponse
    {
        $name = $request->request->get('name');

        if ($name === null) {
            return redirect()->action('Web\CycleController@index');
        }

        return redirect()->action('Web\CycleController@index', ['name' => $name]);
    }

    /**
     * POST method to create cycle
     *
     * @param CreateCycleRequest $request
     *
     * @return RedirectResponse
     */
    public function postCreateCycle(CreateCycleRequest $request): RedirectResponse
    {
        $name = $request->request->get('name');

        $lunchtime = $request->request->get('lunchtime');
        list($lunchHour, $lunchMinute) = explode(':', $lunchtime);
        $lunchtime = new \DateInterval("PT{$lunchHour}H{$lunchMinute}M");

        $proposeUntil = $request->request->get('propose_until');
        if ($proposeUntil !== null) {
            list($proposeHour, $proposeMinute, $proposeSecond) = explode(':', $proposeUntil);
            $proposeUntil = new \DateInterval("PT{$proposeHour}H{$proposeMinute}M");
        }

        $this->createCycle->createCycle($name, $lunchtime, $proposeUntil);

        return back()->with('flash-message.success', 'Cycle registered.');
    }

    /**
     * POST method to join cycle
     *
     * @param JoinCycleRequest $request
     *
     * @return RedirectResponse
     */
    public function postJoinCycle(JoinCycleRequest $request): RedirectResponse
    {
        $cycleId = $request->request->get('cycle_id');

        $this->joinCycle->joinCycle($cycleId);

        return back()->with('flash-message.success', 'Cycle joined.');
    }

    /**
     * POST method to leave cycle
     *
     * @param JoinCycleRequest $request
     *
     * @return RedirectResponse
     */
    public function postLeaveCycle(LeaveCycleRequest $request): RedirectResponse
    {
        $cycleId = $request->request->get('cycle_id');

        $this->leaveCycle->leaveCycle($cycleId);

        return back()->with('flash-message.warning', 'Cycle left.');
    }
}
