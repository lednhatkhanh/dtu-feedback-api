<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";

    protected $fillable = [
        'name'
    ];

    function feedbacks() {
        $this->hasMany(Feedback::class, 'category_id', 'id');
    }
}
