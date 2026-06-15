<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This model represents a favorite route.
 * It stores the relationship between a user and a route.
 */
class Favorite extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = ['user_id', 'route_id'];

    /**
     * Get the associated route.
     */
    public function route()
    {
        return $this->belongsTo(Travel_route::class, 'route_id');
    }
}