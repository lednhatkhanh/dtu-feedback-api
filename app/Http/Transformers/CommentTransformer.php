<?php
/**
 * Created by PhpStorm.
 * User: lednh
 * Date: 3/8/2017
 * Time: 6:06 PM
 */

namespace App\Http\Transformers;


use App\Comment;

class CommentTransformer
{
    public function transform(Comment $comment) {
        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'feedback_id' => $comment->feedback_id,
            'user_id' => $comment->user_id
        ];
    }
}