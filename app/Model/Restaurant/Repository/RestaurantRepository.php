<?php

namespace App\Model\Restaurant\Repository;

use App\Model\Restaurant\Restaurant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RestaurantRepository
{
    const DEFAULT_PAGE_SIZE = 20;

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

    /**
     * @param string|null $name
     * @param int|null    $page
     * @param string      $order
     * @param int         $size
     *
     * @return iterable|LengthAwarePaginator
     * @throws \InvalidArgumentException
     */
    public function pageByRestaurantName(
        string $name = null,
        int $page = null,
        string $order = 'asc',
        int $size = self::DEFAULT_PAGE_SIZE
    ): iterable {
        // todo: create pagination result VO in common
        if ($name === null) {
            $paginated = $this->restaurant::orderBy('name', $order)
                ->paginate($size, $columns = ['*'], $pageName = 'page', $page);
            return $paginated;
        }

        $paginated = $this->restaurant::where('name', 'like', "%{$name}%")
            ->orderBy('name', $order)
            ->paginate($size, $columns = ['*'], $pageName = 'page', $page);

        return $paginated;
    }
}
