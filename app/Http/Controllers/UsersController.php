<?php

namespace App\Http\Controllers;

use App\Http\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends BaseController
{
    function show($id) {
        $user = User::find($id);

        if(!$user) {
            return $this->response->errorNotFound("User not found");
        }

        return $this->response->item($user, new UserTransformer);
    }

    function update(Request $request, $id) {

    }

    function me() {
        $user_id = JWTAuth::parseToken()->authenticate()->id;

        $user = User::find($user_id);
        if(!$user) {
            return $this->response->errorNotFound("User not found");
        }
        return $this->response->item($user, new UserTransformer);
    }
}
