<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This model represents a user role.
 * It defines permissions through user roles.
 */
class Role extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = ['name'];

    /**
     * Get all users assigned to this role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}