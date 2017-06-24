<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $name
 * @property string $description
 */
class Restaurant extends Model
{
    protected $fillable = ['name', 'description'];
}
