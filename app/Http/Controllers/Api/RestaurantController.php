<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRestaurantRequest;
use App\Model\Restaurant\Domain\RegisterRestaurant;
use App\Model\Restaurant\Restaurant;

class RestaurantController extends Controller
{
    /** @var RegisterRestaurant $registerRestaurant */
    private $registerRestaurant;

    public function __construct(RegisterRestaurant $registerRestaurant)
    {
        $this->registerRestaurant = $registerRestaurant;
    }

    /**
     * Register a restaurant
     *
     * @param RegisterRestaurantRequest $request
     *
     * @return Restaurant
     */
    public function register(RegisterRestaurantRequest $request)
    {
        $name = $request->request->get('name');
        $description = $request->request->get('description');

        return $this->registerRestaurant->registerRestaurant($name, $description);
    }

    /**
     * Get restaurant by Id
     *
     * @param int $id
     *
     * @return Restaurant
     */
    public function get(int $id)
    {
        return Restaurant::findOrFail($id);
    }
}
