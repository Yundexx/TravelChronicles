<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This model represents a route tag.
 * Tags are used to categorize routes.
 */
class Tag extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = ['name'];

    /**
     * Get all routes associated with this tag.
     */
    public function routes()
    {
        return $this->belongsToMany(Travel_route::class, 'route_tag', 'tag_id', 'travel_route_id');
    }
}