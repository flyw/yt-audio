<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Download;
use Faker\Generator as Faker;

$factory->define(Download::class, function (Faker $faker) {

    return [
        'video_id' => $faker->word,
        'path' => $faker->word,
        'available_format' => $faker->word,
        'selected_format' => $faker->word,
        'title' => $faker->word,
        'thumbnail_path' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
