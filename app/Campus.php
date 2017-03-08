<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $table = 'campuses';

    protected $fillable = [
        'name',
        'address',
    ];

    function feedback() {
        $this->hasMany(Feedback::class, 'campus_id', 'id');
    }
}
