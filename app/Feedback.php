<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = "feedbacks";

    protected $fillable = [
        'title',
        'description',
        'location',
        'image',
        'solved'
    ];

    /** Each feedback is belonged to one user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function user() {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    function campus() {
        return $this->belongsTo(\App\Campus::class, 'campus_id', 'id');
    }

    function category() {
        return $this->belongsTo(\App\Category::class, 'category_id', 'id');
    }

    function comments() {
        return $this->hasMany(\App\Comment::class, 'feedback_id', 'id');
    }
}
