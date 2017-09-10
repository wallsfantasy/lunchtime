<?php

namespace App\Model\Propose;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property Carbon $for_date
 * @property Carbon $proposed_at
 * @property int    $user_id
 * @property int    $restaurant_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @mixin \Eloquent
 */
class Propose extends Model
{
    const DAY_PROPOSES_LIMIT = 3;

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes(['proposed_at' => Carbon::now()], true);
        parent::__construct($attributes);
    }

    protected $casts = [
        'for_date' => 'date',
        'proposed_at' => 'datetime',
    ];

    protected $fillable = ['user_id', 'restaurant_id', 'for_date'];
}
