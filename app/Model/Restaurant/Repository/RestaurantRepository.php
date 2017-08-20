<?php

namespace App\Model\Restaurant\Repository;

use App\Common\Exception\RepositoryException;
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
     * @throws RepositoryException
     */
    public function get(int $id): Restaurant
    {
        try {
            $restaurant = $this->restaurant->where('id', '=', $id)
                ->firstOrFail();
        } catch (\Throwable $e) {
            throw RepositoryException::getNotFound(self::class, Restaurant::class, $id, $e);
        }

        return $restaurant;
    }

    /**
     * @param int $id
     *
     * @return Restaurant
     */
    public function find(int $id): Restaurant
    {
        $restaurant = $this->restaurant->where('id', '=', $id)
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
        $restaurants = $this->restaurant->whereIn('id', $ids)
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
        int $page = 1,
        string $order = 'asc',
        int $size = self::DEFAULT_PAGE_SIZE
    ): iterable {
        // todo: create pagination result VO in common
        if ($name === null) {
            $paginated = $this->restaurant->orderBy('name', $order)
                ->paginate($size, $columns = ['*'], $pageName = 'page', $page);
            return $paginated;
        }

        $paginated = $this->restaurant->where('name', 'ilike', "%{$name}%")
            ->orderBy('name', $order)
            ->paginate($size, $columns = ['*'], $pageName = 'page', $page);

        return $paginated;
    }
}
