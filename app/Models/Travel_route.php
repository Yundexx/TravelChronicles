<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RoutePoint;
use App\Models\RoutePhoto;
use App\Models\User;

/**
 * This model represents a travel route.
 * It stores route information and related data.
 */
class Travel_route extends Model
{
    /** @use HasFactory<\Database\Factories\TravelRouteFactory> */
    use HasFactory;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'description',
        'country_name',
        'city_name',
        'start_location',
        'end_location',
        'user_id',
        'flagged'
    ];

    /**
     * Get the user who created the route.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all photos associated with the route.
     */
    public function photos()
    {
        return $this->hasMany(RoutePhoto::class, 'route_id');
    }

    /**
     * Get all route points in order.
     */
    public function points()
    {
        return $this->hasMany(RoutePoint::class, 'route_id')->orderBy('order');
    }

    /**
     * Get all tags assigned to the route.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'route_tag', 'travel_route_id', 'tag_id');
    }

    /**
     * Get all users who favorited the route.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'route_id');
    }
}