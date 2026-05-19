<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function routes()
    {
        return $this->belongsToMany(Travel_route::class, 'route_tag', 'tag_id', 'travel_route_id');
    }
}
