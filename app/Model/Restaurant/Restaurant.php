<?php

namespace App\Model\Restaurant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $name
 * @property string $description
 * @property int    $register_user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Restaurant extends Model
{
    protected $fillable = ['name', 'description', 'register_user_id'];
}
