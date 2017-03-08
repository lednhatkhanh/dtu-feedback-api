<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');
    });

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });

    $api->group(['middleware' => 'cors'], function(Router $api) {
        $api->get('feedbacks/{feedback}/comments', 'App\\Http\\Controllers\\CommentsController@index');
        //$api->get('comments/{comment}', 'App\\Http\\Controllers\\CommentsController@show');
        $api->post('feedbacks/{feedback}/comments', 'App\\Http\\Controllers\\CommentsController@store');
        $api->patch('comments/{comment}', 'App\\Http\\Controllers\\CommentsController@update');
        $api->delete('comments/{comment}', 'App\\Http\\Controllers\\CommentsController@destroy');

        $api->resource('feedbacks', 'App\\Http\\Controllers\\FeedbacksController', [
            'except' => ['edit', 'create']
        ]);
    });
});
