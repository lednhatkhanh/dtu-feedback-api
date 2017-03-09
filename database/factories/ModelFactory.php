<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Feedback::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(),
        'description' => $faker->paragraph(),
        'location' => $faker->sentence(),
        'campus_id' => $faker->numberBetween(1,3),
        'user_id' => $faker->numberBetween(2, 6),
        'image' => $faker->image(storage_path('app/images'), null, null, 'cats', false),
        'solved' => $faker->boolean()
    ];
});

$factory->define(\App\Comment::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->paragraph(),
        'user_id' => $faker->numberBetween(2, 6),
        'feedback_id' => $faker->numberBetween(1, 30)
    ];
});
