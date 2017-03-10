<?php

namespace App\Http\Controllers;

use App\Campus;
use App\Http\Transformers\CampusTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CampusesController extends BaseController
{
    function __construct()
    {
        $this->middleware('jwt.auth', [
            'except' => ['index', 'show']
        ]);
    }

    function index() {
        $campuses = Campus::paginate(10);
        return $this->response->paginator($campuses, new CampusTransformer);
    }

    function show($id) {
        $campus = Campus::find($id);

        if(! $campus) {
            return $this->response->errorNotFound("There is no matched feedback");
        }

        return $this->response->item($campus, new CampusTransformer);
    }

    function store (Request $request) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user->can('access_backend')) {
            return $this->response->errorUnauthorized('You are not allow to add new campus!');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        $campus = new Campus();
        $campus->name = $request->get('name');
        $campus->address = $request->get('address');
        $campus->save();

        return $this->response->item($campus, new CampusTransformer);
    }

    function update(Request $request, $id) {
        $user = JWTAuth::parseToken()->authenticate();
        if(!$user->can('access_backend')) {
            return $this->response->errorUnauthorized('You are not allow to update this campus!');
        }

        $campus = Campus::find($id);
        if(!$campus) {
            return $this->response->errorNotFound('There is no matched campus!');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        $campus->name = $request->get('name');
        $campus->address = $request->get('address');
        $campus->save();

        return $this->response->item($campus, new CampusTransformer);
    }

    function destroy($id) {
        $user = JWTAuth::parseToken()->authenticate();
        if(!$user->can('access_backend')) {
            return $this->response->errorUnauthorized('You are not allow to update this campus!');
        }

        $campus = Campus::find($id);
        if(!$campus) {
            return $this->response->errorNotFound('There is no matched campus!');
        }

        $campus->delete();
        return $this->response->noContent();
    }
}
