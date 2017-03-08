<?php

namespace App;

use Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Automatically creates hash for the user password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /** One user owns many feedbacks
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    function feedbacks() {
        return $this->hasMany(\App\Feedback::class, 'user_id', 'id');
    }

    function comments() {
        return $this->hasMany(\App\Comment::class, 'user_id', 'id');
    }
}
