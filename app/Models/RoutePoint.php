<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This model represents a route point.
 * It stores coordinates that make up a route.
 */
class RoutePoint extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'route_id',
        'latitude',
        'longitude',
        'order'
    ];
}