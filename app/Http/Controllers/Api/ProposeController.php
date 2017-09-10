<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeProposeRequest;
use App\Model\Propose\Application\MakePropose;
use App\Model\Propose\Propose;
use Carbon\Carbon;

class ProposeController extends Controller
{
    /** @var MakePropose */
    private $makePropose;

    public function __construct(MakePropose $makePropose)
    {
        $this->makePropose = $makePropose;
    }

    /**
     * Create a Cycle
     *
     * @param MakeProposeRequest $request
     *
     * @return Propose
     */
    public function make(MakeProposeRequest $request): Propose
    {
        $restaurantId = $request->request->get('restaurant_id');
        $forDate = $request->request->get('for_date');
        $forDate = new Carbon($forDate);

        $propose = $this->makePropose->makePropose($restaurantId, $forDate);

        return $propose;
    }
}
