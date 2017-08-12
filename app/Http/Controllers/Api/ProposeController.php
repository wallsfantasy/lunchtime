<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeProposeRequest;
use App\Http\Requests\ReProposeRequest;
use App\Model\Propose\Domain\MakePropose;
use App\Model\Propose\Domain\RePropose;
use App\Model\Propose\Propose;
use Carbon\Carbon;

class ProposeController extends Controller
{
    /** @var MakePropose */
    private $makePropose;

    /** @var RePropose */
    private $rePropose;

    public function __construct(MakePropose $makePropose, Repropose $rePropose)
    {
        $this->makePropose = $makePropose;
        $this->rePropose = $rePropose;
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

    /**
     * @param ReProposeRequest $request
     *
     * @return Propose
     */
    public function rePropose(ReProposeRequest $request): Propose
    {
        $restaurantId = $request->request->get('restaurant_id');
        $forDate = $request->request->get('for_date');
        $forDate = new Carbon($forDate);

        $propose = $this->rePropose->rePropose($restaurantId, $forDate);

        return $propose;
    }
}
