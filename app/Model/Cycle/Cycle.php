<?php

namespace App\Model\Cycle;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int    $id
 * @property string $name
 * @property string $propose_until
 * @property string $lunchtime
 * @mixin \Eloquent
 */
class Cycle extends Model
{
    const DEFAULT_PROPOSE_BEFORE_LUNCHTIME = 'PT5M';

    protected $fillable = ['name', 'propose_until', 'lunchtime', 'user_id'];

    /**
     * The Users that belongs to this cycle
     *
     * @return HasMany|Collection|Member[]
     */
    public function members()
    {
        return $this->hasMany(Member::class, 'cycle_id');
    }
}
