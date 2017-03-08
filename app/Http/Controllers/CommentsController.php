<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Feedback;
use App\Http\Transformers\CommentTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentsController extends BaseController
{
    function __construct()
    {
        $this->middleware('jwt.auth', [
            'except' => ['index']
        ]);
    }

    function index($feedback_id) {
        $feedback = Feedback::find($feedback_id);

        if(!$feedback) {
            return $this->response->errorNotFound("There is no matched feedback");
        }

        $comments = Comment::paginate(10);
        return $this->response->paginator($comments, new CommentTransformer);
    }

    function store(Request $request, $feedback_id) {
        $feedback = Feedback::find($feedback_id);

        if(!$feedback) {
            return $this->response->errorNotFound("There is no matched feedback");
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|max:255'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = JWTAuth::parseToken()->authenticate()->id;
        $comment->feedback_id = $feedback_id;
        $comment->save();

        return $this->response->item($comment, new CommentTransformer);
    }

    function update(Request $request, $id) {
        $comment = Comment::find($id);

        if(!$comment) {
            return $this->response->errorNotFound("There is no matched comment");
        }

        $user_id = JWTAuth::parseToken()->authenticate()->id;
        if($user_id != $comment->user_id) {
            return $this->response->errorUnauthorized("You are not allowed to update this comment!");
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|max:255'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        $comment->content = $request->get('content');
        $comment->save();

        return $this->response->item($comment, new CommentTransformer);
    }

    function destroy($id) {
        $comment = Comment::find($id);

        if(!$comment) {
            return $this->response->errorNotFound("There is no matched comment");
        }

        $user_id = JWTAuth::parseToken()->authenticate()->id;
        if($user_id != $comment->user_id) {
            return $this->response->errorUnauthorized("You are not allowed to update this comment!");
        }

        $comment->delete();
        return $this->response->noContent();
    }
}
