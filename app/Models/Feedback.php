<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This model represents route feedback.
 * It stores feedback submitted by users.
 */
class Feedback extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = ['route_id', 'user_id', 'feedback'];

    /**
     * Database table associated with the model.
     */
    protected $table = 'feedbacks';

    /**
     * Get the user who submitted the feedback.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}