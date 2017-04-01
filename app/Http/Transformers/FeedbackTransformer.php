<?php

namespace App\Http\Transformers;

use \App\Feedback;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * Created by PhpStorm.
 * User: lednh
 * Date: 3/7/2017
 * Time: 4:30 PM
 */
class FeedbackTransformer extends TransformerAbstract
{
    public function transform(Feedback $feedback) {
        return [
            'id' => $feedback->id,
            'title' => $feedback->title,
            'description' => $feedback->description,
            'image' => $feedback->image,
            'location' => $feedback->location,
            'solved' => $feedback->solved ? true : false,
            'campus' => [
                'id' => $feedback->campus->id,
                'name' => $feedback->campus->name,
            ],
            'category' => [
                'id' => $feedback->category->id,
                'name' => $feedback->category->name,
            ],
            'user' => [
                'id' => $feedback->user->id,
                'name' => $feedback->user->name,
                'avatar' => $feedback->user->avatar,
            ],
            'created_at' => $feedback->created_at->toDateTimeString(),
            'updated_at' => $feedback->updated_at->toDateTimeString()
        ];
    }
}