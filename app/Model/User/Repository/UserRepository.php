<?php

namespace App\Model\User\Repository;

use App\Model\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    const DEFAULT_PAGE_SIZE = 20;

    /** @var User */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param int[] $ids
     *
     * @return iterable|Collection|User[]
     */
    public function findByIds(array $ids): iterable
    {
        $users = $this->user->whereIn('id', $ids)
            ->get();

        return $users;
    }

    /**
     * @param string|null $name
     * @param int         $page
     * @param string      $order
     * @param int         $size
     *
     * @return iterable|LengthAwarePaginator|User[]
     * @throws \InvalidArgumentException
     */
    public function pageByUserName(
        string $name = null,
        int $page = 1,
        string $order = 'asc',
        int $size = self::DEFAULT_PAGE_SIZE
    ): iterable {
        // todo: create pagination result VO in common
        if ($name === null) {
            $paginated = $this->user->orderBy('name', $order)
                ->paginate($size, $columns = ['*'], $pageName = 'page', $page);
            return $paginated;
        }

        $paginated = $this->user->where('name', 'ilike', "%{$name}%")
            ->orderBy('name', $order)
            ->paginate($size, $columns = ['*'], $pageName = 'page', $page);

        return $paginated;
    }
}
