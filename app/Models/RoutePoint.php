<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutePoint extends Model
{
    protected $fillable = [
        'route_id',
        'latitude',
        'longitude',
        'order'
    ];
}
