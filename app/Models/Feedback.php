<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ['route_id', 'user_id', 'feedback'];
    protected $table = 'feedbacks'; 
    
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
