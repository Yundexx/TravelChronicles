<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel_route extends Model
{
    protected $fillable = [
        'name',
        'start_location',
        'end_location',
        'description',
        'flagged'
    ];
    /** @use HasFactory<\Database\Factories\TravelRouteFactory> */
    use HasFactory;
}
