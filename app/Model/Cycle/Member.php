<?php

namespace App\Model\Cycle;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cycle_id
 * @property int $user_id
 */
class Member extends Model
{
    protected $table = 'cycle_members';

    protected $fillable = ['user_id'];
}
