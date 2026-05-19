<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['user_id', 'route_id'];

    public function route()
    {
        return $this->belongsTo(Travel_route::class, 'route_id');
    }
}
