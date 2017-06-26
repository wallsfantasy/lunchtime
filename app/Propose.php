<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $user_id
 * @property int    $restaurant_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Propose extends Model
{
    protected $fillable = ['name'];

    /**
     * The User that made this propose
     *
     * @return BelongsTo|User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The proposed Restaurant
     *
     * @return BelongsTo|Restaurant
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
