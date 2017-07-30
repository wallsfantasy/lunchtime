<?php

namespace App\Model\Restaurant\Repository;

use App\Model\Restaurant\Restaurant;
use Illuminate\Database\Eloquent\Collection;

class RestaurantRepository
{
    /** @var Restaurant $restaurant */
    private $restaurant;

    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * @param int $id
     *
     * @return Restaurant
     */
    public function find(int $id): Restaurant
    {
        $restaurant = $this->restaurant::where('id', '=', $id)
            ->first();

        return $restaurant;
    }

    /**
     * @param int[] $ids
     *
     * @return iterable|Collection|Restaurant[]
     */
    public function findByIds(array $ids): iterable
    {
        $restaurants = $this->restaurant::whereIn('id', $ids)
            ->get();

        return $restaurants;
    }
}
