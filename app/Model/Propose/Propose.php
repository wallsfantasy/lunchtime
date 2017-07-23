<?php

namespace App\Model\Propose;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $user_id
 * @property int    $restaurant_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $for_date
 * @mixin \Eloquent
 */
class Propose extends Model
{
    protected $casts = [
        'for_date' => 'date',
    ];

    protected $fillable = ['user_id', 'restaurant_id', 'for_date'];
}
