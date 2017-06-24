<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Restaurant\RegisterRestaurantRequest;
use App\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Register a restaurant
     *
     * @param RegisterRestaurantRequest $request
     * @return Restaurant
     */
    public function register(RegisterRestaurantRequest $request)
    {
        return Restaurant::create($request->all());
    }

    /**
     * Get restaurant by Id
     *
     * @param int $id
     * @return array
     */
    public function get(int $id)
    {
        return Restaurant::findOrFail($id);
    }
}
