<?php

namespace App\Http\Controllers\Api;

use App\Cycle;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCycleRequest;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    /**
     * Create a Cycle
     *
     * @param CreateCycleRequest $request
     * @return Cycle
     */
    public function createCycle(CreateCycleRequest $request)
    {
        return Cycle::create($request->all());
    }
}
