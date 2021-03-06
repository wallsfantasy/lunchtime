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
$factory->define(App\Model\User\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'api_token' => str_random(10),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Model\Restaurant\Restaurant::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'description' => $faker->sentence()
    ];
});

$factory->define(\App\Model\Cycle\Cycle::class, function (Faker\Generator $faker) {
    return [
        'id' => "{$faker->uuid}",
        'name' => "{$faker->company} {$faker->companySuffix}",
        'propose_until' => $faker->time(),
        'lunchtime' => $faker->time(),
    ];
});

$factory->define(\App\Model\Cycle\Member::class, function (Faker\Generator $faker) {
    return [];
});

$factory->define(\App\Model\Propose\Propose::class, function (Faker\Generator $faker) {
    return [];
});
