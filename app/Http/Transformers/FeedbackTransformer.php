<?php

namespace App\Http\Transformers;

use \App\Feedback;
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
            'location' => $feedback->location,
            'campus' => [
                'id' => $feedback->campus->id,
                'name' => $feedback->campus->name,
                'address' => $feedback->campus->address,
            ],
            'user' => [
                'id' => $feedback->user->id,
                'name' => $feedback->user->name,
            ],
            'image' => $feedback->image,
        ];
    }
}