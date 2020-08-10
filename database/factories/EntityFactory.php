<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Entity;
use Faker\Generator as Faker;

$factory->define(Entity::class, function (Faker $faker) {

    return [
        'channel_id' => $faker->word,
        'title' => $faker->word,
        'video_id' => $faker->word,
        'published' => $faker->word,
        'updated' => $faker->word,
        'thumbnail' => $faker->word,
        'description' => $faker->text,
        'views_count' => $faker->word,
        'rating_count' => $faker->word,
        'rating_average' => $faker->word,
        'is_viewed' => $faker->randomDigitNotNull,
        'viewd_index' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
