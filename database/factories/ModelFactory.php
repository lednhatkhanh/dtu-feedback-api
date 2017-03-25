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
        'avatar' => $faker->randomElement(array('avatar1.png', 'avatar2.png', 'avatar3.png', 'avatar4.png'))
    ];
});

$factory->define(\App\Feedback::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(),
        'description' => $faker->paragraph(),
        'location' => $faker->sentence(),
        'campus_id' => $faker->numberBetween(1,3),
        'category_id' => $faker->numberBetween(1, 2),
        'user_id' => $faker->numberBetween(2, 6),
        'image' => $faker->randomElement(array('placeholder1.png', 'placeholder2.png', 'placeholder3.png')),
        'solved' => $faker->boolean(),
        'is_private' => $faker->boolean(),
    ];
});

$factory->define(\App\Comment::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->paragraph(),
        'user_id' => $faker->numberBetween(2, 6),
        'feedback_id' => $faker->numberBetween(1, 30)
    ];
});
