<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Http\Transformers\FeedbackTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class FeedbacksController extends BaseController
{
    function __construct()
    {
        $this->middleware('cors');
        $this->middleware('jwt.auth', [
            'except' => ['index', 'show']
        ]);
    }

    /** Display all feedback
     * @return \Dingo\Api\Http\Response|\Illuminate\Database\Eloquent\Collection|static[]
     */
    function index() {
        $feedback = Feedback::paginate(10);
        return $this->response->paginator($feedback, new FeedbackTransformer);
    }

    /** Create a new feedback
     * @param Request $request
     * @return \Dingo\Api\Http\Response|\Illuminate\Http\JsonResponse
     */
    function store(Request $request) {
        $input = $request->only([
            'title',
            'description',
            'location',
            'image'
        ]);

        $validator = Validator::make($input, [
            'title' => 'required|max:255|unique:feedbacks,title',
            'description' => 'required',
            'location' => 'required',
            'image' => 'image'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        $original_image = $request->file('image');
        $image_name = hash('sha256', "" . Carbon::now()->getTimestamp()
            . $request->file('image')->getFilename()) . ".jpeg";
        $image_path = storage_path('app/images/') . $image_name;

        $feedback = new Feedback();
        $feedback->title = $input['title'];
        $feedback->description = $input['description'];
        $feedback->location = $input['location'];
        $feedback->image = $image_name;
        $feedback->user_id = JWTAuth::parseToken()->authenticate()->id;
        $feedback->save();

        Image::make($original_image)->encode('jpeg')
            ->save($image_path);

        //$new_image->response()
        return $this->response->item($feedback, new FeedbackTransformer);
    }

    /** Get a single feedback by id
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    function show($id) {
        $feedback = Feedback::find($id);

        if(! $feedback) {
            return $this->response->errorNotFound("There are no matched feedback");
        }

        return $this->response->item($feedback, new FeedbackTransformer);
    }

    /** Update the feedback info
     * @param Request $request
     * @param $id
     * @return \Dingo\Api\Http\Response|\Illuminate\Http\JsonResponse|void
     */
    function update(Request $request, $id) {
        $feedback = Feedback::find($id);

        if(! $feedback) {
            return $this->response->errorNotFound("There are no matched feedback");
        }

        $user_id = JWTAuth::parseToken()->authenticate()->id;
        // User has no access right
        if($user_id != $feedback->user_id) {
            return $this->response->errorUnauthorized("You are not allowed to update this feedback!");
        }

        $input = $request->only([
            'title',
            'description',
            'location',
            'image'
        ]);

        $validator = Validator::make($input, [
            'title' => 'required|max:255|unique:feedbacks,title,'.$id,
            'description' => 'required',
            'location' => 'required',
            'image' => 'image'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        $original_image = $request->file('image');
        $image_name = hash('sha256', "" . Carbon::now()->getTimestamp()
                . $request->file('image')->getFilename()) . ".jpeg";
        $image_path = storage_path('app/images/') . $image_name;
        $old_image_path = storage_path('app/images/') . $feedback->image;

        $feedback->title = $input['title'];
        $feedback->description = $input['description'];
        $feedback->location = $input['location'];
        $feedback->image = $image_name;
        $feedback->save();

        Image::make($original_image)->encode('jpeg')
            ->save($image_path);

        if(file_exists($old_image_path)) {
            Storage::disk('local')->delete("/images/" . $feedback->image);
        }

        return $this->response->item($feedback, new FeedbackTransformer);
    }

    function destroy($id) {
        $feedback = Feedback::find($id);

        if(! $feedback) {
            return $this->response->errorNotFound("There are no matched feedback");
        }

        $user_id = JWTAuth::parseToken()->authenticate()->id;
        // User has no access right
        if($user_id != $feedback->user_id) {
            return $this->response->errorUnauthorized("You are not allowed to delete this feedback!");
        }

        $old_image_path = storage_path('app/images/') . $feedback->image;
        if(file_exists($old_image_path)) {
            Storage::disk('local')->delete("/images/" . $feedback->image);
        }

        $feedback->delete();

        return $this->response->noContent();
    }
}
