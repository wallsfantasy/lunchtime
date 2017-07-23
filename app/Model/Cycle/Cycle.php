<?php

namespace App\Model\Cycle;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int    $id
 * @property string $name
 */
class Cycle extends Model
{
    protected $fillable = ['name', 'propose_until'];

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
