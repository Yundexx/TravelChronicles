<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RoutePoint;
use App\Models\RoutePhoto;
use App\Models\User;

class Travel_route extends Model
{
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(RoutePhoto::class, 'route_id');
    }

    public function points()
    {
        return $this->hasMany(RoutePoint::class, 'route_id')->orderBy('order');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'route_tag', 'travel_route_id', 'tag_id');
    }
    
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'route_id');
    }
    /** @use HasFactory<\Database\Factories\TravelRouteFactory> */
    use HasFactory;
}
