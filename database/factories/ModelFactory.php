<?php

use App\User;
use App\Models\NumberRequest;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $gender = $faker->randomElement(['male', 'female']);
    $role_id = $faker->randomElement(['male', 'female']);
    $company = $faker->randomElement(['Bạch Mai', 'Hoài Đức', 'Thanh Nhàn', 'Y Hà Nội']);

    return [
        'name' => $faker->name,
        'gender' => $gender,
        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'id_number' => mt_rand(111111111111, 999999999999),
        'id_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'id_address' => $faker->address,
        'permanent_residence' => $faker->address,
        'staying_address' => $faker->address,
        'job' => 'doctor',
        'company' => $company,
        'avatar' => 'images.jpeg',
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('12345678'),
        'role_id' => 2,
        'remember_token' => Str::random(10),
    ];
});
