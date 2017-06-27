<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * @return BelongsToMany|User[]
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
