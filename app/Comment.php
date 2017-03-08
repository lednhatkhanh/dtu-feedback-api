<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'content'
    ];

    function user() {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    function feedback() {
        return $this->belongsTo(\App\Feedback::class, 'feedback_id', 'id');
    }
}
