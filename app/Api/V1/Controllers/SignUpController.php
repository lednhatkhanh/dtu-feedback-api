<?php

namespace App\Api\V1\Controllers;

use Carbon\Carbon;
use Config;
use App\User;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignUpController extends Controller
{
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->only(['name', 'email', 'password']));

        if($request->hasFile('avatar')) {
            $client_file = $request->file('avatar');
            $avatar_name = '' . Carbon::now()->getTimestamp() . '_' . $client_file->getFilename() . '.jpeg';
            $avatar_path = storage_path('app/avatars/') . $avatar_name;

            $user->avatar = $avatar_name;

            Image::make($client_file)->encode('jpeg')
                ->save($avatar_path);
        }

        if(!$user->save()) {
            throw new HttpException(500);
        }

        //$user->assignRole('admin');

        if(!Config::get('boilerplate.sign_up.release_token')) {
            return response()->json([
                'status' => 'ok'
            ], 201);
        }

        $token = $JWTAuth->fromUser($user);
        return response()->json([
            'status' => 'ok',
            'token' => $token
        ], 201);
    }
}
