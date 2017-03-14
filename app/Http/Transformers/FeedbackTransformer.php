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
            'location' => $feedback->location,
            'solved' => $feedback->solved ? true : false,
            'campus' => [
                'id' => $feedback->campus->id,
                'name' => $feedback->campus->name,
                'address' => $feedback->campus->address,
            ],
            'user' => [
                'id' => $feedback->user->id,
                'name' => $feedback->user->name,
                'role' => $feedback->user->roles()->pluck('name'),
            ],
            'image' => $feedback->image,
            'created_at' => Carbon::createFromFormat('Y-m-d H:m:s', $feedback->created_at)
                ->format('Y-m-d H:m'),
            'updated_at' => Carbon::createFromFormat('Y-m-d H:m:s', $feedback->updated_at)
                ->format('Y-m-d H:m'),
        ];
    }
}