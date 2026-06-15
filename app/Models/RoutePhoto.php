<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This model represents a route photo.
 * It stores image paths associated with routes.
 */
class RoutePhoto extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = ['route_id', 'photo_path'];
}