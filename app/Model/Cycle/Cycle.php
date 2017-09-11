<?php

namespace App\Model\Cycle;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

/**
 * @property int                      $id
 * @property string                   $name
 * @property string                   $propose_until
 * @property string                   $lunchtime
 * @property int                      $creator_user_id
 * @property-read Collection|Member[] $members
 * @mixin \Eloquent
 */
class Cycle extends Model
{
    const DEFAULT_PROPOSE_BEFORE_LUNCHTIME = 'PT0M';

    public $incrementing = false;

    protected $fillable = ['id', 'name', 'propose_until', 'lunchtime', 'creator_user_id'];

    /**
     * The Users that belongs to this cycle
     *
     * @return HasMany|Member[]
     */
    public function members()
    {
        return $this->hasMany(Member::class, 'cycle_id');
    }

    /**
     * @param int                $userId
     * @param string             $name
     * @param \DateInterval      $lunchtime
     * @param \DateInterval|null $proposeUntil
     *
     * @return self
     */
    public static function createCycle(
        int $userId,
        string $name,
        \DateInterval $lunchtime,
        ?\DateInterval $proposeUntil
    ): self {
        // make default propose until if need
        if ($proposeUntil === null) {
            $todayProposeUntil = Carbon::today()->add($lunchtime)->sub(new \DateInterval(self::DEFAULT_PROPOSE_BEFORE_LUNCHTIME));
            $proposeUntil = Carbon::today()->diff($todayProposeUntil);
        }

        static::guardProposeUntilAndLunchtime($proposeUntil, $lunchtime);

        $cycleId = Uuid::uuid4()->toString();
        $data = [
            'id' => $cycleId,
            'name' => $name,
            'lunchtime' => $lunchtime->format('%H:%I:%S'),
            'propose_until' => $proposeUntil->format('%H:%I:%S'),
            'creator_user_id' => $userId,
        ];
        $cycle = new static($data);

        $member = new Member([
            'cycle_id' => $cycleId,
            'user_id' => $userId,
        ]);
        $cycle->members->add($member);

        // generate cycleCreatedEvent

        return $cycle;
    }

    /**
     * @param int $userId
     */
    public function joinCycle(int $userId)
    {
        $this->guardJoinAlreadyMember($userId);

        $member = new Member(['cycle_id' => $this->id, 'user_id' => $userId]);
        $this->members->add($member);

        // generate userJoinedCycleEvent
    }

    /**
     * @param int $userId
     */
    public function leaveCycle(int $userId)
    {
        $this->guardLeaveCycleNotMember($userId);

        $key = $this->members->search(function (Member $member) use ($userId) {
            return $member->user_id === $userId;
        });
        $this->members->pull($key);

        // generate memberLeftCycleEvent
    }

    public function closeCycle()
    {
        $this->guardCycleCloseStillHavingMember();

        // generate cycleClosedEvent
    }

    /**
     * @param \DateInterval $proposeUntil
     * @param \DateInterval $lunchtime
     *
     * @throws CycleException
     */
    private static function guardProposeUntilAndLunchtime(\DateInterval $proposeUntil, \DateInterval $lunchtime)
    {
        // check times make sense
        $todayLunchtime = Carbon::today()->add($lunchtime);
        $todayProposeUntil = Carbon::today()->add($proposeUntil);
        if ($todayLunchtime < $todayProposeUntil) {
            $lunchtimeFormatted = $lunchtime->format('%H:%I:%S');
            $proposeUntilFormatted = $proposeUntil->format('%H:%I:%S');
            throw CycleException::createLunchtimeBeforePropose(null, [
                'propose_until' => $proposeUntilFormatted,
                'lunchtime' => $lunchtimeFormatted,
            ]);
        }
    }

    /**
     * @param int $userId
     *
     * @throws CycleException
     */
    private function guardJoinAlreadyMember(int $userId)
    {
        if (null !== $this->members->where('user_id', $userId)->first()) {
            throw CycleException::createJoinAlreadyMemberCycle(null,
                ['user_id' => $userId, 'cycle_id' => $this->toArray()]
            );
        }
    }

    /**
     * @param int $userId
     *
     * @throws CycleException
     */
    private function guardLeaveCycleNotMember(int $userId)
    {
        if (null === $this->members->where('user_id', $userId)->first()) {
            throw CycleException::createLeaveCycleNotMember(null,
                ['user_id' => $userId, 'cycle_id' => $this->toArray()]);
        }
    }

    /**
     * @throws CycleException
     */
    private function guardCycleCloseStillHavingMember()
    {
        if (count($this->members) > 0) {
            throw CycleException::createCloseCycleHasMember(null, ['cycle' => $this->toArray()]);
        }
    }
}
