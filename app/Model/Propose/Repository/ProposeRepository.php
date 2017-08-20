<?php

namespace App\Model\Propose\Repository;

use App\Common\Exception\RepositoryException;
use App\Model\Propose\Propose;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ProposeRepository
{
    /** @var Propose */
    private $propose;

    public function __construct(Propose $propose)
    {
        $this->propose = $propose;
    }

    /**
     * @param int $id
     *
     * @return Propose
     * @throws RepositoryException
     */
    public function get(int $id): Propose
    {
        try {
            $propose = $this->propose->firstOrFail($id);
        } catch (\Throwable $e) {
            throw RepositoryException::getNotFound(self::class, Propose::class, $id, $e);
        }
    }

    /**
     * @param int            $userId
     * @param \DateTime|null $date
     *
     * @return Propose|null
     */
    public function findLatestByUserIdForDate(int $userId, \DateTime $date = null): ?Propose
    {
        $date = $date ?? Carbon::today();
        $propose = $this->propose->where('user_id', '=', $userId)
            ->where('for_date', '=', $date->format('Y-m-d'))
            ->orderBy('proposed_at', 'desc')
            ->first();

        return $propose;
    }

    /**
     * @param int[]          $userIds
     * @param \DateTime|null $date
     *
     * @return iterable|Collection|Propose[]
     */
    public function findByUserIdsForDate(array $userIds, \DateTime $date = null): iterable
    {
        $date = $date ?? Carbon::today();
        $proposes = $this->propose->whereIn('user_id', $userIds)
            ->where('for_date', '=', $date->format('Y-m-d'))
            ->get();

        return $proposes;
    }

    /**
     * @param Propose $propose
     *
     * @return Propose
     * @throws RepositoryException
     */
    public function add(Propose $propose): Propose
    {
        if ($propose->exists === true) {
            throw RepositoryException::addAlreadyExistEntity(
                self::class,
                Propose::class, $propose->id,
                $propose->toArray()
            );
        }

        try {
            $success = $propose->save();
        } catch (\Throwable $e) {
            throw RepositoryException::infrastructural(self::class, Propose::class, $e);
        }

        if ($success === false) {
            throw RepositoryException::infrastructural(self::class, Propose::class);
        }

        return $propose;
    }
}
