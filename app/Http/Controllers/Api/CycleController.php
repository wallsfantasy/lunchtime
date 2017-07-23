<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCycleRequest;
use App\Http\Requests\JoinCycleRequest;
use App\Model\Cycle\Application\JoinCycle;
use App\Model\Cycle\Cycle;
use App\Model\Cycle\Member;

class CycleController extends Controller
{
    /** @var JoinCycle */
    private $joinCycle;

    public function __construct(JoinCycle $joinCycle)
    {
        $this->joinCycle = $joinCycle;
    }

    /**
     * Create a Cycle
     *
     * @param CreateCycleRequest $request
     *
     * @return Cycle
     */
    public function create(CreateCycleRequest $request)
    {
        return Cycle::create($request->all());
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
