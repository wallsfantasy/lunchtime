<?php

namespace App\Model\Cycle;

use App\Common\Event\RecordEventsTrait;
use App\Model\Cycle\Event\CycleClosedEvent;
use App\Model\Cycle\Event\MemberLeftCycleEvent;
use App\Model\Cycle\Event\UserJoinedCycleEvent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    use RecordEventsTrait;

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
     * @param int $userId
     */
    public function joinCycle(int $userId)
    {
        $this->guardJoinAlreadyMember($userId);

        $member = new Member(['cycle_id' => $this->id, 'user_id' => $userId]);
        $this->members->add($member);

        // generate userJoinedCycleEvent
        $this->recordEvent(new UserJoinedCycleEvent($this->id, $this->name, $member->user_id));
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
        /** @var Member $member */
        $member = $this->members->pull($key);

        // generate memberLeftCycleEvent
        $this->recordEvent(new MemberLeftCycleEvent($this->id, $this->name, $member->id, $member->user_id));
    }

    public function closeCycle()
    {
        $this->guardCycleCloseStillHaveMember();

        // generate cycleClosedEvent
        $this->recordEvent(new CycleClosedEvent($this->id, $this->name));
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
    private function guardCycleCloseStillHaveMember()
    {
        if (count($this->members) > 0) {
            throw CycleException::createCloseCycleHasMember(null, ['cycle' => $this->toArray()]);
        }
    }
}
