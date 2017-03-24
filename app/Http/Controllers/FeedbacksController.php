<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Http\Transformers\FeedbackTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class FeedbacksController extends BaseController
{
    function __construct()
    {
        $this->middleware('jwt.auth', [
            'except' => ['index', 'show']
        ]);
    }

    /** Display all feedback
     * @param Request $request
     * @return \Dingo\Api\Http\Response|\Illuminate\Http\JsonResponse
     */
    function index(Request $request) {

        $validator = Validator::make($request->all(), [
           'sort' => 'integer|in:0,1',
            'solved' => 'string|in:true,false,all', // This is a BUG with laravel...
            'limit' => 'integer',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        $feedback = null;

        if($request->exists('sort') && $request->get('sort') == 0) {
            $feedback = Feedback::orderBy('created_at', 'asc');
        } else {
            $feedback = Feedback::orderBy('created_at', 'desc');
        }

        if($request->exists('solved')) {
            $solved = $request->get('solved');
            if($solved === 'true') {
                $feedback = $feedback->where('solved', true);
            } else if($solved === 'false') {
                $feedback = $feedback->where('solved', false);
            }
        }

        $limit = $request->get('limit');

        if($limit) {
            $feedback = $feedback->paginate($limit);
        } else {
            $feedback = $feedback->paginate(10);
        }

        return $this->response->paginator($feedback, new FeedbackTransformer);
    }

    /** Create a new feedback
     * @param Request $request
     * @return \Dingo\Api\Http\Response|\Illuminate\Http\JsonResponse
     */
    function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:feedbacks,title',
            'description' => 'required|min:10',
            'location' => 'required',
            'campus_id' => 'required|exists:campuses,id',
            'image' => 'sometimes|required|image',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        $user_id = JWTAuth::parseToken()->authenticate()->id;

        $feedback = new Feedback();
        $feedback->title = $request->get('title');
        $feedback->description = $request->get('description');
        $feedback->location = $request->get('location');
        $feedback->campus_id = $request->get('campus_id');
        $feedback->solved = false;
        $feedback->user_id = $user_id;

        if($request->hasFile('image')) {
            $original_image = $request->file('image');
            $image_name = '' . Carbon::now()->getTimestamp() . '_' . $user_id . '.jpg';
            $image_path = storage_path('app/images/') . $image_name;

            $feedback->image = $image_name;

            Image::make($original_image)->encode('jpg')
                ->save($image_path);
        }

        $feedback->save();

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
            return $this->response->errorNotFound("There is no matched feedback");
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
            return $this->response->errorNotFound("There is no matched feedback");
        }

        $user = JWTAuth::parseToken()->authenticate();

        if($user->id != $feedback->user_id && !$user->can('access_backend')) {
            return $this->response->errorUnauthorized("You are not allowed to update this feedback!");
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:feedbacks,title,'.$id,
            'description' => 'required',
            'location' => 'required',
            'campus_id' => 'required|exists:campuses,id',
            'image' => 'sometimes|required|image',
            'solved' => 'string'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        if($request->hasFile('image')) {
            $image_name = '' . Carbon::now()->getTimestamp() . '_' . $user->id . '.jpg';
            $image_path = storage_path('app/images/') . $image_name;

            Image::make($request->file('image'))->encode('jpg')
                ->save($image_path);
            $feedback->image = $image_name;
        }

        // Don't delete image, or this is what I think....
//        if($feedback->image) {
//            $old_image_path = storage_path('app/images/') . $feedback->image;
//            if(file_exists($old_image_path)) {
//                Storage::disk('local')->delete('images/' . $feedback->image);
//            }
//        }

        $feedback->title = $request->get('title');
        $feedback->description = $request->get('description');
        $feedback->location = $request->get('location');
        $feedback->campus_id = $request->get('campus_id');

        // This is a laravel bug, really....
        $feedback->solved = $request->get('solved') === 'true' ? true : false;

        $feedback->save();

        return $this->response->item($feedback, new FeedbackTransformer);
    }

    function destroy($id) {
        $feedback = Feedback::find($id);

        if(! $feedback) {
            return $this->response->errorNotFound("There is no matched feedback");
        }

        $user = JWTAuth::parseToken()->authenticate();
        // User has no access right
        if($user->id != $feedback->user_id && !$user->can('access_backend')) {
            return $this->response->errorUnauthorized("You are not allowed to delete this feedback!");
        }

        // Don't delete image, or this is what I think....
//        if($feedback->image) {
//            $old_image_path = storage_path('app/images/') . $feedback->image;
//            if(file_exists($old_image_path)) {
//                Storage::disk('local')->delete('images/' . $feedback->image);
//            }
//        }

        $feedback->delete();
        return $this->response->noContent();
    }

    function toggle($id) {
        $feedback = Feedback::find($id);

        if(! $feedback) {
            return $this->response->errorNotFound("There is no matched feedback");
        }

        $user = JWTAuth::parseToken()->authenticate();
        // User has no access right
        if($user->id != $feedback->user_id && !$user->can('access_backend')) {
            return $this->response->errorUnauthorized("You are not allowed to delete this feedback!");
        }

        $feedback->solved = !$feedback->solved;
        $feedback->save();
        return $this->response->item($feedback, new FeedbackTransformer);
    }
}
