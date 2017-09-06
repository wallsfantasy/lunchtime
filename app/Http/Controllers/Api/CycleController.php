<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCycleRequest;
use App\Model\Cycle\Cycle;
use App\Model\Cycle\Application\CreateCycle;
use App\Model\Cycle\Application\JoinCycle;
use App\Model\Cycle\Application\LeaveCycle;
use App\Model\Cycle\Member;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * Get Cycles
     *
     * @return Collection|Cycle[]
     */
    public function get()
    {
        return Cycle::all();
    }

    /**
     * Get Cycle by id
     *
     * @param int $cycleId
     *
     * @return Model|Cycle
     */
    public function getById(int $cycleId)
    {
        return Cycle::find(['id' => $cycleId]);
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
     * @param int $cycleId
     *
     * @return Model|Member
     */
    public function join(int $cycleId)
    {
        return $this->joinCycle->joinCycle($cycleId);
    }

    /**
     * Leave a cycle
     *
     * @param int $cycleId
     *
     * @return array
     */
    public function leave(int $cycleId)
    {
        $result = $this->leaveCycle->leaveCycle($cycleId);

        return ['success' => $result];
    }
}
