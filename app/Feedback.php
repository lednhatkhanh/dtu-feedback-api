<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    // Really, this is an ERROR...
    protected $table = "feedbacks";

    protected $fillable = [
        'title',
        'description',
        'location',
        'image'
    ];

    /** Each feedback is belonged to one user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function user() {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }
}
