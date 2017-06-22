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
     * @return array
     */
    public function register(RegisterRestaurantRequest $request)
    {
        $restaurant = Restaurant::create($request->all());

        return [
            'success' => true,
            'data' => $restaurant,
        ];
    }
}
