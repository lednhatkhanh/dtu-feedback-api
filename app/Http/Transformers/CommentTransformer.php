<?php
/**
 * Created by PhpStorm.
 * User: lednh
 * Date: 3/8/2017
 * Time: 6:06 PM
 */

namespace App\Http\Transformers;


use App\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    public function transform(Comment $comment) {
        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'feedback' => [
                'id' => $comment->feedback_id,
            ],
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'avatar' => $comment->user->avatar,
            ],
            'created_at' => $comment->created_at->toDateTimeString(),
            'updated_at' => $comment->updated_at->toDateTimeString(),
        ];
    }
}