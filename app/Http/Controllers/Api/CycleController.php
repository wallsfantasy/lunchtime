<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCycleApiRequest;
use App\Model\Cycle\Application\CreateCycle;
use App\Model\Cycle\Application\JoinCycle;
use App\Model\Cycle\Application\LeaveCycle;
use App\Model\Cycle\Cycle;
use App\Model\Cycle\Event\MemberLeftCycleEvent;
use App\Model\Cycle\Event\UserJoinedCycleEvent;
use App\Model\Cycle\Member;
use App\Model\User\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

class CycleController extends Controller
{
    /** @var CreateCycle */
    private $createCycle;

    /** @var JoinCycle */
    private $joinCycle;

    /** @var LeaveCycle */
    private $leaveCycle;

    public function __construct(CreateCycle $createCycle, JoinCycle $joinCycle, LeaveCycle $leaveCycle)
    {
        $this->createCycle = $createCycle;
        $this->joinCycle = $joinCycle;
        $this->leaveCycle = $leaveCycle;
    }

    /**
     * Create a Cycle
     *
     * @param CreateCycleApiRequest $request
     *
     * @return Model|Cycle
     */
    public function create(CreateCycleApiRequest $request)
    {
        $name = $request->request->get('name');

        $lunchtime = $request->request->get('lunchtime');
        list($lunchHour, $lunchMinute, $lunchSecond) = explode(':', $lunchtime);
        $lunchtime = new \DateInterval("PT{$lunchHour}H{$lunchMinute}M{$lunchSecond}S");

        $proposeUntil = $request->request->get('propose_until');
        if ($proposeUntil !== null) {
            list($proposeHour, $proposeMinute, $proposeSecond) = explode(':', $proposeUntil);
            $proposeUntil = new \DateInterval("PT{$proposeHour}H{$proposeMinute}M{$proposeSecond}S");
        }

        // @todo: catch and return appopriate data, might be abort(...)
        return $this->createCycle->createCycle($name, $lunchtime, $proposeUntil);
    }

    /**
     * Join a cycle
     *
     * @param string $cycleId
     *
     * @return Model|Member
     */
    public function join(string $cycleId)
    {
        return $this->joinCycle->joinCycle($cycleId);
    }

    /**
     * Leave a cycle
     *
     * @param string $cycleId
     *
     * @return Model|Cycle
     */
    public function leave(string $cycleId)
    {
        return $this->leaveCycle->leaveCycle($cycleId);
    }

    /**
     * Push a UserJoinedCycleEvent
     */
    public function pushUserJoinedEvent(Request $request, UserRepository $userRepo)
    {
        $userId = $request->user()->id;
        $cycleId = $request->get('cycle_id');
        $cycleName = $request->get('cycle_name');

        $event = new UserJoinedCycleEvent($cycleId, $cycleName, $userId);
        $event->addMeta();
        $event->enrich($userRepo);

        Broadcast::event($event);
    }

    /**
     * Push a MemberLeftCycleEvent
     */
    public function pushMemberLeftCycleEvent(Request $request, UserRepository $userRepo)
    {
        $cycleId = $request->get('cycle_id');
        $cycleName = $request->get('cycle_name');
        $memberId = $request->get('member_id');
        $memberUserId = $request->get('member_user_id');

        $event = new MemberLeftCycleEvent($cycleId, $cycleName, $memberId, $memberUserId);
        $event->addMeta();
        $event->enrich($userRepo);

        Broadcast::event($event);
    }
}
