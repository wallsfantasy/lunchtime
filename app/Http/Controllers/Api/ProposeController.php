<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeProposeRequest;
use App\Propose;

class ProposeController extends Controller
{
    /**
     * Create a Cycle
     *
     * @param MakeProposeRequest $request
     * @return Propose
     */
    public function make(MakeProposeRequest $request)
    {
        $user = \Auth::user();

        return Propose::create(
            [
                'user_id' => $user->id,
                'restaurant_id' => $request->get('restaurant_id'),
            ]
        );
    }
}
