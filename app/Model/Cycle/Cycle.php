<?php

namespace App\Model\Cycle;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                 $id
 * @property string              $name
 * @property string              $propose_until
 * @property string              $lunchtime
 * @property Collection|Member[] $members
 * @mixin \Eloquent
 */
class Cycle extends Model
{
    const DEFAULT_PROPOSE_BEFORE_LUNCHTIME = 'PT0M';

    protected $fillable = ['name', 'propose_until', 'lunchtime', 'creator_user_id'];

    /**
     * The Users that belongs to this cycle
     *
     * @return HasMany|Member[]
     */
    public function members()
    {
        return $this->hasMany(Member::class, 'cycle_id');
    }
}
