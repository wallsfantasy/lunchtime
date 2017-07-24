<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCycleRequest;
use App\Http\Requests\JoinCycleRequest;
use App\Model\Cycle\Cycle;
use App\Model\Cycle\Domain\CreateCycle;
use App\Model\Cycle\Domain\JoinCycle;
use App\Model\Cycle\Member;
use Illuminate\Database\Eloquent\Model;

class CycleController extends Controller
{
    /** @var CreateCycle */
    private $createCycle;

    /** @var JoinCycle */
    private $joinCycle;

    public function __construct(CreateCycle $createCycle, JoinCycle $joinCycle)
    {
        $this->createCycle = $createCycle;
        $this->joinCycle   = $joinCycle;
    }

    /**
     * Create a Cycle
     *
     * @param CreateCycleRequest $request
     *
     * @return Model|Cycle
     */
    public function create(CreateCycleRequest $request)
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
     * @param JoinCycleRequest $request
     *
     * @return Member
     */
    public function join(JoinCycleRequest $request)
    {
        return $this->joinCycle->joinCycle($request->get('cycle_id'));
    }
}
