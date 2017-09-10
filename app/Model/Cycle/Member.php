<?php

namespace App\Model\Cycle;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cycle_id
 * @property int $user_id
 * @mixin \Eloquent
 */
class Member extends Model
{
    protected $table = 'cycle_members';

    protected $fillable = ['cycle_id', 'user_id'];
}
