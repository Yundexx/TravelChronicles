<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    /** @use HasFactory<\Database\Factories\TravelRouteFactory> */
    use HasFactory;
}
