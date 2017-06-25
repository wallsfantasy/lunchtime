<?php

namespace App\Http\Controllers\Api;

use App\Cycle;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCycleRequest;
use App\Http\Requests\JoinCycleRequest;
use App\Service\CycleService;
use App\User;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    /** @var CycleService */
    private $cycleService;

    public function __construct(CycleService $cycleService)
    {
        $this->cycleService = $cycleService;
    }

    /**
     * Create a Cycle
     *
     * @param CreateCycleRequest $request
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
     * @return User
     */
    public function join(JoinCycleRequest $request)
    {
        return $this->cycleService->joinCycle(\Auth::user(), $request->get('cycle_id'));
    }
}
