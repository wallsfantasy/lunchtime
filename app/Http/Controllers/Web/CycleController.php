<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCycleRequest;
use App\Http\Requests\PageCycleRequest;
use App\Model\Cycle\Application\CreateCycle;
use App\Model\Cycle\Cycle;
use App\Model\Cycle\CycleRepository;
use App\Model\User\Repository\UserRepository;
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

    public function __construct(CycleRepository $cycleRepo, UserRepository $userRepo, CreateCycle $createCycle)
    {
        $this->cycleRepo = $cycleRepo;
        $this->userRepo = $userRepo;
        $this->createCycle = $createCycle;
    }

    /**
     * Show Cycle page
     *
     * @return View
     */
    public function index(PageCycleRequest $request): View
    {
        $page = $request->request->get('page');
        $name = $request->request->get('name');

        [$cycles, $registrants] = $this->queryCycleView($name, $page);

        return view('cycle', compact('cycles', 'registrants'));
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
        $proposeUntil = $request->request->get('propose_until');

        $this->createCycle->createCycle($name, $lunchtime, $proposeUntil);

        return back()->with('notification', 'Cycle registered.');
    }

    /**
     * Prepare data to display cycle view
     *
     * @param string|null $name
     * @param int|null    $page
     *
     * @return array
     */
    private function queryCycleView(?string $name, ?int $page): array
    {
        /** @var Cycle[] $cycles */
        $cycles = $this->cycleRepo
            ->pageByCycleName($name, $page ?? 1, self::PAGE_ORDER, self::PAGE_SIZE)
            ->items();

        // find unique users in all cycles
        $userIds = [];
        foreach ($cycles as $cycle) {
            foreach ($cycle->members as $member) {
                $userIds[$member->user_id] = $member->user_id;
            }
        }
        $registrants = $this->userRepo->findByIds($userIds);

        // view data for $userProposesByCycle
        foreach ($cycles as &$cycle) {
            // registrator_user
            $cycle->creator_user = $registrants->where('id', $cycle->creator_user_id)->first();

            // member_user
            foreach ($cycle->members as &$cycleMember) {
                $cycleMember->user = $registrants->where('id', $cycleMember->user_id)->first();
            }
        }

        return [$cycles, $registrants];
    }
}
